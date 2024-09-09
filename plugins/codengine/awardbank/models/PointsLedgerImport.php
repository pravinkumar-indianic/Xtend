<?php 
namespace Codengine\Awardbank\Models;
use RainLab\User\Models\User as User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PointsLedgerImport extends \Codengine\Awardbank\Models\ImportModel
{
    /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [];

    public function importLog($sessionKey = null) {   

    }    

    protected $resultStats = [
        'processed' => 0, // Added this for overall progress percentage calculation
        'updated' => 0,
        'created' => 0,
        'errors' => [],
        'warnings' => [],
        'skipped' => []
    ];    

    public function importData($results, $sessionKey = null)
    {
        foreach ($results as $row => $data) {

            try {

                $update = false;
                if(isset($data['id']) && !empty($data['id'])){
                    $result = PointsLedger::find($data['id']);
                    if($result == null){
                        $result = new PointsLedger;
                    } else {
                        $update = true;
                    }
                    unset($data['id']);
                } else {
                    $result = new PointsLedger;
                }
                if(isset($data['user']) && !empty($data['user'])){
                    $user = User::where('email','=',$data['user'])->first();
                    if($user){
                        $result->user_id = $user->id;
                        unset($data['user']);
                    }
                }
                if(isset($data['type']) && !empty($data['type'])){
                    if($data['type'] == 'Addition Value'){
                        $data['type'] = 1;
                    } elseif($data['type'] == 'Subtraction Value'){
                        $data['type'] = 2;
                    } elseif($data['type'] == 'In Cart'){
                        $data['type'] = 3;
                    } elseif($data['type'] == 'Out Cart'){
                        $data['type'] = 4;
                    } elseif($data['type'] == 'Program Points Addition'){
                        $data['type'] = 5;
                    } elseif($data['type'] == 'Program Points Transfer'){
                        $data['type'] = 6;
                    } elseif($data['type'] == 'Program Points Return'){
                        $data['type'] = 7;
                    } elseif($data['type'] == 'Program Points Subtraction'){
                        $data['type'] = 8;
                    } else{
                        $data['type'] = 0;
                    }
                } else {
                    $data['type'] = 0;
                }
                if(isset($data['program_id']) && $data['program_id'] == ''){
                    unset($data['program_id']);
                }
                if(isset($data['order_id']) && $data['order_id'] == ''){
                    unset($data['order_id']);
                }
                if(isset($data['product_id']) && $data['product_id'] == ''){
                    unset($data['product_id']);
                }
                if(isset($data['transaction_id']) && $data['transaction_id'] == ''){
                    unset($data['transaction_id']);
                }
                $result->fill($data);
                $result->save();
                if($update == true){
                    $this->logUpdated();
                } else {
                    $this->logCreated();
                }
            }
            catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }

        }
    }
}