<?php

namespace Codengine\Awardbank\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class GoogleAnalyticsExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $rolls = GoogleAnalytics::all();

        $result = [];

        foreach ($rolls as $roll) {
            $return = [];
            foreach ($columns as $column) {
                $return[$column] = $roll->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}
