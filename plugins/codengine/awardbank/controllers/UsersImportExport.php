<?php namespace Codengine\Awardbank\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

class UsersImportExport extends Controller
{
    public $implement = [
        'Backend.Behaviors.ImportExportController'
    ];

    public $importExportConfig = 'config_import_export.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('RainLab.User', 'user', $this->action);
    }

}
