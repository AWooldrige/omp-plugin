<?php

/**
 * Zend Framework bootstrap loader.
 *
 */

//Zend Framework is installed at this location by the zend-framework package.
set_include_path(get_include_path() .
                 PATH_SEPARATOR .
                 '/usr/share/php/libzend-framework-php/' .
                 PATH_SEPARATOR .
                 dirname(__FILE__) . '/lib/');

echo get_include_path();

require_once('Zend/Loader/Autoloader.php');
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('OMP_');

