<?php namespace Codengine\Awardbank\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Session;

class Teams extends Controller
{
    public $implement = [
            'Backend\Behaviors\ListController',
            'Backend\Behaviors\FormController',
            'Backend\Behaviors\ReorderController',
            'Backend.Behaviors.ImportExportController',
            'Backend.Behaviors.RelationController',
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $importExportConfig = 'config_import_export.yaml';  
    public $relationConfig = 'config_relation.yaml';       
    
    public function __construct()
    {
        parent::__construct();
         BackendMenu::setContext('Codengine.Awardbank', 'programstructure', 'teams');
    }

    public function reorderExtendQuery($query)
    {
        $array = $this->getFilters();
        if(isset($array[0])){
            $array = $array[0];
            if(!empty($array['scope-program'])){
                $query->whereIn('program_id',array_keys($array['scope-program']));
            }
            if(!empty($array['scope-parent'])){
                $query->whereIn('parent_id',array_keys($array['scope-parent']));
            }
            if(!empty($array['scope-billingcontact'])){
                $query->whereIn('billingcontact_id',array_keys($array['scope-billingcontact_id']));
            }
            if(!empty($array['scope-users'])){
                $query->whereHas('users',function($query) use ($array){
                    $query->whereIn('id',array_keys($array['scope-users']));
                });
            }
            if(!empty($array['scope-managers'])){
                $query->whereHas('managers',function($query) use ($array){
                    $query->whereIn('id',array_keys($array['scope-managers']));
                });
            }
        }
    }

    public function getFilters()
    {
        $filters = [];
        foreach (Session::get('widget', []) as $name => $item) {
            if (str_contains($name, 'Filter')) {
                $filter = @unserialize(@base64_decode($item));
                if ($filter) {
                    array_push($filters, $filter);
                }
            }
        }
        return $filters;
    }        
}