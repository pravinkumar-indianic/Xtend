<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Codengine\Awardbank\Models\Award;
use Codengine\Awardbank\Models\Prize;
use Codengine\Awardbank\Models\Nomination;
use Auth;
use Event;
use Carbon\Carbon;
use Redirect;
use Response;

class AwardEdit extends ComponentBase
{
    /** MODELS **/

    private $user; 
    private $imagecomponent;
    private $imagecomponent2;
    private $moduleEnabled;
    private $navoptions = [];
    private $navoption;
    private $award;
    private $new = false;
    public $html1;
    public $html2;

    public function componentDetails()
    {
        return [
            'name'        => 'Award Edit',
            'description' => 'Award Edit'
        ];
    }

    public function defineProperties()
    {
        return [

        ];
    }

    public function init()
    {
        $this->user = Auth::getUser();
        if($this->user){
            $this->user =  $this->user->load('currentProgram');
            $this->moduleEnabled = Event::fire('xtend.checkModuleValid', [$this->user,'module_allow_awards'], true);
            $this->getAward();
        }
    }

    public function onRun(){
        $this->addJs('/plugins/codengine/awardbank/assets/js/calendar.min.js');
        $this->addJs('/plugins/codengine/awardbank/assets/js/AwardEdit151119.js');
        if($this->navoption == null){
            $this->navoption = $this->param('navoption');
        }
        $this->imagecomponent->fileList = $this->award->feature_image;
        $this->imagecomponent->singleFile = $this->award->feature_image;
        $this->imagecomponent2->fileList = $this->award->trophy_image;
        $this->imagecomponent2->singleFile = $this->award->trophy_image;
        $this->coreLoadSequence();
    }

    /**
        Component Custom Functions
    **/

    /**
        Reusable function call the core sequence of functions to load and render the Cart partial html
    **/

    public function coreLoadSequence()
    {
        $this->setNavOptions();
        $this->awardFactory();
        $this->generateHtml();
    }

    public function setNavOptions()
    {
        if($this->new == false){
            $this->navoptions = [
                'generaldetails' => 'General Details',
                'images' => 'Images',
                'prizes' => 'Prizes',
                'nominationquestions' => 'Nomination Questions',
                'nominations' => 'Nominations',
                //'votequestions' => 'Votes Questions',
                'votes' => 'Votes',
            ];
            if(array_key_exists($this->navoption, $this->navoptions)){
                $this->navoption = $this->navoption;
            } else {
                $this->navoption = 'generaldetails';
            }
        } else {
            $this->navoptions = [
                'generaldetails' => 'General Details',
            ];     
            $this->navoption = 'generaldetails';       
        }
        //if($this->navoption == 'images'){
            //$this->pageCycle();
        //}
    }

    public function getAward()
    {
        $slug = $this->param('slug');
        if($slug != 'create'){
            $this->award = Award::where('slug','=', $slug)->first();
        }
        if($this->award == null){
            $this->award = Award::find($slug);
        }    
        if($this->award == null || $slug == 'create'){
            $this->award = new Award;
            $this->award->program_id = $this->user->currentProgram->id;
            $this->new = true;
        } 
        if($this->award){
            if($this->award->prizes->count() >= 1){
                $this->award->prizes = $this->award->prizes->sortBy('order');
            }
        }
        $this->imagecomponent = $this->addComponent(
            'Responsiv\Uploader\Components\FileUploader',
            'featureImage',
            [
                'deferredBinding' => false
            ]
        );
        $this->imagecomponent->bindModel('feature_image', $this->award);

        $this->imagecomponent2 = $this->addComponent(
            'Responsiv\Uploader\Components\FileUploader',
            'trophy_image',                      
            [  
                'deferredBinding' => false
            ]
        );
        $this->imagecomponent2->bindModel('trophy_image', $this->award);
    }

    public function awardFactory()
    {
        $this->award->prizes->each(function($prize){
            $prize->managed = true;
            $prize->points_name = $this->award->program->points_name;
            $prize->color = $this->award->secondary_color;
            return $prize;
        });

        $nommanaged = false;
        $managerusers = $this->award->nominationsmanagers()->pluck('id')->toArray();
        if(in_array($this->user->id,$managerusers)){
            $nommanaged = true;
        }

        $this->award->nominations->each(function($nomination) use ($nommanaged){
            $nomination->managed = $nommanaged;
            return $nomination;
        });
    }

    /**%}
        Render the html partials to pass back into public vars.
    **/

    public function generateHtml()
    {
        $this->html1 = $this->renderPartial('@awardlhsnav',
            [
                'navoptions' => $this->navoptions,
                'navoption' => $this->navoption,
                'award' => $this->award,
            ]
        );  
        $this->html2 = $this->renderPartial('@'.$this->navoption,
            [
                'award' => $this->award,
                'new' => $this->new,
            ]
        ); 
    }

