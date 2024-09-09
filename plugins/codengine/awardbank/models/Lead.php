<?php namespace Codengine\Awardbank\Models;

use Model;
use Event;

/**
 * Model
 */
class Lead extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_leads';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public function afterCreate()
    {
        $toemails = ['hq@evtmarketing.com.au'];
        $ccemails = ['matthew@codengine.com.au'];
        $vars = [
            'title' => 'New Lead Created',
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
        $template = 'xtend-new-lead-xtend-2-0';
        Event::fire('xtend.sendEmail',[$toemails,$ccemails,$vars, $template]);
    }
}
