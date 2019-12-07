<?php

return [
    'controllers_dir' => 'Data/Controllers',

    'autoload' => 'Flow/App/autoload.php',

    'ignore' => '/Flowcore',

    'pages' => [
        'test' => 'Data/Config/Test_config.php',
        'some' => 'Data/Config/some_config.php'
    ],

    'default' => 'test'
];