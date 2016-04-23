<?php
return [
    'debug' => false,
    'timezone' => 'UTC',
    'language' => [
        'locale' => 'zh_CN',
        'use_browser' => false,
    ],
    'url' => 'http://localhost',
    'class_aliases' => [
        'Config' => 'Common\Config',
        'Log' => 'Common\Log',
        'Router' => 'Common\Router',
        'User' => 'Common\Model\User',
    ],
    'log' => [
        'adapter' => 'file',
        'file' => 'phalcon.log',
    ],
];
