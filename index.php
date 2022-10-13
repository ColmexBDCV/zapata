<?php
/**
 * Omeka
 * 
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 * @package Omeka
 */

// Bootstrap the application.
require_once 'bootstrap.php';

if(!defined('PATH_ENV')){
    Zend_Registry::set('pathconf', getenv('PATH_ENV'));
}else{
    Zend_Registry::set('pathconf', '');
}

if(!defined('IP_RESTRICT')){
    Zend_Registry::set('ip_restrict', getenv('IP_RESTRICT'));
}else{
    Zend_Registry::set('ip_restrict', '');
}

// Configure, initialize, and run the application.
$application = new Omeka_Application(APPLICATION_ENV);
$application->getBootstrap()->setOptions(array(
    'resources' => array(
        'theme' => array(
            'basePath' => THEME_DIR,
            'webBasePath' => WEB_THEME
        )
    )
));
$application->initialize()->run();
