<?php

return [
    'enable' => true,
    'default' => 'files',
    'drivers' => [
        'files' => [
            'uniqueId' => 'my-private-app'
        ],
        'redis' => [
            'uniqueId' => 'my-private-app',
            'host' => 'localhost',
            'port' => 6379,
//            'auth' => '',
            'persistent' => false,
            'lifetime' => 3600,
            'prefix' => 'my_'
        ],
        'memcache' => [
            'uniqueId' => 'my-private-app',
            'host' => '127.0.0.1',
            'port' => 11211,
            'persistent' => true,
            'lifetime' => 3600,
            'prefix' => 'my_'
        ],
        'memcached' => [
            'adapter' => 'Libmemcached',
            'servers' => array(
                array('host' => 'localhost', 'port' => 11211, 'weight' => 1),
            ),
            'client' => array(
                Memcached::OPT_HASH => Memcached::HASH_MD5,
                Memcached::OPT_PREFIX_KEY => 'prefix.',
            ),
            'lifetime' => 3600,
            'prefix' => 'my_'
        ]
    ],
];