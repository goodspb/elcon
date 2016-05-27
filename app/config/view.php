<?php

return [
    'extension' => '.phtml',
    'path' => ROOT_PATH . '/app/views/',
    'volt' => [
        "compiledPath" => ROOT_PATH . "/storage/templates/",
        "compiledExtension" => ".compiled.php"
    ],
    'functions' => [
        'config' => function ($resolvedArgs, $exprArgs) {
            return '\Config::get(' . $resolvedArgs . ')';
        },
        'session' => function ($resolvedArgs, $exprArgs) {
            return '\Session::get(' . $resolvedArgs . ')';
        },
        'lang' => function ($resolvedArgs, $exprArgs) {
            return '\Lang::get(' . $resolvedArgs . ')';
        },
    ],
];