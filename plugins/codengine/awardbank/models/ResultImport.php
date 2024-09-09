<?php
namespace Codengine\Awardbank\Models;
use RainLab\User\Models\User as User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Codengine\Awardbank\Models\Team;

class ResultImport extends \Backend\Models\ImportModel
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
                if(isset($data['id']) && !empty($data['id'])){
                    $result = Result::find($data['id']);
                    if($result == null){
                        $result = new Result;
                    } else {
                        $update = true;
                    }
                } else {
                    $result = new Result;
                }
                if(isset($data['resulttypeid']) && !empty($data['resulttypeid'])){
                    $result->resulttype_id = $data['resulttypeid'];
                }
                if(isset($data['attachment_level']) && !empty($data['attachment_level'])){
                    $result->attachment_level = $data['attachment_level'];
                }
                if(isset($data['region']) && !empty($data['region'])){
                    $result->region_id = $data['region'];
                }
                if(isset($data['team']) && !empty($data['team'])){
                    $team = Team::where('external_reference','=',$data['team'])->first();

                    if (isset($data['programid']) && !empty($data['programid'])) {
                        $team = Team::where('external_reference', '=', $data['team'])
                            ->where('program_id', $data['programid'])
                            ->first();
                    }

                    if($team){
                        $result->team_id = $team->id;
                    }
                }
                if(isset($data['user']) && !empty($data['user'])){
                    $user = User::where('email','=',$data['user'])->first();
                    if($user){
                        $result->user_id = $user->id;
                    }
                }
                if(isset($data['value']) && !empty($data['value'])){
                    $result->value = $data['value'];
                }
                if(isset($data['string']) && !empty($data['string'])){
                    $result->string = $data['string'];
                }
                if(isset($data['is_current']) && !empty($data['is_current'])){
                    if($data['is_current'] == '1' || $data['is_current'] == true){
                        $result->is_current = true;
                    } else {
                        $result->is_current = false;
                    }
                } else {
                    $result->is_current = false;
                }
                if(isset($data['month_index']) && !empty($data['month_index'])){
                    $result->month_index = $data['month_index'];
                }
                if(isset($data['year_index']) && !empty($data['year_index'])){
                    $result->year_index = $data['year_index'];
                }
                if(isset($data['row']) && !empty($data['row'])) {
                     $result->row = $data['row'];
                }
                //$result->magicAttachments();
                $result->save();
                if($update == true){
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
