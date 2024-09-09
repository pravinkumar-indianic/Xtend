<?php namespace Codengine\Awardbank\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'PlatformSettings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}