<?php namespace Codengine\Awardbank\Models;

use Addgod\MandrillTemplate\Mandrill\Message;
use Addgod\MandrillTemplate\Mandrill\Recipient;
use Addgod\MandrillTemplate\Mandrill\Template;
use Addgod\MandrillTemplate\MandrillTemplateFacade;
use Model;
//use Session;
use Storage;
use Event;
use Renatio\DynamicPDF\Classes\PDF;
use System\Models\File;
use Mail;

/**
 * Model
 */
class OrderLineitem extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_order_line_item';

    public $belongsTo = [

        'order' => [
            'Codengine\Awardbank\Models\Order',
        ],
        'product' => [
            'Codengine\Awardbank\Models\Product',
            'scope' => 'withTrashed'
        ],
        'supplier' => [
            'Codengine\Awardbank\Models\Supplier',
            'key' => 'product_supplier_id',
        ],
        'program' => [
            'Codengine\Awardbank\Models\Program',
            'key' => 'order_program_id',
        ],
        'order_placer' => [
            'RainLab\User\Models\User',
            'key' => 'order_placer_id',
        ],
    ];

    /** SAVE PROCESS **/

    public function afterCreate()
    {
        $this->createPDF();
        $this->sendPurchaseOrder();
    }

    public function beforeSave()
    {

        $this->bounceOrderPlace();
        //$this->bounceProduct();

        $this->order_supplier_name = $this->order->products()->withTrashed()->first()->supplier->name;
        $this->order_program_id = $this->order->order_program_id;
        $this->order_program_name = $this->order->order_program_name;

        if($this->purchase_order_id == null){
            $count = $this->order->orderlineitems->count() + 1;
            $this->purchase_order_id = $this->order->orderplacer->currentProgram->xero_job_code_suffix.'-'.$this->product_id.$this->order->id.$count;
        }

        if($this->invoice_po_variance == null || $this->invoice_po_variance == '' || $this->invoice_po_variance == 0){
            $this->invoice_po_variance = $this->product_invoiced_amount - ($this->product->cost_ex * $this->product_volume);
        }
        if($this->invoice_freight_variance == null || $this->invoice_freight_variance == '' || $this->invoice_freight_variance == 0){
            $this->invoice_freight_variance = $this->product_invoiced_freight - ($this->product->cost_freight * $this->product_volume);
        }

        if($this->last_po_email_sent == ''){
            $this->last_po_email_sent = null;
        }

        $dirty = $this->getDirty();

        if(is_array($dirty) && array_key_exists('product_status', $dirty)){
            if($this->product_status == 7){
                $this->refundLine();
            }
        }
    }

    public function beforeUpdate()
    {
        $this->createPDF();
    }

    /** FILTER SCOPES **/


    public function scopeFilterSuppliers($query, $filter)
    {
        $query->whereIn('product_supplier_id', $filter);
    }

    public function scopeFilterCustomers($query, $filter)
    {
        $query->whereHas('order', function($query) use ($filter){
            $query->whereIn('user_id', $filter);
        });
    }

    /** RENDER TO PDF **/

    public function createPDF()
    {

        $templateCode = 'renatio::invoice'; // unique code of the template
        if ($this->product->supplier->address){
            $supplieraddress = $this->product->supplier->address->full_address;
        } else {
            $supplieraddress = 'Supplier Address Not Set';
        }
        if (!empty($this->order->orderplacer) && !empty($this->order->orderplacer->phone_number)) {
            $phone = $this->order->orderplacer->phone_number;
        } else {
            $phone = 'NA';
        }
        $shippingaddress = $this->order->shippingaddress->getPOaddressAttribute();
        $data = [
            'po_number' => $this->purchase_order_id,
            'suppliername' => $this->product->supplier->name,
            'supplieraddress' => $supplieraddress,
            'suppliercontact' => $this->product->supplier->primary_contact_name,
            'supplierphone' => $this->product->supplier->primary_contact_number,
            'supplieremail' => $this->product->supplier->primary_contact_email,
            'deliverbusiness' => $this->order->shippingaddress->business_name,
            'delivercontact' => $this->order->shippingaddress->attn_name,
            'delivername' => $this->order->orderplacer->full_name,
            'deliveraddress' => $shippingaddress,
            'deliverphone' => $phone,
            //'deliveremail' => $this->order_customer_email,
            'productmodel' => $this->product->model_number,
            'productname' => $this->product->name,
            'productcolor' => $this->option1_selection,
            'productsize' => $this->option2_selection,
            'productcustom' => $this->option3_selection,
            'productprice' => ($this->product->cost_ex * $this->product_volume),
            'productshipping' => $this->product->cost_freight,
            'note' => $this->notes,
            'deliver_notes' => $this->order->delivery_notes,
            'product_volume' => $this->product_volume,
            'date' => $this->order->created_at,
        ]; // optional data used in template
        $pdf = PDF::loadTemplate($templateCode, $data)->output();
        Storage::put("media/purchaseorders/".$this->purchase_order_id.".pdf", $pdf);
        Storage::setVisibility("media/purchaseorders/".$this->purchase_order_id.".pdf", 'public');
        $url = Storage::url("media/purchaseorders/".$this->purchase_order_id.".pdf", $pdf);
        $this->purchase_order_url = $url;

    }

    /**SEND E-MAIL TO SUPPLIER**/

    public function sendPurchaseOrder()
    {

        $toemails = ['orders@evtmarketing.com.au'];
        //$toemails = [$this->supplier->orders_email];
        $ccemails = ['orders@evtmarketing.com.au', 'anthony@evtmarketing.com.au'];
        $vars = [
            'title' => 'New Purchase Order',
            'suppliername' => $this->supplier->name,
            'ponumber' => $this->purchase_order_id,
            'pourl' => $this->purchase_order_url,
        ];

        $template = new Template('xtend-po-email-xtend-2-0');
        $message = new Message();
        $message->setSubject("EVT Marketing - Xtend System - $this->purchase_order_id");
        $message->setFromEmail('noreply@xtendsystem.com');
        $message->setMergeVars($vars);

        if (!empty($ccemails)) {
            foreach ($ccemails as $ccemail) {
                $recipient = new Recipient($ccemail, null, Recipient\Type::CC);
                $recipient->setMergeVars($vars);
                $message->addRecipient($recipient);
            }
        }

        foreach ($toemails as $toemail) {
            $recipient = new Recipient($toemail, null, Recipient\Type::TO);
            $recipient->setMergeVars($vars);
            $message->addRecipient($recipient);
        }

        MandrillTemplateFacade::send($template, $message);

        $this->last_po_email_sent = now();
        $this->save();

    }

    /** BOUNCE DOWN VARIABLES FROM RELATIONSHIPS**/

    public function bounceOrderPlace()
    {
        if($this->order){
            $this->order_placer_id = $this->order->user_id;
        }
        if($this->order_placer){
            $this->order_customer_email = $this->order_placer->email;
            $this->order_customer_address = $this->order_placer->getAddressByType('shipping');
            $this->order_customer_phone_number = $this->order_placer->phone_number;
        } else {
            $this->order_customer_email = 'Problem Finding Order Placer';
            $this->order_customer_address = 'Problem Finding Order Placer';
            $this->order_customer_phone_number = 'Problem Finding Order Placer';
        }
    }

    /** GET STATUS ATTRIBUTES **/

    public function getProductStatusOptions()
    {
        return [
            0 => 'Processing',
            1 => 'Order Placed With Supplier',
            2 => 'On Backorder',
            3 => 'In Warehouse',
            4 => 'Item Ready For Dispatch',
            5 => 'Item Dispatched',
            6 => 'Product Delivered',
            7 => 'Product Cancelled + Refund Points',
        ];
    }

    public function getProductStatusColumnsAttribute()
    {
        $value = array_get($this->attributes, 'product_status');
        return array_get($this->getProductStatusOptions(), $value);
    }

    /** REFUND LINE **/

    public function refundLine()
    {
        $pointsvalue = $this->product_dollar_value * $this->program->scale_points_by;
        try {

            $pointsledger = new PointsLedger();
            $pointsledger->points_value = $pointsvalue;
            $pointsledger->user_id = $this->order->orderplacer->id;
            $pointsledger->program_id = $this->program->id;
            $pointsledger->order_id = $this->order->id;
            $pointsledger->type = 1;
            $pointsledger->save();

        } catch (\Exception $ex) {
            return $ex;
        }
    }
}
