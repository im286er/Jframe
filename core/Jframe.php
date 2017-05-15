<?php

require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'BaseJframe.php');

/**
 * The default class of the Jframe framework of the PHP development
 * Easy use and convienent to extend the features of the defferent situation
 */
class Jframe extends \Jframe\BaseJframe
{
    
}
/**
 * The autoload function which can use to load the class file automatically
 */
spl_autoload_register(['Jframe', 'autoload'], true, true);
