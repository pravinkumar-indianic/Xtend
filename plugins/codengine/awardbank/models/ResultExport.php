<?php
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ResultExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $results = [];
        DB::table('codengine_awardbank_results')
            ->select([
                'codengine_awardbank_results.*',
                'codengine_awardbank_result_type.id as resulttypeid',
                'codengine_awardbank_result_type.name as resulttypename',
                'codengine_awardbank_teams.id as team',
                'codengine_awardbank_regions.id as region',
                'users.email as user'
            ])
            ->leftJoin('codengine_awardbank_result_type', 'codengine_awardbank_results.resulttype_id', '=', 'codengine_awardbank_result_type.id')
            ->leftJoin('codengine_awardbank_teams', 'codengine_awardbank_results.team_id', '=', 'codengine_awardbank_teams.id')
            ->leftJoin('codengine_awardbank_regions', 'codengine_awardbank_results.region_id', '=', 'codengine_awardbank_regions.id')
            ->leftJoin('users', 'codengine_awardbank_results.user_id', '=', 'users.id')
            ->orderBy('id')
            ->chunk(1000, function (Collection $rows) use (&$results, $columns) {
                foreach ($rows as $row) {
                    $results[] = collect($row)->only($columns)->toArray();
                }
            });
        return $results;
    }
}
