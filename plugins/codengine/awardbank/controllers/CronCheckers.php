<?php namespace Codengine\Awardbank\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Illuminate\Support\Facades\Event;

class CronCheckers extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Codengine.Awardbank', 'CronCheckers');
    }

    public function onRunBilling()
    {
        Event::fire('xtend.runbilling');
    }

    public function onRunTenure()
    {
        Event::fire('xtend.updateAnniversaryFlags', ['new_tenure']);
        Event::fire('xtend.sendTenureEmail');
    }

    public function onRunBirthDay()
    {
        Event::fire('xtend.updateAnniversaryFlags', ['new_birthday']);
    }
}
