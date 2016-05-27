<?php

return [
    'enable' => true,
    'default' => 'files',
    'drivers' => [
        'beanstalk' => [
            'host' => '127.0.0.1',
            'port' => 11300
        ],
        'files' => [
            'path' => ROOT_PATH . '/storage/queue',
            'ext' => '.data',
        ],
    ]
];