<?php namespace Codengine\Awardbank\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class PointsLedgers extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Codengine\Awardbank\Behaviors\ImportExportController',
        'Backend.Behaviors.RelationController',  
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $importExportConfig = 'config_import_export.yaml'; 
    public $relationConfig = 'config_relation.yaml';     

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Codengine.Awardbank', 'financial', 'PointsLedger');
    }
}
