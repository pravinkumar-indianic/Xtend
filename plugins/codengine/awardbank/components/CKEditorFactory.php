<?php namespace Codengine\Awardbank\Components;

use RainLab\User\Models\User;
use Codengine\Awardbank\Models\Program;
use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Auth;
use Redirect;
use stdClass;

class CKEditorFactory extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name' => 'CKEditor 5',
            'description' => 'CKEditor 5 Init Function',
        ];
    }

    public function defineProperties()
    {
        return [

        ];
    }

    public function init()
    {

    }

    public function onRun()
    {

    }

    public function onRender()
    {
        $this->page['licenceKey'] = 'EHQXY7XVU3QWNWGCY';
        $this->page['tokenUrl'] = '/ckeditor/token-endpoint';
        $this->page['uploadUrl'] = 'https://86738.cke-cs.com/easyimage/upload/';
        $this->page['elementIds'] = $this->property('elementIds');
        $this->page['removePlugins'] = $this->property('removePlugins');
    }

    /**
    Reusable function call the core sequence of functions to load and render the Cart partial html
     **/

    public function coreLoadSequence()
    {
        $this->generateHtml();
    }

    public function generateHtml()
    {

    }
}
