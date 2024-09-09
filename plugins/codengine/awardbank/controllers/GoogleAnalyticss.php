<?php

namespace Codengine\Awardbank\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class GoogleAnalyticss extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\ImportExportController',
    ];

    public $listConfig = 'config_list.yaml';
    public $importExportConfig = 'config_import_export.yaml';

    public function __construct()
    {
        parent::__construct();
    }
}
