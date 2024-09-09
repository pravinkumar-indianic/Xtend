<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProgramImport extends \Backend\Models\ImportModel
{

     /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [];

    public function importData($results, $sessionKey = null)
    {

        $findAbleData = [
            'id',
            'external_reference',
        ];

        $relationshipArray = [
            'teams' => [],
            'users' => [],
            'managers' => [],
        ];

        foreach ($results as $row => $data) {
            $updated = false;
            $parent = null;
            try {
                $model = null;
                foreach($findAbleData as $key){
                    if(isset($data[$key])){
                        $model = $this->checkExistingModel($key,$data[$key]);
                    }
                }
                if($model == null){
                    $model = new Program;
                } else {
                    $updated = true;
                }
                foreach($relationshipArray as $key => $value){
                    $result = $this->relationShipColumnProcess($data,$key);
                    $data = $result['data'];
                    $relationshipArray[$key] = $result['sync'];
                }
                foreach($data as $key => $value){
                    $data = $this->removeEmpty($data,$key);
                }    

                $model->fill($data);

                $model->save();

                foreach($relationshipArray as $key => $value){
                    $model->$key()->sync($value);
                }

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

    public function checkExistingModel($name,$data){
        $team = Program::where($name,'=',$data)->first();
        return $team;
    }

    public function removeEmpty($data,$key)
    {
        if(isset($data[$key])){
            if($data[$key] == '' || empty($data[$key])){
                unset($data[$key]);
            }
        }
        return $data;
    }

    public function relationShipColumnProcess($data,$key){
        $sync = null;
        if(isset($data[$key]) && ! empty($data[$key])){
            $sync = explode(';', $data[$key]);
            unset($data[$key]);
        }
        return [
            'data' => $data,
            'sync' => $sync,
        ];
    }
}