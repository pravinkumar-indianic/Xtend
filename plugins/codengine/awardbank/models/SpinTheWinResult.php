<?php namespace Codengine\Awardbank\Models;

use Auth;
use Model;

/**
 * Model
 */
class SpinTheWinResult extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'price_id', 'user_id'
    ];

    public $belongsTo = [
        'program' => 'Rainlab\User\Models\Program',
    ];

    /*
     * Validation
     */

    public $rules = [
        'price_id' => 'required',
        'user_id' => 'required',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_spin_the_win_results';
}
