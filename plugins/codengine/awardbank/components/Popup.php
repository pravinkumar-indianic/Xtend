<?php namespace Codengine\Awardbank\Components;


use Illuminate\Support\Facades\Session;
use RainLab\User\Models\User;
use Codengine\Awardbank\Models\Program;
use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Auth;
use Redirect;

class Popup extends ComponentBase
{
    public $user;
    public $program;
    public $title;
    public $content;
    public $source;

    public function componentDetails()
    {
        return [
            'name' => 'Login Page',
            'description' => 'Login Page editor',
        ];
    }

    public function defineProperties()
    {
        return [];
    }


    public function init()
    {
        $this->user = Auth::getUser();
        if ($this->user && $this->user->currentProgram) {
            $this->program = $this->user->currentProgram;
        }
    }

    public function onRun()
    {
        $this->page['hasPopup'] = $this->getPopup();
        $this->page['title'] = $this->title;
        $this->page['content'] = $this->content;
        $this->page['source'] = $this->source;
    }

    private function getPopup()
    {
        //Session::forget('popup-component-closed-by-user');
        $displayed_before = Session::get('popup-component-closed-by-user');
        if ($displayed_before) {
            return false;
        }

        if (!empty($this->user->popup_content)) {
            $this->title = $this->sanitizeContentForJs($this->user->popup_title);
            $this->content = $this->sanitizeContentForJs(trim(preg_replace('/\s+/', ' ', $this->user->popup_content)));
            $this->source = 'user';
            return true;
        } elseif (!empty($this->program->popup_content)) {
            $this->title = $this->sanitizeContentForJs($this->program->popup_title);
            $this->content = $this->sanitizeContentForJs(trim(preg_replace('/\s+/', ' ', $this->program->popup_content)));
            $this->source = 'program';
            return true;
        } else {
            return false;
        }
    }

    private function sanitizeContentForJs($content) {
        return str_replace(["'"], ["&#39"], $content);
    }

    public function onPopupClosed()
    {
        Session::put('popup-component-closed-by-user', post('source'));
        return true;
    }
}
