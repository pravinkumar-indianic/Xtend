<?php namespace Codengine\Awardbank\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Prizes extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController',
        //'Backend.Behaviors.ImportExportController',
        'Backend.Behaviors.RelationController'
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    //public $importExportConfig = 'config_import_export.yaml';
    public $relationConfig = 'config_relation.yaml';       
    public $requiredPermissions = ['codengine.awardbank.user_manager'];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Codengine.Awardbank', 'awardnominations' , 'prizes');
    }
}