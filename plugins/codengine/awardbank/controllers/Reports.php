<?php namespace Codengine\Awardbank\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Codengine\Awardbank\Models\Report;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class Reports extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController',
        'Backend.Behaviors.RelationController',
        ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Codengine.Awardbank', 'reports');
        $this->vars['myVariable'] = 'value';
    }

    public function preview($id){
        $report = Report::find($id);
        $this->pageTitle = 'Report Details';
        $this->vars['id'] = $id;
        $this->initForm($report);
    }

    public function download($id){
        $report = Report::find($id);
        if (!$report) {
            return Redirect::to('/backend/codengine/awardbank/reports');
        } else {
            if(!Storage::disk('s3')->exists('reports/' . $report->filename)) {
                abort(404, 'File not found');
            } else {
                $file = Storage::disk('s3')->get('reports/' . $report->filename);
                if(!empty($file)) {
                    $response = Response::make($file, 200);
                    $response->header('Content-Type', 'text/csv');
                    $response->header('Content-Disposition', 'attachment; filename="' . $report->filename . '"');
                    return $response;
                } else {
                    abort(404, 'File not found');
                }
            }
        }
    }
}
