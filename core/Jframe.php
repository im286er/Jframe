<?php

/**
 * To require the BaseJframe class to do the Initial job
 */
require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'BaseJframe.php');

/**
 * The default class of the Jframe framework of the PHP development
 * Easy use and convenient to extend the features of the different situation
 */
class Jframe extends \Jframe\BaseJframe
{
    /**
     * Hi everyone reading the Jframe source code.
     * There are some suggestions help you to understand the source code of the
     * Jframe PHP Framework
     *
     * ````
     * ``The system's config file can be divided into several parts as you like
     *
     * ``Each class placed in the core/base directory has important feature
     *
     * ``Some other class can be used if you want to use in you project, please place it
     *  in the vendor directory
     *
     * Wish you love the Jframe PHP framework
     *
     * PHP is the word best popular programing language
     **/
}

/**
 * The autoload function which can use to load the class file automatically
 */
spl_autoload_register(['Jframe', 'autoload'], true, true);
