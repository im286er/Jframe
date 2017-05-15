<?php

define('ROOT', dirname(__DIR__));

require(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Jframe.php');
require(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'bootstrap.php');

$config = require(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'web.php');


(new \Jframe\Application())->run($config);
