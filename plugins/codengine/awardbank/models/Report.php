<?php
namespace Codengine\Awardbank\Models;

use BackendAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Model;
use Db;
use RainLab\User\Models\User;

/**
 * Model
 */
class Report extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $dates = ['deleted_at'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'type' => 'required',
        'program_id' => 'required',
        'date_from' => 'required',
        'date_to' => 'required',
    ];

    public $belongsTo = [
        'program' => 'Codengine\Awardbank\Models\Program',
        'user' => 'Backend\Models\User',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_reports';

    public function beforeCreate()
    {
        $this->user_id = BackendAuth::getUser()->id;
        $this->filename = implode("_", [
           $this->type, 'report', time()
        ]).'.csv';

        try {
            $this->generateReport();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(501, 'Error while creating report file');
        }
    }

    /**
     * Delete the report file before deleting DB record
     */
    public function beforeDelete()
    {
        //unlink on S3
        Storage::disk('s3')->delete('reports/' . $this->filename);
    }


    private function generateReport()
    {
        switch($this->type) {
            case 'pointsledger' : return $this->generatePointsLedgerReport();
            case 'financial' : return $this->generateFinancialReport();
            default: return false;
        }
    }

    private function generatePointsLedgerReport()
    {
        $startDate = date('Y-m-d 00:00:00', strtotime($this->date_from));
        $endDate = date('Y-m-d 23:59:59', strtotime($this->date_to));
        $pointsLedgers = $this->getPointsLedgerData($this->program_id, $startDate, $endDate);
    }

    private function getPointsLedgerData($programId, $startDate, $endDate)
    {
        $ledgers = \Illuminate\Support\Facades\DB::select("
            SELECT l.id, l.updated_at, l.dollar_value, l.points_value, l.type,l.xero_id,
	               u.full_name as 'user', pm.`name` as 'program', l.order_id, pd.`name` as 'product', l.transaction_id
            FROM
                codengine_awardbank_points_ledger l
            LEFT JOIN users u ON u.id = l.user_id
            LEFT JOIN codengine_awardbank_programs pm ON pm.id = l.program_id
            LEFT JOIN codengine_awardbank_products pd ON pd.id = l.product_id
            WHERE l.deleted_at IS NULL 
            AND l.program_id = ?
            AND l.created_at between CAST(? AS DATE) AND CAST(? AS DATE)
        ", [$programId, $startDate, $endDate]);

        $this->exportPointsLedgerReportAsCsv($ledgers);
    }

    /**
     * Create csv file
     * @param $data
     */
    private function exportPointsLedgerReportAsCsv($data)
    {
        $directoryPath = storage_path();
        $fileName = $this->filename;
        $fullPath = $directoryPath . '/reports/' . $fileName;
        if (!file_exists($directoryPath.'/reports')) { //create dir first if we have to
            mkdir($directoryPath.'/reports', 0755, true);
        }

        $handle = fopen($fullPath, 'w');

        //header row
        fputcsv($handle, array(
            'ID',
            'UPDATED_AT',
            'DOLLAR VALUE',
            'POINTS VALUE',
            'TYPE',
            'XERO ID',
            'USER',
            'PROGRAM',
            'ORDER',
            'PRODUCT',
            'TRANSACTION'
        ));

        foreach($data as $userId => $row) {
            $typeLabel = $this->getTypeLabel(intval($row->type));

            fputcsv($handle, array(
                    $row->id,
                    $row->updated_at,
                    $row->dollar_value,
                    $row->points_value,
                    $typeLabel,
                    $row->xero_id,
                    $row->user,
                    $row->program,
                    $row->order_id,
                    $row->product,
                    $row->transaction_id
                )
            );
        }

        fclose($handle);

        if ($this->uploadFileToS3Bucket($fullPath, $fileName)) {
            unlink($fullPath);
        }

        return true;
    }

    private function getTypeLabel($type)
    {
        switch($type) {
            case 0 : return 'Fixed Value';
            case 1 : return 'Addition Value';
            case 2 : return 'Subtraction Value';
            case 3 : return 'In Cart';
            case 4 : return 'Out Cart';
            case 5 : return 'Program Points Addition';
            case 6 : return 'Program Points Transfer';
            case 7 : return 'Program Points Return';
            case 8 : return 'Program Points Subtraction';
            default: return $type;
        }
    }

    private function generateFinancialReport()
    {
        //A Username & Full name & Business name
        //NOTE - refunds should not be added to B but deducted from C
        //B Total points allocated to that user (points ledger items appearing as 'Addition Value' and blank fields) - type 1
        //C Total points spent on orders by that user (taken from 'order line items')
        //D Total points subtracted by that user (i.e. not related to orders)(taken from points ledger items appearing as 'subtraction value') - type 2
        //E Total points remain in that user's account (B-C)
        //F Dollar value of B
        //G Dollar value of C
        //H Dollar value of D
        //I Dollar value of E
        //ADDITIONAL SUM ROW

        $startDate = date('Y-m-d 00:00:00', strtotime($this->date_from));
        $endDate = date('Y-m-d 23:59:59', strtotime($this->date_to));
        $userPoints = $this->getUserProgramPointsData($this->program_id, $startDate, $endDate);
        $users = $this->getUserData($this->program_id);
        $order_data = $this->getUserProgramOrdersData($this->program_id, $startDate, $endDate);

        $reportData = [];
        if (!empty($users)) {
            foreach ($users as $userId => $data) {
                $reportData[$userId] = $data;
                $reportData[$userId]['points'] = !empty($userPoints[$userId]) ? $userPoints[$userId] : [];
                $reportData[$userId]['order_data'] = !empty($order_data[$userId]) ? $order_data[$userId] : [];
            }
        }

        $this->exportFinancialReportAsCsv($reportData);
    }

    /**
     * Get User Program data
     * @param null $programId
     * @return array
     */
    private function getUserProgramPointsData($programId, $startDate, $endDate){
        //DB::enableQueryLog();
        $ledgers = \Illuminate\Support\Facades\DB::select(
            "Select t.user_id, 
           IF(t.type = 1 AND t.order_id IS NOT NULL, 'refund', t.type) as t_type, 
           sum(points_value) as total_points, 
           sum(dollar_value) as total_dollars
           from codengine_awardbank_points_ledger t
           inner join(
                Select max(id) id, user_id
                from codengine_awardbank_points_ledger
                where type = 0
                group by user_id
                union
                Select min(id) id, user_id
                from codengine_awardbank_points_ledger
                where user_id not in (
                    Select distinct user_id
                    from codengine_awardbank_points_ledger
                    where type = 0
                    and user_id is not null
                    and deleted_at is null
                )
                group by user_id
            ) a on t.id >= a.id
            And t.user_id = a.user_id
            where t.program_id = ?
            AND t.created_at between CAST(? AS DATE) AND CAST(? AS DATE)
            AND t.deleted_at is null
            group by t.user_id, t_type
            order by t.user_id",
            [$programId, $startDate, $endDate]
        );
        //dd(DB::getQueryLog());

        $userPoints = [];
        foreach ($ledgers as $row) {
            $userPoints[$row->user_id][$row->t_type] = [
                'points_value' => $row->total_points ?? 0,
                'dollar_value' => $row->total_dollars ?? 0
            ];
        }

        return $userPoints;
    }

    /**
     * Get User Program Orders Data
     * @param null $programId
     * @return array
     */
    private function getUserProgramOrdersData($programId, $start_date, $end_date){
        $query = DB::table('codengine_awardbank_orders')
            ->selectRaw('user_id, SUM(points_value) as points_value, SUM(dollar_value) as dollar_value')
            ->where('order_program_id', '=', $programId)
            ->whereBetween(
                'created_at', [$start_date, $end_date]
            )
            ->groupBy('user_id')
            ->orderBy('user_id');

        $data = $query->get();
        $orders = [];

        foreach ($data as $row) {
            $orders[$row->user_id] = [
                'points_value' => $row->points_value ?? 0,
                'dollar_value' => $row->dollar_value ?? 0
            ];
        }
        return $orders;
    }

    /**
     * Get User Data
     * @param array $userList
     * @return array
     */
    private function getUserData($program_id){
        $userList = DB::table('codengine_awardbank_u_p')
            ->select('user_id')
            ->where('program_id', '=', $program_id)
            ->pluck('user_id')
            ->toArray();

        if (!empty($userList) && is_array($userList)) {
            $rows = User::whereIn('id', $userList)
                ->select('id', 'username', 'name', 'surname', 'business_name')
                ->get();

            $users = [];
            foreach($rows as $row) {
                $users[$row->id] = [
                    'username' => $row->username,
                    'name' => trim(implode(' ', [$row->name, $row->surname])),
                    'business_name' => $row->business_name,
                ];
            }
            return $users;
        }

        return [];
    }

    /**
     * Create csv file
     * @param $data
     */
    private function exportFinancialReportAsCsv($data){
        $directoryPath = storage_path();
        $fileName = $this->filename;
        $fullPath = $directoryPath . '/reports/' . $fileName;
        if (!file_exists($directoryPath.'/reports')) { //create dir first if we have to
            mkdir($directoryPath.'/reports', 0755, true);
        }

        $handle = fopen($fullPath, 'w');

        //header row
        fputcsv($handle, array(
            'Username',
            'Full name',
            'Business Name',
            'Total Points',
            'Points Spent',
            'Total Points Subtracted',
            'Total Points Balance',
            'Total Points $ Value',
            'Points Spent $ Value',
            'Points Subtracted $ Value',
            'Total Points Balance $ Value'
        ));

        //Types
        //0 - Fixed, 1 - Addition, 2 - Subtraction, 3 - In Cart, 4 - Out Cart

        foreach($data as $userId => $row) {
            $points = $row['points'][0]['points_value'] ?? 0;
            $additionsPoints = $row['points'][1]['points_value'] ?? 0;
            $subtractionsPoints = $row['points'][2]['points_value'] ?? 0;
            $inCartPoints = $row['points'][3]['points_value'] ?? 0;
            $outCartPoints = $row['points'][4]['points_value'] ?? 0;

            //Do not consider cart points
            $cartCurrentPoints = 0; //$inCartPoints - $outCartPoints;
            $refundedPoints = isset($row['points']['refund']['points_value']) ? $row['points']['refund']['points_value'] : 0;
            $currentPoints = $points + $additionsPoints + $refundedPoints - $subtractionsPoints - $cartCurrentPoints;
            $spentPoints = (isset($row['order_data']['points_value']) ? $row['order_data']['points_value'] : 0) - $refundedPoints;

            //Dollar Value
            $dollars = $row['points'][0]['dollar_value'] ?? 0;
            $additionsDollars = $row['points'][1]['dollar_value'] ?? 0;
            $subtractionsDollars = $row['points'][2]['dollar_value'] ?? 0;
            $inCartDollars = $row['points'][3]['dollar_value'] ?? 0;
            $outCartDollars = $row['points'][4]['dollar_value'] ?? 0;

            //Do not consider cart dollars
            $cartCurrentDollars = 0; //$inCartDollars - $outCartDollars;
            $refundedDollars = isset($row['points']['refund']['dollar_value']) ? $row['points']['refund']['dollar_value'] : 0;
            $currentDollars = $dollars + $additionsDollars + $refundedDollars - $subtractionsDollars - $cartCurrentDollars;
            $spentDollars = (isset($row['order_data']['dollar_value']) ? $row['order_data']['dollar_value'] : 0) - $refundedDollars;

            fputcsv($handle, array(
                    $row['username'],
                    $row['name'],
                    $row['business_name'],
                    $additionsPoints,
                    $spentPoints,
                    $subtractionsPoints,
                    $currentPoints,
                    $additionsDollars,
                    $spentDollars,
                    $subtractionsDollars,
                    $currentDollars
                )
            );
        }

        fclose($handle);

        if ($this->uploadFileToS3Bucket($fullPath, $fileName)) {
            unlink($fullPath);
        }

        return true;
    }

    private function uploadFileToS3Bucket($filepath, $fileName){
        $file = file_get_contents($filepath);
        return Storage::disk('s3')->put('reports/' . $fileName, $file);
    }
}
