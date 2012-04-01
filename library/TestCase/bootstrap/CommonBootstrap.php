<?php 

putenv('APPLICATION_ENV=testing');

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? 
    	getenv('APPLICATION_ENV') : 'testing'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

defined('TEST_DATA_APP_PATH') 
	|| define('TEST_DATA_APP_PATH', realpath(APPLICATION_PATH . '/../tests/data/application/modules/'));

require_once  dirname(__DIR__). '/DbTestCase.php';