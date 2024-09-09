<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Rainlab\User\Models\User;
use System\Helpers\DateTime;
use Carbon\Carbon;

class UserImport extends \Backend\Models\ImportModel
{

    /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [];

    public function importData($results, $sessionKey = null)
    {
        foreach ($results as $row => $data) {

            $findAbleData = [
                'id',
                'email',
                'username',
            ];

            /**
            $roughDates = [
                'new_birthday',
                'new_tenure',
            ];
            **/

            $relationshipArray = [
                'programs' => [],
                'teams' => [],
                'programs_manager' => [],
                'teams_manager' => [],
            ];

            $updated = false;

            try {

                foreach($findAbleData as $key){
                    if(isset($data[$key])){
                        $model = $this->checkExistingModel($key,$data[$key]);
                    }
                }

                if($model == null){
                    $model = new User;
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
                /**
                foreach($roughDates as $value){
                    $data = $this->roughDatesProcess($data,$value);
                }      
                **/          

                if(isset($data['password'])){
                    $data['password_confirmation'] = $data['password'];
                }

                $model->fill($data);

                if($model->username == null){
                    $model->username = $model->email.rand().'-'.rand();
                }

                if($model->password == null){
                    $pword = 'randompword'.rand().'-'.rand();
                    $model->password = $pword;
                    $model->password_confirmation = $pword;

                } 
                $model->is_activated = true;
                $model->activated_at = new Carbon;
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
        $user = User::where($name,'=',$data)->first();
        if($user){
            return $user;
        } else {
            return null;
        }
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

    /**
        public function roughDatesProcess($data,$key){
            if(isset($data[$key]) && ! empty($data[$key])){
                $date = strtotime($data[$key]);
                $data[$key] = DateTime::makeCarbon($date); 
            }
            return $data;
        }
    **/
}