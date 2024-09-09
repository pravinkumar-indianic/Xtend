<?php namespace Codengine\Awardbank\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Session;
use Crypt;
use Event;
use Flash;
use Redirect;
use Codengine\Awardbank\Models\Product as Product;

class Products extends Controller
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

    protected $addressFormWidget;

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Codengine.Awardbank', 'suppleriersproducts', 'products');

        $this->addressFormWidget = $this->vars['itemFormWidget'] = $this->createAddressFormWidget();

    }

    protected function createAddressFormWidget()
    {
        $config = $this->makeConfig('$/codengine/awardbank/models/address/fields-relation.yaml');
        $config->alias = 'addressForm';
        $config->arrayName = 'Address';
        $config->model = new \Codengine\Awardbank\Models\Address;
        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();
        return $widget;
    }

    public function listExtendQuery($query)
    {

    }

    public function listFilterExtendQuery($query, $scope)
    {

        if($scope->scopeName == 'categories'){
            $query->where('type','=','product');
        }
    }

    public function formExtendQuery($query)
    {
        $query->withTrashed();
    }


    public function onRestore()
    {

        $array = post('checked');
        if (is_array($array) && count($array)) {
            $restores = Product::withTrashed()->whereIn('id', $array)->get();
            foreach($restores as $restore){
                $restore->restore();
            }
        }

        return $this->listRefresh();
    }

    public function onPopupFullRefresh()
    {

        return $this->makePartial('popup_import_all');

    }

    public function onRefreshProducts()
    {

        $startval = intval(post('startval'));

        $endval = intval(post('endval'));

        $offset = intval(post('offset'));

        if($offset == null || $offset == ''){

            $offset = $startval;

        }

        if(empty($endval) || $endval == null || $endval == ''){

            $endval = Product::orderBy('id', 'desc')->first();

            $endval = $endval->id;

            $endval = intval($endval);

        }

        if($offset+49 <= $endval){

            $terminate = $offset+49;

        } else {

            $terminate = $endval;

        }

        $products = Product::where('id', '>=', $offset)->where('id', '<=', $terminate)->get();

        foreach($products as $product){

            $product->save();

        }

        $result['startval'] = intval($startval);

        $result['endval'] = intval($endval);

        $result['offset'] = $offset+50;

        return $result;


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


        if($record->completeness_score != null){
            $integer = ceil($record->completeness_score);
            $incomplete = 'complete '.$integer;
            if($integer >= 80){
                $incomplete = 'xl-incomplete';
            } else if ($integer >= 60 && $integer <= 79){
                $incomplete = 'l-incomplete';
            } else if ($integer >= 40 && $integer <= 59){
                $incomplete = 'm-incomplete';
            } else if ($integer >= 20 && $integer <= 39){
                $incomplete = 's-incomplete';
            } else if ($integer >= 1 && $integer <= 19){
                $incomplete = 'xs-incomplete';
            }
            $result .= ' '.$incomplete;
        }


        return $result;
    }

    public function onClearFilters(){

        Session::forget('widget');
        return Redirect::refresh();

    }

}
