<?php namespace Codengine\Awardbank\Controllers;

use Codengine\Awardbank\Models\BillingContact;
use Codengine\Awardbank\Models\XeroAPI;
use Backend\Classes\Controller;
use BackendMenu;

class BillingContacts extends Controller
{
    public $implement = [
    	'Backend\Behaviors\ListController',
    	'Backend\Behaviors\FormController',
    	'Backend\Behaviors\ReorderController'
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Codengine.Awardbank', 'financial', 'billingcontacts');
    }

    public function onXeroSync()
    {
        $xero = XeroAPI::getXero();
        $contacts = $xero->load(\XeroPHP\Models\Accounting\Contact::class)->execute();
        foreach ($contacts as $contact) {
            $checkexisting = BillingContact::where('xero_id','=',$contact->ContactID)->first();
            if($checkexisting == null){
                $billingcontact = new \Codengine\Awardbank\Models\BillingContact;
                $billingcontact->xero_id = $contact->ContactID;
            } else {
                $billingcontact = $checkexisting;
            }
            $billingcontact->sync = true;
            $billingcontact->accountnumber = $contact->accountnumber;
            $billingcontact->contactstatus = $contact->ContactStatus;
            $billingcontact->name = $contact->Name;
            $billingcontact->firstname = $contact->FirstName;
            $billingcontact->lastname = $contact->LastName;
            $billingcontact->emailaddress = $contact->EmailAddress;
            $billingcontact->trackingcategoryname = $contact->TrackingCategoryName;
            $billingcontact->trackingcategoryoptions = $contact->TrackingCategoryOption;
            $billingcontact->taxnumber = $contact->TaxNumber;
            $billingcontact->is_supplier = $contact->IsSupplier;
            $billingcontact->is_customer = $contact->IsCustomer;
            $billingcontact->default_currency = $contact->DefaultCurrency;
            $billingcontact->sales_default_account_code = $contact->SalesDefaultAccountCode;
            $billingcontact->purchase_default_account_code = $contact->PurchasesDefaultAccountCode;
            $billingcontact->purchase_tracking_categories = $contact->PurchasesTrackingCategories;
            $billingcontact->tracking_category_name = $contact->TrackingCategoryName;
            $billingcontact->tracking_option_name = $contact->TrackingCategoryOption;
            // $billingcontact->payment_terms = $contact->PaymentTerms;
            $billingcontact->contact_groups = $contact->ContactGroups;
            $billingcontact->website = $contact->Website;
            $billingcontact->branding_theme = $contact->BrandingTheme->BrandingThemeID ?? null;
            $billingcontact->discount = $contact->Discount;
            $billingcontact->save();
        }
    }
}
