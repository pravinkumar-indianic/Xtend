<?php namespace Codengine\Awardbank\Models;

use Auth;
use Model;

/**
 * Model
 */
class ScorecardResultImport extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'status', 'processing', 'processing_current_offset', 'month', 'skip_errors', 'source_file_path',
        'total', 'processed', 'skipped', 'errors'
    ];

    public $belongsTo = [
        'program' => 'Rainlab\User\Models\Program',
    ];

    protected $jsonable = [
        'errors'
    ];

    public const STATUS_PENDING = 0;
    public const STATUS_IN_PROGRESS = 1;
    public const STATUS_PROCESSED = 2;
    public const STATUS_INVALID_RECORDS = 3;
    public const STATUS_FILE_HAS_ERRORS = 4;
    public const STATUS_NEEDS_REVIEW = 5;

    /*
     * Validation
     */

    public $rules = [
        'program_id' => 'required',
        'status' => 'required',
        'month' => 'required',
        'skip_errors' => 'required',
        'source_file_path' => 'required',
        'total' => 'required',
        'processed' => 'required',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_scorecard_result_imports';

    public static function getStatusLabel($status)
    {
        switch ($status) {
            case self::STATUS_PENDING : return 'Pending';
            case self::STATUS_IN_PROGRESS : return 'In Progress';
            case self::STATUS_PROCESSED : return 'Completed';
            case self::STATUS_INVALID_RECORDS : return 'Failed - Invalid Records';
            case self::STATUS_FILE_HAS_ERRORS : return 'Failed - Invalid File';
            case self::STATUS_NEEDS_REVIEW : return 'Needs to be reviewed by developer.';
        }

        return 'Unknown';
    }
}
