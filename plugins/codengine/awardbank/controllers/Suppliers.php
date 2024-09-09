<?php namespace Codengine\Awardbank\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Session;
use Crypt;
use Event;
use Flash;
use Codengine\Awardbank\Models\Supplier as Supplier;

class Suppliers extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController',
        'Codengine\Awardbank\Behaviors\ImportExportController',
        'Backend.Behaviors.RelationController',   
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $importExportConfig = 'config_import_export.yaml';  
    public $relationConfig = 'config_relation.yaml';       
    public $requiredPermissions = ['codengine.awardbank.supplier_manager'];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Codengine.Awardbank', 'suppleriersproducts', 'suppliers');    
       
    }

    public function listExtendQuery($query)
    {
  
        
    }  

    public function onRestore()
    {
        $array = post('checked');
        if (is_array($array) && count($array)) {
            $restores = Supplier::withTrashed()->whereIn('id', $array)->get();
            foreach($restores as $restore){
                $restore->restore();
            }
        }

        return $this->listRefresh();
    }

    public function listFilterExtendQuery($query, $scope)
    {


    }   

    public function listInjectRowClass($record, $definition = null)
    {
        $result = '';

        if ($record->trashed()) {
            $result .= ' strike negative deleted-row';
        }

        if (!$record->trashed()) {
            $result .= ' active-row';
        }

        return $result;
    }

    public function formExtendQuery($query)
    {
        $query->withTrashed();
    }  


}