<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Auth;
use Codengine\Awardbank\Models\Post;
use Event;
use Config;
use October\Rain\Support\Facades\Input;
use October\Rain\Exception\AjaxException;

class ProgramSettings extends ComponentBase
{
    private $user;
    private $program;
    private $manager = false;
    private $dashboardsettings;
    private $loginpage;
    private $faqs;
    private $navoptions = [];
    private $dashboardsettingscomponent;
    private $loginpagecomponent;
    private $loginpageform;
    private $rewardscomponent;
    private $socialfeedsettingscomponent;
    private $faqeditorcomponent;
    private $postslistcomponent;
    private $posteditcomponent;
    private $programtoolslistcomponent;
    private $ckeditorfactorycomponent;
    public  $navoption;
    public $html1;
    public $html2;

    public function componentDetails()
    {
        return [
            'name'        => 'Program Settings',
            'description' => 'Program Settings'
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
        if ($this->user && $this->user->currentProgram) {
            $this->program = $this->user->currentProgram;
            $this->manager = $this->user->currentProgram->checkIfManager($this->user);
        }

        $this->setNavOption();

        if ($this->navoption == 'dashboard') {
            $this->dashboardsettingscomponent = $this->addComponent(
                'Codengine\Awardbank\Components\DashboardSettings',
                'dashboardSettings',
                [
                    'deferredBinding' => false
                ]
            );
        }

        if ($this->navoption == 'loginpage') {
            $this->loginpagecomponent = $this->addComponent(
                'Codengine\Awardbank\Components\LoginPage',
                'LoginPage',
                [
                    'deferredBinding' => false
                ]
            );
        }

        if ($this->navoption == 'cms-rewards') {
            $this->rewardscomponent = $this->addComponent(
                'Codengine\Awardbank\Components\RewardsSettings',
                'RewardsSettings',
                [
                    'deferredBinding' => false
                ]
            );
        }

        if ($this->navoption == 'socialfeed') {
            $this->socialfeedsettingscomponent = $this->addComponent(
                'Codengine\Awardbank\Components\SocialFeedSettings',
                'SocialFeedSettings',
                [
                    'deferredBinding' => false
                ]
            );
        }

        if ($this->navoption == 'cms-faqs') {
            $this->faqeditorcomponent = $this->addComponent(
                'Codengine\Awardbank\Components\FAQ',
                'FAQ',
                [
                    'deferredBinding' => false
                ]
            );
        }

        if ($this->navoption == 'cms-posts') {
            $this->postslistcomponent = $this->addComponent(
                'Codengine\Awardbank\Components\PostsList',
                'PostsList',
                [
                    'deferredBinding' => false
                ]
            );
        }

        if ($this->navoption == 'cms-create-post') {
            $this->posteditcomponent = $this->addComponent(
                'Codengine\Awardbank\Components\PostEdit',
                'PostEdit',
                [
                    'deferredBinding' => false
                ]
            );
        }

        if ($this->navoption == 'cms-content') {
            $this->programtoolslistcomponent = $this->addComponent(
                'Codengine\Awardbank\Components\ProgramToolsList',
                'ProgramToolsList',
                [
                    'deferredBinding' => false
                ]
            );
        }

        if ($this->navoption == 'cms-create-content') {
            $this->posteditcomponent = $this->addComponent(
                'Codengine\Awardbank\Components\PostEdit',
                'PostEdit',
                [
                    'deferredBinding' => false
                ]
            );
        }

        $this->ckeditorfactorycomponent = $this->addComponent(
            'Codengine\Awardbank\Components\CKEditorFactory',
            'CKEditorFactory',
            [
                'deferredBinding' => false
            ]
        );
    }

    public function onRun()
    {
        $this->addJs('/plugins/codengine/awardbank/assets/js/ProgramSettings011221.js');
        $this->coreLoadSequence();
    }

    public function coreLoadSequence()
    {
        $this->setNavOptions($this->navoption);
        if($this->navoption == 'dashboard'){
            $this->getDashboardSettings();
        }
        if($this->navoption == 'loginpage'){
            $this->getLoginPage();
        }
        if($this->navoption == 'cms-faqs'){
            $this->faqs = $this->getFaqs();
        }
        $this->generateHtml();
    }

    private function setNavOption()
    {
        if ($this->navoption == null) {
            $this->navoption = post('navoption') ?? ($this->param('navoption') ?? 'dashboard');
        }

        if ($this->navoption == 'default') {
            $this->navoption = 'dashboard';
        }
    }

    public function setNavOptions($navoption)
    {
        $this->navoptions = [
            'dashboard' => 'Dashboard',
            'loginpage' => 'Login Page',
            'socialfeed' => 'Social Feed',
            'cms' => 'Content Management',
            'cms-posts' => 'Posts',
            'cms-create-post' => 'Create Post',
            'cms-content' => 'Content',
            'cms-create-content' => 'Create Content',
            'cms-faqs' => 'FAQs',
            'cms-rewards' => 'Rewards',
            'cms-criteria' => 'Criteria',
            'cms-sponsors' => 'Sponsors',
            'cms-contactus' => 'Contact Us',
        ];

        if (array_key_exists($navoption, $this->navoptions)) {
            $this->navoption = $navoption;
        } else {
            $this->navoption = 'dashboard';
        }
    }

    private function getDashboardSettings()
    {
        if ($this->user && $this->user->currentProgram) {
            $this->dashboardsettings = \Codengine\Awardbank\Models\DashboardSettings::where(
                'program_id', '=', $this->user->currentProgram->id
            )->first();
        }
    }

    private function getLoginPage()
    {
        if ($this->user && $this->user->currentProgram) {
            $this->loginpage = \Codengine\Awardbank\Models\LoginPage::where(
                'program_id', '=', $this->user->currentProgram->id
            )->first();
            $this->loginpageform = $this->renderPartial('@loginpageform', [
                'login_page' => $this->loginpage
            ]);
        }
    }

    private function getFaqs()
    {
        if ($this->user && $this->user->currentProgram) {
            return $this->user->currentProgram->faqs ?? [];
        }

        return [];
    }

    public function generateHtml()
    {
        $this->html1 = $this->renderPartial('@programsettingsnav',
            [
                'navoption' => $this->navoption,
                'user' => $this->user,
                'program' => $this->program,
                'is_cms_page' => stripos($this->navoption, 'cms') !== false,
            ]
        );
        $this->html2 = $this->renderPartial('@' . $this->navoption,
            [
                'dashboardsettings' => $this->dashboardsettings,
                'loginpage' => $this->loginpage,
                'loginpageform' => $this->loginpageform,
                'faqs' => $this->faqs
            ]
        );

        $this->page['manager'] = $this->manager;
    }

    public function onUpdateTab()
    {
        if ($this->testPost(post('navoption')) == true){
            $this->navoption = post('navoption');
        }
        $this->pageCycle();
        $result['fileuploaderrun'] = 1;
        $result['html']['#html1target'] = $this->html1;
        $result['html']['#html2target'] = $this->html2;
        return $result;
    }

    public function testPost($input)
    {
        if($input != '' && !empty($input)){
            return true;
        } else {
            return false;
        }
    }

    public function onDashboardSettingsUpdate()
    {
        try {
            $this->getDashboardSettings();

            if (!$this->dashboardsettings) {
                $this->dashboardsettings = new \Codengine\Awardbank\Models\DashboardSettings();
                $this->dashboardsettings->program_id = $this->program->id;
            }

            $this->dashboardsettings->template = post('template');
            $this->dashboardsettings->announcement = post('announcement');
            $this->dashboardsettings->announcement_content = post('announcement_content');
            $this->dashboardsettings->save();

            $result['updatesucess'] = "Dashboard updated.";
            return $result;
        } catch (\Exception $ex) {
            $result['manualerror'] = $ex->getMessage();
            return $result;
        }
    }

    public function onLoginPageUpdate()
    {
        try {
            if ($this->validateInputs(post())) {
                $this->getLoginPage();
                $this->loginpage->template = post('template');
                $this->loginpage->video_source = post('video_source');
                $this->loginpage->video_id = post('video_id');
                $this->loginpage->paragraph1 = post('paragraph1');
                $this->loginpage->paragraph2 = post('paragraph2');
                $this->loginpage->update();
            }

            $result['updatesucess'] = "Login Page updated.";
            return $result;
        } catch (\Exception $ex) {
            $result['manualerror'] = $ex->getMessage();
            return $result;
        }
    }

    private function validateInputs($data) {
        return true;
    }

    public function onExportSocialFeedReport()
    {
        try {
            if (Input::has('created_at_start') && Input::has('created_at_end')) {
                if (empty(Input::get('created_at_start')) || empty(Input::get('created_at_end'))) {
                    $result['manualerror'] = 'Invalid date range. Please specify start & end dates.';
                    return $result;
                } else {
                    $type = 'posts';
                    if (Input::has('type')) {
                        $type = Input::get('type');
                    }

                    $created_at_start = date('Y-m-d 00:00:00', strtotime(Input::get('created_at_start')));
                    $created_at_end = date('Y-m-d 23:59:59', strtotime(Input::get('created_at_end')));

                    switch ($type) {
                        case 'hashtags' : return SocialFeed::onExportSocialFeedHashTagsReport(
                            $this->program->id,
                            $created_at_start,
                            $created_at_end
                        );
                        case 'media' : return SocialFeed::onExportSocialFeedMediaReport(
                            $this->program->id,
                            $created_at_start,
                            $created_at_end
                        );
                        case 'posts' : return SocialFeed::onExportSocialFeedReport(
                            $this->program->id,
                            $created_at_start,
                            $created_at_end
                        );
                    }
                }
            }
        } catch (\Exception $ex) {
            $result['manualerror'] = $ex->getMessage();
            return $result;
        }
    }

    public function onFAQItemSave()
    {
        try {
            if (Input::has('id') && Input::has('question') && Input::has('answer')) {
                $faqs = $this->getFaqs();
                if (!isset($faqs[Input::get('id')]) && Input::get('id') != 'new') {
                    $result['manualerror'] = 'Invalid FAQ Id. Please refresh the page and try again.';
                    return $result;
                }

                if (empty(Input::get('question')) || empty(Input::get('answer'))) {
                    $result['manualerror'] = 'Both Question & Answer must not be empty.';
                    return $result;
                } else {
                    if (Input::get('id') == 'new') {
                        $faqs[] = [
                            'question' => Input::get('question'),
                            'answer' => Input::get('answer'),
                        ];
                    } else {
                        $faqs[Input::get('id')]['question'] = Input::get('question');
                        $faqs[Input::get('id')]['answer'] = Input::get('answer');
                    }

                    $this->updateProgramFAQs($faqs);

                    $result['updatesucess'] = 'FAQ has been successfully saved.';
                    $result['html']['#faq_list'] = $this->renderPartial('FAQ::faqtable', [
                        'faqs' => $this->getFaqs()
                    ]);
                    return $result;
                }
            }
        } catch (\Exception $ex) {
            $result['manualerror'] = $ex->getMessage();
            return $result;
        }
    }

    public function onFAQItemDelete()
    {
        try {
            if (Input::has('id')) {
                $faqs = $this->getFaqs();
                if (!isset($faqs[Input::get('id')])) {
                    $result['manualerror'] = 'Invalid FAQ Id. Please refresh the page and try again.';
                    return $result;
                }

                unset($faqs[Input::get('id')]);
                //Reset indexes
                $faqs = array_values($faqs);

                $this->updateProgramFAQs($faqs);

                $result['html']['#faq_list'] = $this->renderPartial('FAQ::faqtable', [
                    'faqs' => $this->getFaqs()
                ]);
                return $result;

            }
        } catch (\Exception $ex) {
            $result['manualerror'] = $ex->getMessage();
            return $result;
        }
    }

    private function updateProgramFAQs($faqs)
    {
        $this->user->currentProgram->faqs = $faqs;
        $this->user->currentProgram->save();
    }
}
