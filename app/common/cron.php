<?php
return array(
    'enable' => false,
    'cron' => array(
        'example' => array(
            'expression' => '* * * * *',
            'class' => 'Library\XXXXHelper',
            'method' => 'doSomething'
        ),
    )
);
