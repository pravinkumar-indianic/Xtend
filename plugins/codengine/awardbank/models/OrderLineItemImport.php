<?php 
namespace Codengine\Awardbank\Models;
use Codengine\Awardbank\Models\OrderLineitem;
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
                    $award = OrderLineitem::find($data['id']);
                    $update = true;;
                }

                if($award == null){
                    $award = new OrderLineitem;
                    $update = false;
                }
                if (isset($data['product_status_columns'])) {
                    $data['product_status'] = $this->mapProductStatus($data['product_status_columns']);
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
     /**
     * Maps product status column to its corresponding index.
     *
     * @param string $statusColumn
     * @return int
     */
    private function mapProductStatus($statusColumn): int
    {
        $statusIndex = $this->getProductStatusIndex();
        
        // Cast to string to ensure matching keys
        $statusColumn = (string) $statusColumn;

        if (!array_key_exists($statusColumn, $statusIndex)) {
            \Log::warning("Invalid product status value: $statusColumn");
            return 0;
        }

        return $statusIndex[$statusColumn];
    }


     /**
     * Returns the mapping of product statuses to their corresponding indexes.
     *
     * @return array
     */
    public function getProductStatusIndex(): array
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
}