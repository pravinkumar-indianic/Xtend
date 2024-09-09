<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ResultTypeImport extends \Backend\Models\ImportModel
{
    /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [];

    public function importData($results, $sessionKey = null)
    {
        foreach ($results as $row => $data) {

            try {
                $updated = false;
                $resultType = null;

                if(isset($data['id']) && !empty($data['id'])){
                    $resultType = ResultType::find($data['id']);
                    $updated = true;
                }
                if(empty($resultType)){
                    $resultType = new ResultType;
                }
                unset($data['id']);
                if(isset($data['resultgroup']) && !empty($data['resultgroup']) && $data['resultgroup'] != 'empty'){
                    $resultgroup = $data['resultgroup'];
                    unset($data['resultgroup']);
                }
                $resultType->fill($data);
                $resultType->resultgroup_id = $resultgroup;
                $resultType->save();

                if($updated == true){
                    $this->logUpdated();
                } else {
                    $this->logCreated();
                }
            }
            catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }

        }
    }
}