    public function testPost($input)
    {
        if($input != '' && !empty($input)){
            return true;
        } else {
            return false;
        }
    }

    /**
        AJAX Requests
    **/

    public function onUpdateTab()
    {
        if($this->testPost(post('navoption')) == true){
            $this->navoption = post('navoption');
            if($this->navoption  == 'images'){
                $result['fileuploaderrun'] = 1;
            }
        } 
        $this->pageCycle();
        $result['html']['#html1target'] = $this->html1;
        $result['html']['#html2target'] = $this->html2;
        return $result;   
    }

    public function onUpdateGeneralDetails()
    {
        try{
            $this->pageCycle();
            if($this->award){
                if($this->testPost(post('Name')) == true){
                    $this->award->name = post('Name');
                }
                if($this->testPost(post('Description')) == true){
                    $this->award->description = post('Description');
                }
                if($this->testPost(post('Award_Start_Date')) == true){
                    $this->award->award_open_at = post('Award_Start_Date');
                }
                if($this->testPost(post('Award_Close_Date')) == true){
                    $this->award->award_close_at = post('Award_Close_Date');
                }
                $this->award->awardallprogramview = post('Program_Can_View');
                $this->award->awardallprogramnominate = post('Program_Can_Nominate');
                $this->award->awardallprogramnominateable = post('Program_Can_Be_Nominated');
                $this->award->awardallprogramvote = post('Program_Can_Vote');
                if($this->testPost(post('Primary_Color')) == true){
                    $this->award->primary_color = post('Primary_Color');
                }
                if($this->testPost(post('Secondary_Color')) == true){
                    $this->award->secondary_color = post('Secondary_Color');
                }
                if($this->testPost(post('Nomination_Name')) == true){
                    $this->award->nomination_display_string = post('Nomination_Name');
                }
                if($this->testPost(post('Nominations_On')) == true){
                    $this->award->hide_nominations_tab = post('Nominations_On');
                }
                $this->award->nominations_approval_required = post('Nominations_Approval');
                $this->award->nominations_public = post('Nominations_Seen');
                $this->award->nomination_image_upload = post('Nominations_Image');
                $this->award->nomination_file_upload = post('Nominations_Upload');
                if($this->testPost(post('Nominations_Open')) == true){
                    $this->award->nominations_open_at = post('Nominations_Open');
                } else {
                    $this->award->nominations_open_at = new Carbon();
                }
                if($this->testPost(post('Nominations_Close')) == true){
                    $this->award->nominations_closed_at = post('Nominations_Close');
                } else {
                    $this->award->nominations_closed_at = new Carbon();
                }
                $this->award->nomination_type = post('Nominations_Target');
                $this->award->hide_voting_tab = post('Voting_On');
                $this->award->votes_approval_required = post('Voting_Approval');
                $this->award->votes_public = 1;
                if($this->testPost(post('Votes_Open')) == true){
                    $this->award->votes_open_at = post('Votes_Open');
                }   else {
                    $this->award->votes_open_at = new Carbon();
                }
                if($this->testPost(post('Votes_Close')) == true){
                    $this->award->votes_close_at = post('Votes_Close');
                } else {
                    $this->award->votes_close_at = new Carbon();
                }
                $this->award->save();
                if($this->new == true){
                    $this->award->managers()->sync([$this->user->id]);
                    $this->award->nominationsmanagers()->sync([$this->user->id]);
                    $this->award->winnersmanagers()->sync([$this->user->id]);
                } else {
                    if($this->testPost(post('Managers')) == true){
                        $array = explode(",",post('Managers'));
                        $this->award->managers()->sync($array);
                    } else{
                        $this->award->managers()->sync([$this->user->id]);
                    }
                    if($this->testPost(post('NominationManagers')) == true){
                        $array = explode(",",post('NominationManagers'));
                        $this->award->nominationsmanagers()->sync($array);
                    } else {
                        $this->award->nominationsmanagers()->sync([$this->user->id]);
                    }
                    if($this->testPost(post('WinnerManagers')) == true){
                        $array = explode(",",post('WinnerManagers'));
                        $this->award->winnersmanagers()->sync($array);
                    } else {
                        $this->award->winnersmanagers()->sync([$this->user->id]);
                    }
                    if($this->testPost(post('NominatableUsers')) == true){
                        $array = explode(",",post('NominatableUsers'));
                        $this->award->nominatableusers()->sync($array);
                    } else {
                        $this->award->nominatableusers()->sync([]);
                    }
                    if($this->testPost(post('NominatableTeams')) == true){
                        $array = explode(",",post('NominatableTeams'));
                        $this->award->nominatableteams()->sync($array);
                    } else {
                        $this->award->nominatableteams()->sync([]);
                    }
                    if($this->testPost(post('NominationTeams')) == true){
                        $array = explode(",",post('NominationTeams'));
                        $this->award->nominationteams()->sync($array);
                    } else {
                        $this->award->nominationteams()->sync([]);
                    }
                    if($this->testPost(post('VoteableTeams')) == true){
                        $array = explode(",",post('VoteableTeams'));
                        $this->award->votableteams()->sync($array);
                    } else {
                        $this->award->votableteams()->sync([]);
                    }
                    if($this->testPost(post('VotingTeams')) == true){
                        $array = explode(",",post('VotingTeams'));
                        $this->award->votingteams()->sync($array);
                    } else {
                        $this->award->votingteams()->sync([]);
                    }
                }
            }
            $this->award->save();
            if($this->new == true){
                return Redirect::to('/award/'.$this->award->slug.'/edit');
            } else {
                $this->navoption = 'generaldetails';
                $this->pageCycle();
                $result['updatesucess'] = "Award updated.";
                $result['html']['#html1target'] = $this->html1;
                $result['html']['#html2target'] = $this->html2;
            }
            return $result;    
        } catch (\Exception $ex) {
            return $ex;
        }          
    }

