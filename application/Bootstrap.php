<?php
/**
 * Bootstrap for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
	
	/**
	 * 
	 * @var Zend_View
	 */
	private $view;
	
	/**
	 * 
	 * Init autoload the resources
	 */
	public function _initAutoloadPackage() {
		$autoloader = Zend_Loader_Autoloader::getInstance();		
		$autoloader->registerNamespace('App_');		
	}
	
	/**
	 * 
	 * Init autoload the resources ZendX_JQuery_View_Helper
	 */
	public function _initViewResource() {
		$this->bootstrap('view');
		$this->view = $this->getResource('view');
		
    	$this->view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer(); 
		$viewRenderer->setView($this->view);
		Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
	}
	
	/**
	 * 
	 * Config zend navigation
	 */
	public function _initNavigation() {
		if ($this->view === null) {
		    return;
		}
		
		$config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation/navigation_admin.xml','navigation');
		$navigation = new Zend_Navigation($config);
		Zend_Registry::set('navigation', $navigation);
		$this->view->navigation($navigation); 
	}
	
	/**
	 *
	 * Append scripts file to layout
	 */
	public function _initHeadScriptAndStyle() {
		if ($this->view === null) {
		    return;
		}
		// Style sheets
		$this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/css/jscal2.css");
		$this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/css/zebralist.css");
		$this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/css/screen.css");
		$this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/css/print.css", "print");
		$this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/css/dialog.css");
		$this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/css/input.css");
		$this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/css/infopanel.css");
		$this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/css/menu_new.css");
		$this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/css/tabs_new.css");
		$this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/css/new_form.css");
		$this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/css/navigation.css");
		$this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/css/form2.0.css");
		$this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/css/siteapp.css");
		
		// jquery core
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/lib/jquery/jquery.min.js','text/javascript');
		
		// Jquery ui
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/lib/jquery-ui/jquery-ui.min.js','text/javascript');
	    $this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/js/lib/jquery-ui/jquery-ui-themes/themes/ui-lightness/jquery-ui.css");
	    
	    // Jgrowl plugin jquery
	    $this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/js/lib/jgrowl/jquery.jgrowl.css");
	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/lib/jgrowl/jquery.jgrowl.js','text/javascript');
	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/lib/jgrowl/Alert.js','text/javascript');
	    
	    // Datatables plugin jquery
	    $this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/js/lib/jquery-datatables/css/demo_table.css");
		$this->view->headScript()->appendFile($this->view->baseUrl() . "/js/lib/jquery-datatables/jquery.dataTables.min.js","text/javascript");
		$this->view->headScript()->appendFile($this->view->baseUrl() . "/js/lib/jquery-validate/jquery.validate.min.js","text/javascript");
		
		// Alert Dialogs plugin jquery
		$this->view->headScript()->appendFile($this->view->baseUrl() . "/js/lib/jquery.alerts-1.1/jquery.alerts.js","text/javascript");
		$this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/js/lib/jquery.alerts-1.1/jquery.alerts.css");
		$this->view->headScript()->appendFile($this->view->baseUrl() . "/js/lib/jquery.alerts-1.1/jquery.alerts.js","text/javascript");
	}
	
	/**
	 * 
	 * Register plugins
	 */
	public function _initRegisterPlugin() {
		$frontController = Zend_Controller_Front::getInstance();
		
		// Register plugin acl
        if (!$frontController->hasPlugin('App_Controller_Plugin_Acl')) {
        	$frontController->registerPlugin(new App_Controller_Plugin_Acl(), 1);
        }
         
		// Register plugin for router, enable parameter lang to zend_translate
        if (!$frontController->hasPlugin('App_Controller_Plugin_RouterSetup')) {
        	$frontController->registerPlugin(new App_Controller_Plugin_RouterSetup(), 2);
        }
         
        // Register plugion view setup
		if (!$frontController->hasPlugin('App_Controller_Plugin_ViewSetup')) {
        	$frontController->registerPlugin(new App_Controller_Plugin_ViewSetup(), 3);
        }
	}
	
	/**
	 * 
	 * Init action helpers
	 */
	public function _initActionHelper() {
		$_messenger = new App_Controller_Action_Helper_Messenger();
		Zend_Controller_Action_HelperBroker::addHelper($_messenger);
		Zend_Controller_Action_HelperBroker::addPrefix("App_Controller_Action_Helper");
	}
	
	/**
	 * 
	 * Init Config
	 */
	protected function _initConfig() {
	    $config = new Zend_Config($this->getOptions(), true);
	    Zend_Registry::set('config', $config);
	    return $config;
	}
}