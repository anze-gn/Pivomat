<?php

require_once("vendor/autoload.php");

class Twig {

    /**
     * Call this method to get singleton
     */
    public static function instance()
    {
        static $instance = false;
        if( $instance === false ) {
            // Late static binding (PHP 5.3+)
            $instance = new Twig_Environment(new Twig_Loader_Filesystem('templates'));
            $instance->addGlobal('STATIC_URL', STATIC_URL);
            $instance->addGlobal('BASE_URL', BASE_URL);
        }

        return $instance;
    }

    /**
     * Make constructor private, so nobody can call "new Class".
     */
    private function __construct() {}

    /**
     * Make clone magic method private, so nobody can clone instance.
     */
    private function __clone() {}

    /**
     * Make sleep magic method private, so nobody can serialize instance.
     */
    private function __sleep() {}

    /**
     * Make wakeup magic method private, so nobody can unserialize instance.
     */
    private function __wakeup() {}

}