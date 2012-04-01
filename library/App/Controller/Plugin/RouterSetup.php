<?php 

require_once ('Zend/Controller/Plugin/Abstract.php');

class App_Controller_Plugin_RouterSetup extends Zend_Controller_Plugin_Abstract
{
	/**
	 * @var App_Log
	 */
	private $logger;
	
	/**
	 * Set parameter lang in router
	 * @see Zend_Controller_Plugin_Abstract::routeStartup()
	 */
	public function routeStartup(Zend_Controller_Request_Abstract $request) {
	    $frontController  = Zend_Controller_Front::getInstance();
	    $router = $frontController->getRouter();
	    $locale = Zend_Registry::get('Zend_Locale');
//	    
	    $routeLang = new Zend_Controller_Router_Route(
	        ':lang',
	        array(
	            'lang' => $locale->getLanguage()
	        ),
	        array('lang' => '[a-z]{2}')
	    );	
//	    
//	    // Instantiate default module route
	    $routeDefault = new Zend_Controller_Router_Route_Module(
	        array(),
	        $frontController->getDispatcher(),
	        $frontController->getRequest()
	    );
//	    var_dump($routeDefault); 
//    
//	    // Chain it with language route
    	$routeLangDefault = $routeLang->chain($routeDefault);
		//@victor comment
//	    $router->addRoute('default', $routeLangDefault);
	}
	
	/** 
	 * Read parameter lang for set language
	 * @see Zend_Controller_Plugin_Abstract::routeShutdown()
	 */
	public function routeShutdown(Zend_Controller_Request_Abstract $request) 
	{
		$this->logger = Zend_Registry::get('Zend_Log');
		try {
			$lang = $request->getParam('lang', null);
			$translate = Zend_Registry::get('Zend_Translate');
			
			// Change language if available
	        if ($translate->isAvailable($lang)) {
	            $translate->setLocale($lang);
	        } else {
	            // Otherwise get default language
	            $locale = $translate->getLocale();
	            if ($locale instanceof Zend_Locale) {
	                $lang = $locale->getLanguage();
	            } else {
	                $lang = $locale;
	            }
	        }
	
	        // Set language to global param so that our language route can fetch it nicely.
	        $front = Zend_Controller_Front::getInstance();
	        $router = $front->getRouter();
		} catch (Zend_Exception $e) {
			$this->logger->warn($e->getMessage());
			return;
		}
	}
}