<?php
/**
 * Selenium for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class PrivilegeTest extends SeleniumTestCase {
	
	/**
	 * 
	 * This is url the page
	 * @var string
	 */
	private $url;
	
	/**
	 * (non-PHPdoc)
	 * @see SeleniumTestCase::setUp()
	 */
	public function setUp() {    
    	$this->setBrowser("*firefox");
    	$this->setBrowserUrl("http://dist2/");
    	$this->url = "http://dist2/admin/privilege/read/type/security";
  	}
  	
  	/**
  	 * 
  	 * Test to create a new privilege
  	 */
	public function testCreate() {
    	$this->open($this->url);
    	$this->waitForPageToLoad();
    	$this->waitForJQueryXhr(5000);
    
    	//Click on add button
   		$this->click("css=#create-privilege");
		$this->waitForJQueryXhr(5000);
	
		$name = $this->random('tName_', 10);
		$description = $this->random('tDescription_', 20);
		$module = $this->random('tModule_', 10);
		$controller = $this->random('tController_', 10);
		$action = $this->random('tAction_', 10);

		$this->type('name', $name);
		$this->type('description', $description);
		$this->type('module', $module);
		$this->type('controller', $controller);
		$this->type('action', $action);
		
		//Click on save button popup
		$this->click("css=.ui-button-text");
		$this->waitForJQueryXhr(5000);
  	}
  	
  	/**
  	 * 
  	 * Test to update a privilege
  	 */
	public function testUpdate(){
		$this->open($this->url);
    	$this->waitForPageToLoad();
		$this->waitForJQueryXhr(5000);
		
		//Click on link
		$this->click("xpath=//a[@id='update-privilege-10']");
		$this->waitForJQueryXhr(5000);
		
		$name = $this->random('tNameCh_', 10);
		$description = $this->random('tDescriptionCh_', 10);
		$module = $this->random('tModuleCh_', 10);
		$controller = $this->random('tControllerCh_', 10);
		$action = $this->random('tActionCh_', 10);
		
		$this->type('name', $name);
		$this->type('description', $description);
		$this->type('module', $module);
		$this->type('controller', $controller);
		$this->type('action', $action);
						
		//Click on save button
		$this->click("css=.ui-button-text");
		$this->waitForJQueryXhr(5000);
	}
}