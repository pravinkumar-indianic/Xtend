<?php namespace Codengine\Awardbank\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Session;
use Crypt;
use Event;
use Flash;

class Posts extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Codengine\Awardbank\Behaviors\ImportExportController',
        'Backend.Behaviors.RelationController',        
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $importExportConfig = 'config_import_export.yaml';     
    public $relationConfig = 'config_relation.yaml';           
    public $requiredPermissions = ['codengine.awardbank.content_manager'];


    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Codengine.Awardbank', 'posts', 'content');
    }
}