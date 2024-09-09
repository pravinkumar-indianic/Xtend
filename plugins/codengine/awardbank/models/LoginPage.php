<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class LoginPage extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $casts = [
        'ethical_sponsors' => 'array',
        'gold_sponsors' => 'array',
        'silver_sponsors' => 'array',
        'bronze_sponsors' => 'array'
    ];

    protected $fillable = [
        'program_id','youtube_url', 'paragraph1', 'paragraph2',
        'image1_url', 'ethical_sponsors' ,'gold_sponsors', 'silver_sponsors', 'bronze_sponsors'
    ];

    public $belongsTo = [
        'program' => 'Rainlab\User\Models\Program'
    ];

    /*
     * Validation
     */

    public $rules = [

    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_program_login_pages';

    public $attachOne = [
        'infographic' => 'System\Models\file',
    ];

    public $attachMany = [
        'ethical_sponsors' => 'System\Models\File',
        'gold_sponsors' => 'System\Models\File',
        'silver_sponsors' => 'System\Models\File',
        'bronze_sponsors' => 'System\Models\File',
    ];
}
