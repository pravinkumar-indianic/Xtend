<?php

namespace Codengine\Awardbank\Controllers;

use Exception;
use BackendMenu;
use Backend\Classes\Controller;
use Codengine\Awardbank\Models\Result;
use Db;
use Illuminate\Support\Facades\Log;
use October\Rain\Exception\ValidationException;
use October\Rain\Support\Facades\Flash;

class Results extends Controller
{
    public $implement = ['Backend\Behaviors\ListController', 'Backend\Behaviors\FormController', 'Backend\Behaviors\ReorderController', 'Backend.Behaviors.ImportExportController', 'Backend.Behaviors.RelationController'];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $importExportConfig = 'config_import_export.yaml';
    public $relationConfig = 'config_relation.yaml';
    public $requiredPermissions = ['codengine.awardbank.user_manager'];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Codengine.Awardbank', 'results', 'results');
    }

    public function onMarkCurrentStatus()
    {
        return $this->makePartial('mark_program_current_status');
    }

    public function onMarkProgramCurrentStatus()
    {
        $startval = intval(post('startval'));

        $endval = intval(post('endval'));

        $offset = intval(post('offset'));

        $currentStatus = post('currentStatus') == 1 ? true : false;

        if ($offset == null || $offset == '') {

            $offset = $startval;

        }

        if (empty($endval) || $endval == null || $endval == '') {

            $endval = Result::orderBy('id', 'desc')->first();

            $endval = $endval->id;

            $endval = intval($endval);

        }

        if ($offset + 49 <= $endval) {

            $terminate = $offset + 49;

        } else {

            $terminate = $endval;

        }


        $results = Result::where('id', '>=', $offset)->where('id', '<=', $terminate)->get();

        Db::beginTransaction();
        try {
            foreach ($results as $result) {
                $result->is_current = $currentStatus;
                $result->save();
            }

            Db::commit();

        } catch (Exception $ex) {
            Log::error($ex);
            Db::rollback();
            throw new ValidationException(['error' => 'Oops, something went wrong']);
        }


        $result['startval'] = intval($startval);

        $result['endval'] = intval($endval);

        $result['offset'] = $offset + 50;

        return $result;

    }
}
