<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderLineItemImport extends \Backend\Models\ImportModel
{
    /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [];

    public function importData($results, $sessionKey = null)
    {
        foreach ($results as $row => $data) {

            try {
                $update = false;

                if($data['id']){
                    $award = OrderLineItem::find($data['id']);
                    $update = true;;
                }

                if($award == null){
                    $award = new OrderLineItem;
                    $update = false;
                }
                $award->fill($data);
                $award->save();

                if($update == false){
                    $this->logCreated();
                } else {
                    $this->logUpdated();                   
                }
            }
            catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }

        }
    }
}