<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TeamImport extends \Backend\Models\ImportModel
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
                    $model = new Team;
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

                if(isset($data['parent_id'])){
                    $result = $this->findParent($data);
                    $parent = $result['parent'];
                    $data = $result['data'];
                }    

                $model->fill($data);

                $model->save();

                if($parent){
                    $model->makeChildOf($parent);
                }

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
        $team = Team::where($name,'=',$data)->first();
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

    public function findParent($data)
    {
        $result = [];
        $result['parent'] = Team::find($data['parent_id']);
        unset($data['parent_id']);
        $result['data'] = $data;
        return $result;
    }
}