<?php namespace Codengine\Awardbank\Models;

use Addgod\MandrillTemplate\MandrillTemplateFacade;
use Addgod\MandrillTemplate\Mandrill\Message;
use Addgod\MandrillTemplate\Mandrill\Recipient;
use Addgod\MandrillTemplate\Mandrill\Template;
use Event;
use GuzzleHttp\Exception\ClientException;
use Mail;
use Model;
use Renatio\DynamicPDF\Classes\PDF;
use Storage;
use System\Models\File;

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
     * [$fillable description]
     * @var [type]
     */
    protected $fillable = ['order_id','product_id','product_name','product_image','product_dollar_value','product_slug','product_deliver_base','product_category_array','product_supplier_id','product_supplier_name','product_status','product_voucher','product_voucher_code','shipped_date','arrived_date','invoice_id','connote_id','shipping_id','shipping_name','option1_selection','option2_selection','notes','product_invoiced_amount','product_invoiced_freight'];
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

        if ($this->order) {
            $product = $this->order->products()->withTrashed()->first();

            if ($product && $product->supplier) {
                $this->order_supplier_name = $product->supplier->name;
            }

            $this->order_program_id = $this->order->order_program_id ?? null;
            $this->order_program_name = $this->order->order_program_name ?? null;
        }


        if ($this->purchase_order_id == null) {
            if ($this->order && $this->order->orderlineitems) {
                $count = $this->order->orderlineitems->count() + 1;
            } else {
                $count = 1;
            }

            if (
                $this->order && 
                $this->order->orderplacer && 
                $this->order->orderplacer->currentProgram
            ) {
                $this->purchase_order_id = 
                    $this->order->orderplacer->currentProgram->xero_job_code_suffix . 
                    '-' . $this->product_id . 
                    $this->order->id . 
                    $count;
            }
        }


        if (
            $this->invoice_po_variance === null || 
            $this->invoice_po_variance === '' || 
            $this->invoice_po_variance == 0
        ) {
            if ($this->product && isset($this->product->cost_ex)) {
                $this->invoice_po_variance = 
                    $this->product_invoiced_amount - ($this->product->cost_ex * $this->product_volume);
            }
        }

        if (
            $this->invoice_freight_variance === null || 
            $this->invoice_freight_variance === '' || 
            $this->invoice_freight_variance == 0
        ) {
            if ($this->product && isset($this->product->cost_freight)) {
                $this->invoice_freight_variance = 
                    $this->product_invoiced_freight - ($this->product->cost_freight * $this->product_volume);
            }
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

        // Supplier-related information
        $supplier = $this->product->supplier ?? null;
        $supplierAddress = $supplier->address->full_address ?? 'Supplier Address Not Set';
        $supplierName = $supplier->name ?? 'Supplier Name Not Set';
        $supplierContact = $supplier->primary_contact_name ?? 'NA';
        $supplierPhone = $supplier->primary_contact_number ?? 'NA';
        $supplierEmail = $supplier->primary_contact_email ?? 'NA';

        // Orderplacer-related information
        $orderplacer = $this->order->orderplacer ?? null;
        $deliverName = $orderplacer->full_name ?? 'NA';
        $phone = $orderplacer->phone_number ?? 'NA';

        // Shipping-related information
        $shippingAddressObj = $this->order->shippingaddress ?? null;
         $shippingAddress = $shippingAddressObj 
        ? $shippingAddressObj->getPOaddressAttribute() 
        : 'Shipping Address Not Set';
        $businessName = $this->order->shippingaddress->business_name ?? 'NA';
        $attnName = $this->order->shippingaddress->attn_name ?? 'NA';

        // Product-related information
        $productPrice = ($this->product->cost_ex ?? 0) * $this->product_volume;
        $productShipping = $this->product->cost_freight ?? 0;

        $data = [
            'po_number' => $this->purchase_order_id,
            'suppliername' => $supplierName,
            'supplieraddress' => $supplierAddress,
            'suppliercontact' => $supplierContact,
            'supplierphone' => $supplierPhone,
            'supplieremail' => $supplierEmail,
            'deliverbusiness' => $businessName,
            'delivercontact' => $attnName,
            'delivername' => $deliverName,
            'deliveraddress' => $shippingAddress,
            'deliverphone' => $phone,
            'productmodel' => $this->product->model_number ?? 'NA',
            'productname' => $this->product->name ?? 'NA',
            'productcolor' => $this->option1_selection ?? 'NA',
            'productsize' => $this->option2_selection ?? 'NA',
            'productcustom' => $this->option3_selection ?? 'NA',
            'productprice' => $productPrice,
            'productshipping' => $productShipping,
            'note' => $this->notes ?? '',
            'deliver_notes' => $this->order->delivery_notes ?? '',
            'product_volume' => $this->product_volume ?? 0,
            'date' => $this->order->created_at ?? now(),
        ];

        // Generate PDF and store it
        $pdf = PDF::loadTemplate($templateCode, $data)->output();
        $path = "media/purchaseorders/" . $this->purchase_order_id . ".pdf";
        Storage::put($path, $pdf);
        Storage::setVisibility($path, 'public');

        // Set the public URL
        $this->purchase_order_url = Storage::url($path);
    }


    /**SEND E-MAIL TO SUPPLIER**/

    public function sendPurchaseOrder()
    {
        // Ensure supplier and purchase order details are available
        $supplierName = $this->supplier->name ?? 'Unknown Supplier';
        $purchaseOrderId = $this->purchase_order_id ?? 'NA';
        $purchaseOrderUrl = $this->purchase_order_url ?? '#';

        // Email recipients
        $toEmails = ['orders@evtmarketing.com.au'];  // Default recipients
        // $toEmails = [$this->supplier->orders_email]; // Uncomment if supplier email is dynamic

        $ccEmails = ['orders@evtmarketing.com.au', 'anthony@evtmarketing.com.au'];

        // Email template variables
        $vars = [
            'title' => 'New Purchase Order',
            'suppliername' => $supplierName,
            'ponumber' => $purchaseOrderId,
            'pourl' => $purchaseOrderUrl,
        ];

        // Prepare email template and message
        $template = new Template('xtend-po-email-xtend-2-0');
        $message = new Message();
        $message->setSubject("EVT Marketing - Xtend System - $purchaseOrderId");
        $message->setFromEmail('noreply@xtendsystem.com');
        $message->setMergeVars($vars);

        // Add CC recipients (if any)
        if (!empty($ccEmails)) {
            foreach ($ccEmails as $ccEmail) {
                $recipient = new Recipient($ccEmail, null, Recipient\Type::CC);
                $recipient->setMergeVars($vars);
                $message->addRecipient($recipient);
            }
        }

        // Add TO recipients
        foreach ($toEmails as $toEmail) {
            $recipient = new Recipient($toEmail, null, Recipient\Type::TO);
            $recipient->setMergeVars($vars);
            $message->addRecipient($recipient);
        }

        // Send the email
        try {
            MandrillTemplateFacade::send($template, $message);
            $this->last_po_email_sent = now();  // Log email send time
            $this->save();
        } catch (ClientException $e) {
            \Log::error("Guzzle ClientException: " . $e->getMessage());
            \Log::error("Response: " . $e->getResponse()->getBody()->getContents());
        } catch (\Exception $e) {
            \Log::error("Failed to send purchase order email: " . $e->getMessage());
        }
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
    /**
     * [getProductStatusOptions description]
     * @return [type] [description]
     */
    public function getProductStatusIndex()
    {
        return [
            'Processing' => 0,
            'Order Placed With Supplier' => 1,
            'On Backorder' => 2,
            'In Warehouse' => 3,
            'Item Ready For Dispatch' => 4,
            'Item Dispatched' => 5,
            'Product Delivered' => 6,
            'Product Cancelled + Refund Points' => 7,
        ];
    }

    public function getProductStatusColumnsAttribute()
    {
        $value = array_get($this->attributes, 'product_status');
        info(array_get($this->getProductStatusOptions(), $value));
        return array_get($this->getProductStatusOptions(), $value);
    }
    // public function getProductStatusAttribute()
    // {
    //     $value = array_get($this->attributes, 'product_status');
    //     return array_get($this->getProductStatusOptions(), $value);
    // }


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
