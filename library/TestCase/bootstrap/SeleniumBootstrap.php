<?php
require_once __DIR__.'/CommonBootstrap.php';
require_once dirname(__DIR__).'/SeleniumTestCase.php';

define('SELENIUM_BROWSER_URL'	, $GLOBALS['SELENIUM_BROWSER_URL']);
define('SELENIUM_BROWSER'		, $GLOBALS['SELENIUM_BROWSER']);
define('SELENIUM_NAME'			, $GLOBALS['SELENIUM_NAME']);
define('SELENIUM_HOST'			, $GLOBALS['SELENIUM_HOST']);
define('SELENIUM_PORT'			, (int)$GLOBALS['SELENIUM_PORT']);
define('SELENIUM_TIMEOUT'		, (int)$GLOBALS['SELENIUM_TIMEOUT']);
