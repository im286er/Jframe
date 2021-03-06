<?php

return [
    'db' => [
        'dsn' => 'mysql:host=localhost;dbname=Jframe',
        'username' => 'root',
        'password' => 'root'
    ],
    // Default controller when not assigned
    'defaultController' => 'site',
    // Default method when not assigned
    'defaultMethod' => 'index',
    // Initialise some components for the Jframe Framework, with some previous attributes
    'components' => [
        'request' => [
            'class' => 'Jframe\base\Request',
        ],
        'response' => [
            'class' => 'Jframe\base\Response',
        ],
        'view' => [
            'class' => 'Jframe\base\View',
        ],
        'user' => [
            'class' => 'Jframe\base\identity\WebUser',
        ],
        'Reflex' => [
            'class' => 'Jframe\di\Reflex',
        ],
        'session' => [
            'class' => 'Jframe\base\Session',
        ],
    ],
];

