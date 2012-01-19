<?php
define('OMP_ENV', 'development');

/**
 * Zend Framework bootstrap loader.
 *  - Set up include paths
 *  - Set up Autoloader
 *  - Load configuration from JSON file
 */

//Zend Framework is installed at this location by the zend-framework package.
set_include_path(get_include_path() .
                 PATH_SEPARATOR .
                 '/usr/share/php/libzend-framework-php/' .
                 PATH_SEPARATOR .
                 dirname(__FILE__) . '/lib/');

//Load the Zend's Autoloader, let it konw about our namespace
require_once('Zend/Loader/Autoloader.php');
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('OMP_');

//Setup Zend Registry from JSON config file
Zend_Registry::set('config',
                   new Zend_Config_Json('config.json', OMP_ENV));

