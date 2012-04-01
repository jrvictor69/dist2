<?php

/*
 * Extend the locale resource.
 * This resource is working with plugin RouterSetup
 * @package App
 * @subpackage Resource
 */
class App_Resource_Locale extends Zend_Application_Resource_Locale
{
	/**
	 * (non-PHPdoc) This is changed by victor villca
	 * @see Zend_Application_Resource_Locale::init()
	 */
    public function init()
    {
    	$locale = parent::init();
    	
    	// init cache languages
//    	$frontendOptions = array(
//			'lifetime' => 43200,                   // cache lifetime of 30 seconds
//		    'automatic_serialization' => true  // this is the default anyways
//		);
//		$backendOptions = array('cache_dir' => APPLICATION_PATH .  '/../var/tmp/');
//    	$cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
//		Zend_Translate::setCache($cache);
//		
//		// load languages
//		$translate = new Zend_Translate(array(
//			'adapter'=> 'Zend_Translate_Adapter_Gettext',
//			'content' => APPLICATION_PATH .  '/../var/translate/es_ES.mo',
//			'locale'  => 'es'
//		));
//		$translate->addTranslation(array(
//			'adapter'=> 'Zend_Translate_Adapter_Gettext',
//			'content' => APPLICATION_PATH .  '/../var/translate/en_US.mo',
//			'locale'  => 'en'
//		));
//		
//		// delete cache languages
//		//Zend_Translate::clearCache();
//		
//		// key Zend_Locale autoload by Zend_Framework, then we're replacing
//		Zend_Registry::set('Zend_Locale', $locale);
//		Zend_Registry::set('Zend_Translate', $translate);
	    
        return $locale;
    }
}
