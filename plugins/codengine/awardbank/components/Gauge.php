<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Storage;
use Config;

class Gauge extends ComponentBase
{

    /** MODELS **/

    public function componentDetails()
    {
        return [
            'name' => 'Gauge',
            'description' => 'Creates A MultiPurpose Gauge',
        ];
    }

    public function defineProperties()
    {
        return [
            'label' => [
                'title' => 'Wrap Component In The Container',
                'type'=> 'string',
                'default' => false,
            ],

        ];
    }


    public function init()
    {

        $this->addJs('/plugins/codengine/awardbank/assets/js/gauge.min.js');
        //$this->addJs('/themes/xtend/assets/vendor/chartjs/Chart.js');

    }

    public function onRun()
    {

    }

}