    public function onManagePrize()
    {
        try{
            $prize = Prize::find(post('id'));
            if(!$prize){
                $prize = new Prize;
                $prize->new = true;
            } else {
                $prize->new = false;                
            }
            $prize = $this->prizeFactory($prize);
            if($prize){
                $this->html2 = $this->renderPartial('@prizeupdate',
                    [
                        'prize' => $prize,
                    ]
                );  
                $result['html']['#html2target'] = $this->html2;
                return $result;     
            } else {
                $result['manualerror'] = "Unable to retrieve Prize record.";
                return $result;
            }      
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onUpdatePrize()
    {
        try{
            $prize = Prize::find(post('id'));
            if(!$prize){
                $prize = new Prize;
                $new = true;
            } else {
                $new = false;
            }
            if($prize){
                $prize->award_id = $this->award->id;
                if($this->testPost(post('Name')) == true){
                    $prize->name = post('Name');
                }
                if($this->testPost(post('Description')) == true){
                    $prize->description = post('Description');
                }
                if($this->testPost(post('Prize_Value')) == true){
                    $prize->prize_value = post('Prize_Value');
                }
                if($this->testPost(post('Order')) == true){
                    $prize->order = post('Order');
                }  
                if($this->testPost(post('Prize_Won')) == true){
                    $prize->iswon = post('Prize_Won');
                }    
                if($this->testPost(post('UserWinners')) == true){
                    $array = explode(",",post('UserWinners'));
                    $prize->userwinners()->sync($array);
                } 
                if($this->testPost(post('TeamWinners')) == true){
                    $array = explode(",",post('TeamWinners'));
                    $prize->teamwinners()->sync($array);
                } 
                $prize->save();
                $prize = $this->prizeFactory($prize);
                $prize->new = false;
                $this->html2 = $this->renderPartial('@prizeupdate',
                    [
                        'prize' => $prize,
                    ]
                );  
                if($new == true){
                    $result['updatesucess'] = "Prize created.";
                } else {
                    $result['updatesucess'] = "Award updated.";
                }
                $result['html']['#html2target'] = $this->html2;
                return $result; 
            } else {

            }
        } catch (\Exception $ex) {
            return $ex;
        }          
    }

    public function onDeletePrize()
    {
        try{
            $prize = Prize::find(post('id'));
            if($prize){
                $prize->delete();
                $this->navoption = 'prizes';
                $this->pageCycle();
                $result['updatesucess'] = "Prize Deleted.";                
                $result['html']['#html2target'] = $this->html2;
                return $result;     
            } else {
                $result['manualerror'] = "Unable to retrieve Prize record.";
                return $result;
            }      
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onShowWinners()
    {
        $prize = Prize::find(post('id'));
        if($prize){
            $result['updatesucess'] = "Congratulations to the following winners.";
            $i = 0;
            $length = $prize->userwinners->count() - 1;
            foreach($prize->userwinners as $winner){
                if($i == 0){
                    $result['updatesucess'] .= ' Users: ';
                }
                $result['updatesucess'] .= $winner->full_name;
                if($i < $length){
                    $result['updatesucess'] .= ', ';
                } else {
                    $result['updatesucess'] .= '.';
                }
                $i++;
            }
            $i = 0;
            $length = $prize->teamwinners->count() - 1;
            foreach($prize->teamwinners as $winner){
                if($i == 0){
                    $result['updatesucess'] .= ' Teams: ';
                }
                $result['updatesucess'] .= $winner->name;
                if($i < $length){
                    $result['updatesucess'] .= ', ';
                } else {
                    $result['updatesucess'] .= '.';
                }   
                $i++;       
            }
            return $result;
        }
    }

    public function prizeFactory($prize){
        $prize->managed = true;
        $prize->points_name = $this->award->program->points_name;
        $managerusers = $this->award->winnersmanagers()->pluck('id')->toArray();
        if(in_array($this->user->id,$managerusers)){
            $prize->winnermanaged = true;
        }
        return $prize;
    }

    public function onAppendQuestion()
    {
        $nextloop = post('nextloop');
        $result['append']['#question-set'] = $this->renderPartial('@questionform',[
                'bigloop' => $nextloop,
        ]);        
        return $result;
    }

    public function onAppendOption()
    {
        $bigloop = post('bigloop');
        $smallloop = post('nextloop');
        $result['append']['#options-set-'.$bigloop] = $this->renderPartial('@optionform',
            [
                'bigloop' => $bigloop,
                'smallloop' => $smallloop,
            ]
        );        
        return $result;        
    }

    public function onUpdateNominationQuestions(){
        $array = [];
        foreach(post() as  $row){
            foreach($row as $question => $value){
                foreach($value as $key => $output){
                    $array[$question][$key] = $output;
                }
            }
        }
        $this->award->nomination_questions_json = $array;
        $this->award->save();
        $result['updatesucess'] = "Questions Saved.";   
        return $result;
    }

    public function onUpdateVoteQuestions(){
        $array = [];
        foreach(post() as  $row){
            foreach($row as $question => $value){
                foreach($value as $key => $output){
                    $array[$question][$key] = $output;
                }
            }
        }
        $this->award->votes_question_json = $array;
        $this->award->save();
        $result['updatesucess'] = "Questions Saved.";   
        return $result;
    }

    public function onNominationApproval()
    {
        $nomination = Nomination::find(post('id'));
        if($nomination){
            if($nomination->approved_at == null){
                $nomination->approved_at = new Carbon();
                $nomination->approved_user_id = $this->user->id;
            } else {
                $nomination->approved_at = null;
                $nomination->approved_user_id = null;
            }
            $nomination->save();
            $this->navoption = 'nominations';
            $this->pageCycle();
            $result['updatesucess'] = "Nomination Updated.";                
            $result['html']['#html2target'] = $this->html2;
            return $result;  
        } else {
            $result['manualerror'] = "Nomination Not Found.";
        }
        return $result;
    }

    public function onExportNominations()
    {
        $this->getAward();
        $filename = "nominations.csv";
        $handle = fopen($filename, 'w+');
        $outputarray = ['Award','Nominated User','Nominating User','Question Answers','Votes','Image Links','File Links'];
        fputcsv($handle, $outputarray);

        foreach($this->award->nominations as $row) {

            if($row->nomination_file){
                $files = '';
                $filenames = '';
                foreach($row->nomination_file as $document){
                    if(!empty($document['attachment'])){
                        $files .= $document->getPath().' ';
                        $filenames .= $document->file_name.' ';
                    } else {
                        $filenames = 'Empty';                        
                    }
                }
            } else {
                $filenames = 'Empty';
            }

            if($row->nomination_image){
                $images = '';
                $imagenames = '';
                foreach($row->nomination_image as $document){
                    if(!empty($document['attachment'])){
                        $images .= $document->getPath().' ';
                        $imagenames .= $document->file_name.' ';
                    } else {
                        $imagenames = 'Empty';                        
                    }
                }
            } else {
                $imagenames = 'Empty';
            }

            if($row->questions_answers){
                $questions = '';
                foreach($row->questions_answers as $key => $value){
                    $key = str_replace('_',' ',$key);
                    $key = str_replace('-',' ',$key);
                    $key = str_replace(',',' ',$key);
                    $key = str_replace(';',' ',$key);
                    $value = str_replace('_',' ',$value);
                    $value = str_replace('-',' ',$value);
                    $value = str_replace(',',' ',$value);
                    $value = str_replace(';',' ',$value);
                    $questions .= $key.' - '.$value;
                }   
            } else {
                $questions = 'Empty';
            }

            $rowarray = [
                $row->award->name,
                $row->nominee_full_name,
                $row->created_full_name,
                $questions,
                strval($row->votescount),
                $imagenames,
                $filenames,
            ];
            //dump($rowarray);
            fputcsv($handle, $rowarray);
        }
        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return Response::download($filename, 'nominations.csv', $headers);
    }
}
