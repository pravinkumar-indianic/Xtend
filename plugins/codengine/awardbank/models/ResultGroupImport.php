<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ResultGroupImport extends \Backend\Models\ImportModel
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
                $resultgroup = null;

                if(isset($data['id']) && !empty($data['id'])){
                    $resultgroup = ResultGroup::find($data['id']);
                    $updated = true;
                }
                if(empty($resultgroup)){
                    $resultgroup = new ResultGroup;
                }
                unset($data['id']);
                if(isset($data['program']) && !empty($data['program']) && $data['program'] != 'empty'){
                    $programid = $data['program'];
                    unset($data['program']);
                }
                $resultgroup->fill($data);
                $resultgroup->program_id = $programid;
                $resultgroup->save();

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