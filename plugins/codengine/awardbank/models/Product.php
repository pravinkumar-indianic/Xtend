<?php namespace Codengine\Awardbank\Models;

use Model;
use Session;
use Auth;
use BackendAuth;
use DB;
use Schema;
use Carbon\Carbon;

/**
 * Model
 */
class Product extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    use \October\Rain\Database\Traits\Revisionable;

    use \October\Rain\Database\Traits\Sluggable;

    protected $dates = ['deleted_at'];

    protected $slugs = ['slug' => 'name'];

    protected $revisionable = [
        'name',
        'model_number',
        'brand',
        'primary_color',
        'description',
        'cost_currency',
        'cost_ex',
        'cost_freight',
        'cost_rrp'
    ];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'id',
        'name',
        'deleted_at',
        'model_number',
        'brand',
        'category-string',
        'category-string-check',
        'primary_color',
        'description',
        'external_image_url',
        'cost_currency',
        'cost_ex',
        'cost_tax_percent',
        'cost_freight',
        'cost_gross',
        'mark_up_override',
        'mark_up_type',
        'mark_up_integer',
        'cost_rrp',
        'cost_final_cost',
        'points_source',
        'points_value',
        'points_margin',
    ];

    /*
     * Validation
     */
    public $rules = [
        'name' => 'required',
        'cost_ex' => 'required|numeric',
        'cost_freight' => 'required|numeric',
        'mark_up_type' => 'required',
        'mark_up_integer' => 'required|numeric',
        'cost_rrp' => 'required|numeric',
    ];

    public $jsonable = [

        'options1',
        'options2',
        'options3',

    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_products';

    public $belongsTo = [
        'supplier' => 'Codengine\Awardbank\Models\Supplier',
        'replaces' => ['Codengine\Awardbank\Models\Product', 'key' => 'replacement_id', 'otherKey' => 'id'],
    ];

    public $belongsToMany = [
        'orders' => ['Codengine\Awardbank\Models\Order', 'table' => 'codengine_awardbank_order_products'],
        'program_exclusions' => ['Codengine\Awardbank\Models\Program', 'table' => 'codengine_awardbank_p_p_ex'],
    ];

    public $hasMany = [
        'replacements' => ['Codengine\Awardbank\Models\Product','key' => 'replacement_id', 'otherKey' => 'id'],
    ];

    public $attachOne = [
        'feature_image' => 'System\Models\File',
        'feature_image_mobile' => 'System\Models\File',
    ];

    public $attachMany = [
        'images' => 'System\Models\File'
    ];

    public $morphToMany = [
        'owners' => [
            'Codengine\Awardbank\Models\Permission',
            'table' => 'codengine_awardbank_permission_entity_allocation',
            'name'=>'permissionentityallocatable',
            'scope' => 'isOwner'],
        'viewability' => [
            'Codengine\Awardbank\Models\Permission',
            'table' => 'codengine_awardbank_permission_entity_allocation',
            'name'=>'permissionentityallocatable',
            'scope' => 'isViewable'
        ],
        'wishlist' => [
            'Codengine\Awardbank\Models\Permission',
            'table' => 'codengine_awardbank_permission_entity_allocation',
            'name'=>'permissionentityallocatable',
            'scope' => 'isWishlist'
        ],
        'cart' => [
            'Codengine\Awardbank\Models\Permission',
            'table' => 'codengine_awardbank_permission_entity_allocation',
            'name'=>'permissionentityallocatable',
            'scope' => 'isCart'
        ],
        'categories' => [
            'Codengine\Awardbank\Models\Category',
            'table' => 'codengine_awardbank_category_allocation',
            'name'=>'entity',
            'scope'=>'isProduct',
        ],
        'tags' => [
            'Codengine\Awardbank\Models\Tag',
            'table' => 'codengine_awardbank_tag_allocation',
            'name'=>'entity'
        ],
    ];

    public $morphMany = [
        'revision_history' => ['System\Models\Revision', 'name' => 'revisionable']
    ];

    public function getRevisionableUser()
    {
        if (Auth::getUser() != NULL)
            return Auth::getUser()->id;

        return BackendAuth::getUser()->id;
    }

    /**
        MODEL EVENT PROCESSES
    **/

   public function beforeSave(){

        $this->slugAttributes();

        /** Escape Useless Characters From Name**/

        //$this->name = str_replace(array('.', ','), '' , $this->name);

        /**
            CALCULATE ACTUAL COST OF PRODUCT INCLUDING SHIPPING AND TAX
        **/

        if($this->cost_ex != null){
            $cost_ex = $this->cost_ex;
        } else {
            $cost_ex = 0;
        }

        if($this->cost_freight != null){
            $cost_freight = $this->cost_freight;
        } else {
            $cost_freight = 0;
        }

        if(strtolower($this->cost_currency) == 'aud'){
            $this->cost_tax_percent = 10;
        }
        if(strtolower($this->cost_currency) == 'nzd'){
            $this->cost_tax_percent = 15;
        }
        if(strtolower($this->cost_currency) == 'eur'){
            $this->cost_tax_percent = 23;
        }
        if(strtolower($this->cost_currency) == 'gbp'){
            $this->cost_tax_percent = 20;
        }
        if(strtolower($this->cost_currency) == 'fdd'){
            $this->cost_tax_percent = 2.5;
        }

        if(strtolower($this->cost_currency) == 'ntx'){
            $this->cost_tax_percent = 0;
        }

        if($this->cost_tax_percent != null){
            $cost_tax_percent = 100 + $this->cost_tax_percent;
        } else {
            $cost_tax_percent = 100;
        }

        $this->cost_gross = ($cost_ex + $cost_freight) * ($cost_tax_percent/100);

        /**
            FIND PRODUCT MARKUPS
        **/

        if(strtolower($this->mark_up_type) == 'percent'){
            $product_percent = 100 + $this->mark_up_integer;
            $product_dollars = 0;

        } elseif(strtolower($this->mark_up_type) == 'dollar'){
            $product_percent = 100;
            $product_dollars = $this->mark_up_integer;
        } else {
            $product_percent = 100;
            $product_dollars = 0;
        }

        /**
            FIND SUPPLIER MARKUPS
        **/

        if($this->supplier){
            if(strtolower($this->supplier->mark_up_type) == 'percent'){
                $supplier_percent = 100 + $this->supplier->mark_up_integer;
                $supplier_dollars = 0;

            } elseif(strtolower($this->supplier->mark_up_type) == 'dollar'){
                $supplier_percent = 100;
                $supplier_dollars = $this->supplier->mark_up_integer;
            } else {
                $supplier_percent = 100;
                $supplier_dollars = 0;
            }

            $this->territory = $this->supplier->territory;

        } else {

            $supplier_percent = 100;
            $supplier_dollars = 0;
            $this->territory = 'all';

        }

        /**
            APPLY MARKUPS

        **/

        if($this->mark_up_override == true){
            $this->cost_final_cost = ( $this->cost_gross * ($product_percent/100)) + $product_dollars;
        } elseif($this->mark_up_override == false){

            $price = ($this->cost_gross * ($supplier_percent/100)) + $supplier_dollars;
            $price = ($price * ($product_percent/100)) + $product_dollars;
            $this->cost_final_cost = $price;
        }

        /**
            DECIDE BEST POINT SOURCE FOR PROFIT
        **/

        if($this->points_source == null || $this->points_source == 'none'){

            if($this->cost_final_cost >= $this->cost_rrp){

                $this->points_source = 'calculated';

            } else {

                $this->points_source = 'rrp';
            }
        }

        /**
            APPLY POINTS VALUE AND CALCULATE MARKUP
        **/

        if(strtolower($this->points_source) == 'calculated'){

            $this->points_value = ceil($this->cost_final_cost);

        } else {

            $this->points_value = ceil($this->cost_rrp);

        }

        $this->points_margin = $this->points_value - $this->cost_gross;

        $completeChecks = $this->runCompletenessCheck();

        if(Schema::hasColumn($this->getTable(), 'completeness_score')){

                $this->completeness_score = $completeChecks['score'];

                $this->completeness_array =  json_encode($completeChecks['completeness_array']);

                $this->image_valid = $completeChecks['image_valid'];

                if($this->supplier){

                    $this->supplier_name = $this->supplier->name;

                }

                if($this->categories){

                    $result = [];

                    foreach($this->categories as $category){

                        if(!array_key_exists($category->id, $result)){

                            $result[$category->id] = $category->name;
                        }
                    }

                    $this->category_array = json_encode($result);

                }

                if($this->tags){

                    $result = [];

                    foreach($this->tags as $tag){

                        if(!array_key_exists($tag->id, $result)){

                            $result[$tag->id] = $tag->name;
                        }
                    }

                    $this->tag_array = json_encode($result);

                }

        }

    }

    public function afterSave(){

        $catstring = 'category-string-check';
        if($this->$catstring == true){
            $catstring = 'category-string';
            if($this->$catstring){
                $arr = preg_split("/[,&@><#]/", $this->$catstring);
                foreach($arr as $var){
                    $var = trim($var);
                    $categories = Category::where('name','like', '%'.$var.'%')->isProduct()->get();

                    foreach($categories as $category){

                        $category->product()->add($category);

                    }
                }
            }
        }
    }

    public function afterFetch(){

        $this->name = html_entity_decode($this->name);

    }

    /**
        RELATION SCOPES
    **/

    public function scopeIsValidForUser($query, $user)
    {
        $query->where('active','=', 1)
        ->whereDate('activate_after','<=', Carbon::now())
        ->where('territory','=', $user->current_territory)
        ->leftJoin('codengine_awardbank_category_allocation', 'codengine_awardbank_category_allocation.entity_id', '=', 'codengine_awardbank_products.id')
        ->where('codengine_awardbank_category_allocation.entity_type','=','Codengine\Awardbank\Models\Product')
        ->whereNotIn('codengine_awardbank_category_allocation.category_id', $user->currentProgram->category_exclusion_array);
        if($user->currentProgram->maximum_product_value > 0){
            $lowPrice = 1;
            $highPrice = $user->currentProgram->maximum_product_value;
            $query->where('points_value', '>=', $lowPrice)->where('points_value','<=', $highPrice);
        }
    }

    public function scopeBelongsToSupplier($query, $supplier_id)
    {
        return $query->where('supplier_id', $supplier_id);
    }

    public function scopeRelatedSupplierOnly($query,$relation)
    {
        return $query->where('supplier_id', $relation->id);
    }

    public function scopeNotRelatedSuppliersOnly($query,$relation)
    {
        return $query->where('supplier_id','!=', $relation->id);
    }

    /**
        FILTER SCOPES
    **/

    public function scopeFilterWithInactive($query, $response){
        if($response == true){
            $query->where('active','=',0);
        }
    }

    public function scopeFilterWithFeatured($query, $response){
        if($response == true){
            $query->where('featured','=',1);
        }
    }

    public function scopeFilterDeliverEVT($query, $response){
        if($response == true){
            $query->where('deliver_base','=',1);
        }
    }

    public function scopeFilterWithDeleted($query, $response){
        if($response == true){
            $query->withTrashed();
        }
    }

    public function scopeFilterWithoutSupplier($query, $response){
        if($response == true){
            $query->has('supplier', '=', 0);
        }
    }

    public function scopeFilterIDRange($query, $min, $max){

        $query->where('id','>=',$min)->where('id','<=',$max);

    }

    public function scopeFilterWithoutImage($query){
            $query->where('image_valid','=',0);
    }

    public function scopeFilterPointsRange($query, $min, $max){
        $query->where('points_value','>=',$min)->where('points_value','<=',$max);
    }

    public function scopeFilterMarginRange($query, $min, $max){
        $query->where('points_margin','>=',$min)->where('points_margin','<=',$max);
    }

    public function scopeFilterTimeRange($query,$response){

        /**CHECK IF SESSION HAS SAVED FILTERS OR COMING FROM NEW POST REQUEST**/

        $types['created_range'] = null;
        $types['updated_range'] = null;

        if(!empty($this->getCurrentFilters())){
            foreach($this->getCurrentFilters() as $value){
                foreach($value as $key => $value){
                    if(!empty($value)){
                        $newkey = substr($key,6);
                        $types[$newkey] = $value;
                    }
                }
            }

        } else {

            $types[] = [post('scopeName') => $response ];

        }

        if(is_array($types['created_range'])){

            $column = 'created_at';
            $after = $types['created_range'][0];
            $before = $types['created_range'][1];

        }

        if(is_array($types['updated_range'])){

            $column = 'updated_at';
            $after = $types['updated_range'][0];
            $before = $types['updated_range'][1];

        }

        return $query->where($column, '>=', $after)->where($column, '<=', $before);

    }

    public function scopeFilterProcess($query, $response)
    {
        /**SET DEFAULT LEFT TO RIGHT ORDER TO RUN FILTERS IN**/

        $types['completeness_range'] = null;
        $types['products_filter'] = null;
        $types['suppliers'] = null;
        $types['cost_currency'] = null;
        $types['categories'] = null;
        $types['tags'] = null;
        $type['permissions'] = null;
        $type['territory'] = null;
        $type['brand_filtration'] = null;
        $type['organizations'] = null;
        $type['programs'] = null;
        $type['regions'] = null;
        $type['teams'] = null;
        $type['users'] = null;

        /**CHECK IF SESSION HAS SAVED FILTERS OR COMING FROM NEW POST REQUEST**/

        if(!empty($this->getCurrentFilters())){
            foreach($this->getCurrentFilters() as $value){
                foreach($value as $key => $value){
                    if(!empty($value)){
                        $newkey = substr($key,6);
                        $types[$newkey] = $value;
                    }
                }
            }

        } else {

            $types[] = [post('scopeName') => $response ];

        }

        /**RUN THROUGH AVAILABLE KEYS**/

        foreach($types as $key => $type){

            /**COMPLETENESS PROCESS**/

            if($key == 'completeness_range' && !empty($types[$key])){

                $ids = [];

                $bottom = $types[$key][0];
                $top = $types[$key][1];

                foreach($query->get() as $row){
                    $complete = ceil($row->completeness_score);
                    if($complete >= $bottom && $complete <= $top){
                        if(in_array($row->id, $ids) == false){
                            $ids[] = $row->id;
                        }
                    }
                }

                $query->whereIn('id', $ids);

            }

            /**PRODUCT PROCESS**/

            if($key == 'products_filter' && !empty($types[$key])){
                $ids = [];
                foreach($query->get() as $row){
                    if(in_array($row->name, $type)){
                        if(in_array($row->id, $ids) == false){
                            $ids[] = $row->id;
                        }
                    }
                }
                $query->whereIn('id', $ids);
            }

            /**SUPPLIER PROCESS**/

            if($key == 'suppliers' && !empty($types[$key])){

                $query->whereIn('supplier_id', array_values($types[$key]['value']));
            }

            if($key == 'programs' && !empty($types[$key])){

                $query->whereHas('program_exclusions', function ($query) use ($types, $key) {
                    $query->whereIn('program_id', array_values($types[$key]['value']));
                });
            }

            /**SUPPLIER PROCESS**/

            if($key == 'cost_currency' && !empty($types[$key])){
                $ids = [];
                foreach($query->get() as $row){
                    if(array_key_exists($row->cost_currency, $type)){
                        if(in_array($row->id, $ids) == false){
                            $ids[] = $row->id;
                        }
                    }
                }
                $query->whereIn('id', $ids);
            }

            /**CATEGORIES PROCESS**/

             if(($key =='categories' || $key =='tags') && !empty($types[$key])){
                $ids = [];
                foreach($query->get() as $row){
                    if($row->$key->count() >= 1){
                        if($row->$key){
                            foreach($row->$key as $nested){
                                if(array_key_exists($nested->id, $type)){
                                    if(in_array($row->id, $ids) == false){
                                        $ids[] = $row->id;
                                    }
                                }
                            }
                        }
                    }
                }
                $query->whereIn('id', $ids);
            }

            /**PERMISSION FILTER**/

            if($key == 'permissions'  && !empty($types[$key])){
                $ids = [];
                foreach($query->get() as $row){
                    if(array_key_exists('owners', $type)){
                        if($row->owners != null){
                            foreach($row->owners as $owner){
                                if(in_array($row->id, $ids) == false){
                                    $ids[] = $row->id;
                                }
                            }
                        }
                    }
                    if(array_key_exists('viewability',$type)){
                        if($row->viewability){
                            foreach($row->viewability as $viewable){
                                if(in_array($row->id, $ids) == false){
                                    $ids[] = $row->id;
                                }
                            }
                        }
                    }
                    if(array_key_exists('wishlist',$type)){
                        if($row->wishlist){
                            foreach($row->wishlist as $viewable){
                                if(in_array($row->id, $ids) == false){
                                    $ids[] = $row->id;
                                }
                            }
                        }
                    }
                    if(array_key_exists('cart',$type)){
                        if($row->cart){
                            foreach($row->cart as $viewable){
                                if(in_array($row->id, $ids) == false){
                                    $ids[] = $row->id;
                                }
                            }
                        }
                    }
                }
                $query->whereIn('id', $ids);
            }

            if($key == 'territory' && !empty($types[$key])){
                $ids = [];
                foreach($query->get() as $row){
                    if(array_key_exists($row->territory, $type)){
                        if(in_array($row->id, $ids) == false){
                            $ids[] = $row->id;
                        }
                    }
                }
                $query->whereIn('id', $ids);
            }

            if(($key == 'brand_filtration1' || $key == 'brand_filtration2') && !empty($types[$key])){
                foreach($types[$key]['value'] as $brand){
                   $query->where('brand', 'like', $brand."%");
                }
            }

            /**ACCESS FILTER**/

            if(($key == 'organizations'  || $key == 'regions' || $key =='teams' || $key == 'users') && !empty($types[$key])){

                /** CHECK AVAILABLE PERMISSIONS **/

                if(!empty($types['permissions'])){
                    $availablePermissions = $types['permissions'];
                } else {
                    $availablePermissions['owners'] = 'owners';
                    $availablePermissions['viewability'] = 'viewability';
                    $availablePermissions['wishlist'] = 'wishlist';
                    $availablePermissions['cart'] = 'cart';
                }

                $ids = [];

                foreach($query->get() as $row){
                    foreach($availablePermissions as $permissionKey => $permissionValue){
                        if($row->$permissionKey->count() >= 1){
                            foreach($row->$permissionKey as $permission){
                                if($permission->$key){
                                    if($permission->$key->count() >= 1){
                                        foreach($permission->$key as $result){
                                            if(array_key_exists($result->id, $type)){
                                                if(in_array($row->id, $ids) == false){
                                                    $ids[] = $row->id;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $query->whereIn('id', $ids);
            }
        }
    }

    /**
        Permission Relationship Attributes / Printability
    **/

    public function getMyViewAttribute()
    {
        $result = '';
        foreach($this->viewability as $permission){
            $result .= $permission->getAccessNamesAttribute();
        }
        $result = substr($result, 0, -2);
        return $result;
    }

    public function getMyOwnerAttribute()
    {
        $result = '';
        foreach($this->owners as $permission){
            $result .= $permission->getAccessNamesAttribute();
        }
        $result = substr($result, 0, -2);
        return $result;
    }

    /**


    public function getMyWishlistAttribute()
    {
        $result = '';
        foreach($this->wishlist as $permission){
            $result .= $permission->getAccessNamesAttribute();
        }
        $result = substr($result, 0, -2);
        return $result;
    }

    public function getInWishlistAttribute(){

        foreach ($this->wishlist as $wishlist) {
            if($this->id == $wishlist->pivot->permissionentityallocatable_id){
                return true;
            }
        }

        return false;

    }


    public function getInCartAttribute(){

        foreach ($this->cart as $cart) {
            if($this->id == $cart->pivot->permissionentityallocatable_id){
                return true;
            }
        }

        return false;

    }

    public function getMyCartAttribute()
    {
        $result = '';
        foreach($this->cart as $permission){
            $result .= $permission->getAccessNamesAttribute();
        }
        $result = substr($result, 0, -2);
        return $result;
    }

    **/

    public function getMyCategoriesAttribute()
    {

        $resultString = '';
        $results = json_decode($this->category_array, true);

        if(!empty($results)){

            foreach($results as $key => $value){

                if ($value === end($results)):
                    $resultString.= '['.$key.'] '.$value;
                else:
                    $resultString.= '['.$key.'] '.$value.', ';
                endif;

            }
            return $resultString;
        }
    }

    public function getMyTagsAttribute()
    {
        $resultString = '';
        $results = json_decode($this->tag_array, true);

        if(!empty($results)){

            foreach($results as $key => $value){

                if ($value === end($results)):
                    $resultString.= '['.$key.'] '.$value;
                else:
                    $resultString.= '['.$key.'] '.$value.', ';
                endif;

            }
            return $resultString;
        }
    }

    public function getMySupplierMarkupTypeAttribute()
    {
        if($this->supplier){
            $result = $this->supplier->mark_up_type;
        } else {
            $result = 'No Supplier Attached';
        }
        if($result == 'dollar'){
            $result = 'Dollars';
        } else {
            $result = 'Percent';
        }
        return $result;
    }

    public function getMySupplierMarkupIntegerAttribute()
    {
        if($this->supplier){
            $result = $this->supplier->mark_up_integer;
        } else {
            $result = 0;
        }
        return $result;
    }

    public function getCurrentFilters()
    {
        $filters = [];

        foreach (Session::get('widget', []) as $name => $item) {
            if (str_contains($name, 'Filter')) {
                $filter = @unserialize(@base64_decode($item));
                if ($filter) {
                    $filters[] = $filter;
                }
            }
        }

        return $filters;
    }


    public function filterFields($fields, $context = null)
    {

        if(post('product["cost_tax_percent"]')){
            $fields->cost_tax_percent->commentHtml = true;
            $fields->cost_tax_percent->comment = '<i style="color:red;">Unsaved</i>';
            $fields->cost_gross->commentHtml = true;
            $fields->cost_gross->comment = '<i style="color:red;">Unsaved</i>';
            $fields->cost_final_cost->commentHtml = true;
            $fields->cost_final_cost->comment = '<i style="color:red;">Unsaved</i>';
            $fields->points_value->commentHtml = true;
            $fields->points_value->comment = '<i style="color:red;">Unsaved</i>';
            $fields->points_margin->commentHtml = true;
            $fields->points_margin->comment = '<i style="color:red;">Unsaved</i>';
        }

        if (!empty($this->cost_currency)){

            $result = 0;

            if(strtolower($this->cost_currency) == 'aud'){
                $result = 10;
            }
            if(strtolower($this->cost_currency) == 'nzd'){
                $result = 15;
            }
            if(strtolower($this->cost_currency) == 'eur'){
                $result = 23;
            }
            if(strtolower($this->cost_currency) == 'gbp'){
                $result = 20;
            }

            if(strtolower($this->cost_currency) == 'fdd'){
                $result = 2.5;
            }

            if(strtolower($this->cost_currency) == 'ntx'){
                $result = 0;
            }
            $this->cost_tax_percent = $result;
            if(isset($fields->cost_tax_percent->value)){
                $fields->cost_tax_percent->value = $result;
            }
        }

        if(empty($this->cost_tax_percent)){
            $this->cost_tax_percent = 0;
        }

        if(empty($this->cost_ex)){
            $this->cost_ex = 0;
        }

        if(empty($this->cost_freight)){
            $this->cost_freight = 0;
        }

        $cost_gross = (($this->cost_ex + $this->cost_freight) * ((100+ $this->cost_tax_percent)/100));

        if(isset($fields->cost_gross)){
            $fields->cost_gross->value = $cost_gross;
            $this->cost_gross = $cost_gross;
        }

        if($this->supplier){
            $suppliermarkuptype = strtolower($this->supplier->mark_up_type);
            $suppliermarkupinteger = $this->supplier->mark_up_integer;
        } else {
            $suppliermarkuptype = 'percent';
            $suppliermarkupinteger = 0;
        }

        if(empty($this->mark_up_type)){
            $this->mark_up_type = 'percent';
        }

        if(empty($this->mark_up_integer)){
            $this->mark_up_integer = 0;
        }

        $basecost = $this->cost_gross;

        if($this->mark_up_override == false){
            if($suppliermarkuptype == 'percent'){
                $basecost = ($basecost * ((100+$suppliermarkupinteger)/100));
            } else if($suppliermarkuptype == 'dollar'){
                $basecost = $basecost + $suppliermarkupinteger;
            }
        }

        if(strtolower($this->mark_up_type) == 'percent'){
            $cost_final_cost = ($basecost * ((100+$this->mark_up_integer)/100));
        } else if(strtolower($this->mark_up_type) == 'dollar')  {
            $cost_final_cost = ($basecost + $this->mark_up_integer);
        } else {
            $cost_final_cost = $basecost;
        }

        if(isset($fields->cost_final_cost->value)){

            $fields->cost_final_cost->value = $cost_final_cost;
            $this->cost_final_cost = $cost_final_cost;

        }

        if($this->points_source == 'none'){
            if($this->cost_final_cost >= $this->cost_rrp){
                $this->points_source = 'calculated';
            } else {
                $this->points_source = 'rrp';
            }
        }

        if($this->points_source == 'calculated'){
            $this->points_value = ceil($this->cost_final_cost);
        } else if($this->points_source == 'rrp'){
            $this->points_value = ceil($this->cost_rrp);
        }

        if(isset($fields->points_source)){
            $fields->points_source->value = $this->points_source;
        }
        if(isset($fields->points_value)){
            $fields->points_value->value = $this->points_value;
        }

        $this->points_margin = $this->points_value - $this->cost_gross;
        if(isset($fields->points_margi)){
            $fields->points_margin->value = $this->points_margin;
        }

    }

    public function getExternalImagePreviewAttribute()
    {
        return '<img src="'.$this->external_image_url.'" style="height:200px;width:auto;">';
    }

    public function getMyOptionsOneAttribute()
    {

        if(is_array($this->options1) && !empty($this->options1)){
            $string = '';

            foreach($this->options1 as $option){
                $string .= '[';
                $string .= $option['option_name'].',';
                $string .= $option['option_model_number_prefix'].',';
                $string .= $option['option_model_number_suffix'].',';
                $string .= $option['option_model_number_replace'].',';
                $string .= '],';

            }

            $string .= "";
            return $string;

        } else {

            return 'No Options';

        }

    }

    public function getMyOptionsTwoAttribute()
    {

        if(is_array($this->options2) && !empty($this->options2)){
            $string = '';

            foreach($this->options2 as $option){
                $string .= '[';
                $string .= $option['option_name'].',';
                $string .= $option['option_model_number_prefix'].',';
                $string .= $option['option_model_number_suffix'].',';
                $string .= $option['option_model_number_replace'].',';
                $string .= '],';

            }

            $string .= "";
            return $string;

        } else {

            return 'No Options';

        }

    }

    public function getMyOptionsThreeAttribute()
    {

        if(is_array($this->options3) && !empty($this->options3)){
            $string = '';

            foreach($this->options3 as $option){
                $string .= '[';
                $string .= $option['option_name'].',';
                $string .= $option['option_model_number_prefix'].',';
                $string .= $option['option_model_number_suffix'].',';
                $string .= $option['option_model_number_replace'].',';
                $string .= '],';

            }

            $string .= "";
            return $string;

        } else {

            return 'No Options';

        }

    }

    public function hasSomeOptions(){

        if(
            (is_array($this->options1) && !empty($this->options1))  ||
            (is_array($this->options2) && !empty($this->options2))  ||
            (is_array($this->options3) && !empty($this->options3))
        ){
            return true;
        }   else {
            return false;
        }

    }

    public function runCompletenessCheck()
    {

        $score = 0;
        $totalpoints = 0;
        $completeness_array = [];
        $image_valid = true;

        if($this->name == null || $this->name == ''){
            $score = $score + 1;
            $completeness_array['name'] = 'No Name - 1 Point';
        }
        $totalpoints = $totalpoints + 1;

        if($this->model_number == null || $this->model_number == ''){
            $score = $score + 4;
            $completeness_array['model_number'] = 'No Model Number - 4 Points';
        }
        $totalpoints = $totalpoints + 4;

        if($this->brand == null || $this->brand == ''){
            $score = $score + 1;
            $completeness_array['brand'] = 'No Brand - 1 point';
        }
        $totalpoints = $totalpoints + 1;

        if($this->description == null || $this->description == ''){
            $score = $score + 1;
            $completeness_array['description'] = 'No Description - 1 point';
        }
        $totalpoints = $totalpoints + 1;

        if(($this->external_image_url == null || $this->external_image_url == '') && empty($this->feature_image) && $this->feature_image()->withDeferred(post('_session_key'))->count() == 9){
            $score = $score + 4;
            $completeness_array['image'] = 'No Image - 4 points';
            $image_valid = false;

        } else if($this->external_image_url) {

            $check = $this->is_404($this->external_image_url);

            if($check == true){
                $score = $score + 4;
                $completeness_array['image'] = 'Image URL Return 404 - 4 points';
                $image_valid = false;
            }
        }
        $totalpoints = $totalpoints + 4;

        $cat = 'category-string';
        if(($this->$cat == null || $this->$cat == '') && $this->categories == null){
            $score = $score + 1;
            $completeness_array['category-string'] = 'No Category String - 1 points';
        }
        $totalpoints = $totalpoints + 1;

        if($this->cost_ex == null || $this->cost_ex == ''){
            $score = $score + 4;
            $completeness_array['cost_ex'] = 'No Cost Ex. - 4 points';
        }
        $totalpoints = $totalpoints + 4;

        if($this->cost_freight === null || $this->cost_freight === ''){
            $score = $score + 2;
            $completeness_array['cost_freight'] = 'No Cost Freight. - 2 points';
        }
        $totalpoints = $totalpoints + 2;

        if($this->cost_rrp == null || $this->cost_rrp == ''){
            $score = $score + 2;
            $completeness_array['rrp'] = 'No Cost RRP. - 2 points';
        }
        $totalpoints = $totalpoints + 2;

        if($this->supplier == null){
            $score = $score + 2;
            $completeness_array['supplier'] = 'No Supplier - 4 points';
            $score = $score + 4;
        }
        $totalpoints = $totalpoints + 4;

        $score = ($score/$totalpoints)*100;

        $score = number_format($score, 2, '.', '');

        return ['score' => $score, 'completeness_array' => $completeness_array, 'image_valid' => $image_valid];

    }

    public function is_404($url) {
        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

        /* Get the HTML or whatever is linked in $url. */
        $response = curl_exec($handle);

        /* Check for 404 (file not found). */
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        /* If the document has loaded successfully without any redirection or error */
        if ($httpCode == 404) {
            return true;
        } else {
            return false;
        }
    }

    public function getBrandOptions1(){

        return  DB::table('codengine_awardbank_products')->distinct()->limit(500)->get(['brand'])->pluck('brand', 'brand')->toArray();

    }

    public function getBrandOptions2(){

       return DB::table('codengine_awardbank_products')->distinct()->offset(500)->limit(500)->get(['brand'])->pluck('brand', 'brand')->toArray();;

    }
}
