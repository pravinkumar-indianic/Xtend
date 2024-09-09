<?php namespace Codengine\Awardbank\Models;

use Auth;
use Model;

/**
 * Model
 */
class SpinTheWinPrice extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'program_id', 'label', 'name', 'message', 'is_price', 'quantity', 'probability'
    ];

    public $belongsTo = [
        'program' => 'Rainlab\User\Models\Program',
    ];

    /*
     * Validation
     */

    public $rules = [
        'program_id' => 'required',
        'label' => 'required|max:15',
        'name' => 'required|max:255',
        'message' => 'max:255',
        'is_price' => 'required|in:0,1',
        'quantity' => 'required',
        'probability' => 'required|not_in:0',
        'priority' => 'required|not_in:0',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_spin_the_win_prices';

    public $attachOne = [
        'logo' => 'System\Models\file',
    ];
}
