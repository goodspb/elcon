<?php

/**
 * 自动往下 fallback
 */
return [
    'beanstalk' => [
        'enable' => true,
        'host' => '127.0.0.1',
        'port' => 11300
    ],

    'file' => [
        'enable' => true,
        'path' => ROOT_PATH . '/storage/queue',
        'ext' => '.data',
    ],
];