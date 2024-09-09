<?php namespace Codengine\Awardbank\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Categorys extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Codengine\Awardbank\Behaviors\ReorderController',
        'Backend.Behaviors.ImportExportController',
        'Backend.Behaviors.RelationController'
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $importExportConfig = 'config_import_export.yaml'; 
    public $relationConfig = 'config_relation.yaml';         
    public $requiredPermissions = ['codengine.awardbank.content_manager'];
    public $prefilter = null;
    public $createcontext = null;

    public function __construct()
    {
        parent::__construct();

        if(isset($this->params[1])){
            if($this->params[1] == 'product'){
                $this->requiredPermissions = ['codengine.awardbank.supplier_manager'];
                BackendMenu::setContext('Codengine.Awardbank', 'suppleriersproducts', 'categories');    
                $this->createcontext = 'product';        
            } else if ($this->params[1] == 'post'){ 
                $this->requiredPermissions = ['codengine.awardbank.content_manager'];            
                BackendMenu::setContext('Codengine.Awardbank', 'posts', 'post_categories');
                $this->createcontext = 'post'; 
            }
        }
    }

    public function productlist()    // <=== Action method
    {
        $this->prefilter = 'product';
        BackendMenu::setContext('Codengine.Awardbank', 'suppleriersproducts', 'categories');        
        $reorder = $this->reorderList();
        return $reorder;
    }

    public function postlist()    // <=== Action method
    {
        $this->prefilter = 'post';
        BackendMenu::setContext('Codengine.Awardbank', 'posts', 'post_categories');        
        $reorder = $this->reorderList();
        return $reorder;
    }    

    public function reorderList()
    {
        $this->reorder();
        return $this->makePartial('reorder_form');
    }

    public function listExtendQuery($query)
    {
        if($this->prefilter == 'product'){
            $query->isProduct();
        }
         if($this->prefilter == 'post'){
            $query->isPost();
        }       
    }

    public function reorderExtendQuery($query)
    {
        if($this->prefilter == 'product'){
            $query->isProduct();
        }
        if($this->prefilter == 'post'){
            $query->isPost();
        }       
    }


}