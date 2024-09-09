<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [

        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily', 'cloudwatch'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/system.log'),
            'level' => 'debug'
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/system.log'),
            'level' => 'debug',
            'days' => 14
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'October CMS Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => Monolog\Handler\SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => Monolog\Handler\StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],

        'cloudwatch' => [
            'driver' => 'custom',
            'name' => env('CLOUDWATCH_LOG_NAME', ''),
            'region' => env('CLOUDWATCH_LOG_REGION', ''),
            'credentials' => [
                'key' => env('CLOUDWATCH_LOG_KEY', ''),
                'secret' => env('CLOUDWATCH_LOG_SECRET', '')
            ],
            'stream_name' => env('CLOUDWATCH_LOG_STREAM_NAME', 'laravel_app'),
            'retention' => env('CLOUDWATCH_LOG_RETENTION_DAYS', 14),
            'group_name' => env('CLOUDWATCH_LOG_GROUP_NAME', 'laravel_app'),
            'version' => env('CLOUDWATCH_LOG_VERSION', 'latest'),
            'formatter' => \Monolog\Formatter\JsonFormatter::class,
            'batch_size' => env('CLOUDWATCH_LOG_BATCH_SIZE', 10000),
            'via' => \Pagevamp\Logger::class,
            'level' => 'debug'
        ],

    ],

];
