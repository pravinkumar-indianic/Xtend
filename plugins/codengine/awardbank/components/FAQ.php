<?php namespace Codengine\Awardbank\Components;

use Codengine\Awardbank\Traits\OembedRenderer;
use October\Rain\Support\Facades\Input;
use RainLab\User\Models\User;
use Codengine\Awardbank\Models\Program;
use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Auth;
use Redirect;
use stdClass;

class FAQ extends ComponentBase
{
    use OembedRenderer;

    private $program;
    private $user;
    public $image1component;
    public $ckeditorfactorycomponent;

    public function componentDetails()
    {
        return [
            'name' => 'FAQ Editor',
            'description' => 'FAQ Editor',
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
        if ($this->user) {
            $this->program = $this->user->currentProgram;
            $this->image1component = $this->addComponent(
                'Responsiv\Uploader\Components\FileUploader',
                'faq_banner_image',
                [
                    'deferredBinding' => false
                ]
            );
            $this->image1component->bindModel('faq_banner_image', $this->program);

            $this->ckeditorfactorycomponent = $this->addComponent(
                'Codengine\Awardbank\Components\CKEditorFactory',
                'CKEditorFactory',
                [
                    'deferredBinding' => false
                ]
            );
        }
    }

    public function onRun()
    {
        $this->coreLoadSequence();
        if ($this->program) {
            $this->image1component->singleFile = $this->program->faq_banner_image;
        }
    }

    public function onRender()
    {
        if ($this->property('view') == 'page') {
            $faqs = $this->renderFaqs($this->user->currentProgram->faqs);

            return $this->renderPartial('@pageview',[
               'faqs' => $faqs,
               'program' => $this->program
            ]);
        }
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

    public function onFAQList()
    {
        $result['html']['#faq_list'] = $this->renderPartial('@faqtable', [
            'faqs' => $this->getFaqs()
        ]);
        return $result;
    }

    public function onFAQItemAdd()
    {
        $result['html']['#faq_list'] = $this->renderPartial('@faqadd');
        return $result;
    }

    public function onFAQItemEdit()
    {
        if (Input::has('id')) {
            $faqIndex = Input::get('id');
            $faqs = $this->getFaqs();
            if (!empty($faqs)) {
                if(isset($faqs[$faqIndex])) {
                    $result['html']['#faq_list'] = $this->renderPartial('@faqedit', [
                        'index' => $faqIndex,
                        'faq' => $faqs[$faqIndex]
                    ]);

                    return $result;
                }
            }
        }
    }

    private function getFaqs()
    {
        if ($this->user && $this->user->currentProgram) {
            return $this->user->currentProgram->faqs ?? [];
        }

        return [];
    }

    private function renderFaqs($data) {
        $faqs = [];
        if (!empty ($data)) {
            foreach ($data as $faq) {
                $faqs[] = [
                    'question' => $faq['question'],
                    'answer' => self::renderOembedMulti($faq['answer'])
                ];
            }
        }

        return $faqs;
    }
}
