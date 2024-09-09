<?php namespace Codengine\Awardbank\Components;

use Cms\Classes\ComponentBase;

class PreventIEUser extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'PreventIEUser Component',
            'description' => 'Component to prevent user use IE browser'
        ];
    }

    public function defineProperties()
    {
        return [];
    }
}
