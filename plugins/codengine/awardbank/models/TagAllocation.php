<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class TagAllocation extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    /*
     * Validation
     */
    public $rules = [
    ];

    public $belongsTo = [
        'tag' => 'Codengine\Awardbank\Models\Tag'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_p_pt';
}