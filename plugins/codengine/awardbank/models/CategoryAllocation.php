<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class CategoryAllocation extends Model
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
        'category' => 'Codengine\Awardbank\Models\Category'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_p_pc';
}