<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class ActivityFeed extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    use \October\Rain\Database\Traits\Sortable;

    protected $dates = ['deleted_at'];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [
        'user' => 'RainLab\User\Models\User'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_activity_feed';
}
