<?php
/**
 * Selenium for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class CategoryTest extends SeleniumTestCase {
	
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
    	$this->url = "http://dist2/admin/category";
  	}
  	
  	/**
  	 * 
  	 * Test to create a new category
  	 */
	public function testCreate() {
    	$this->open($this->url);
    	$this->waitForPageToLoad();
    	$this->waitForJQueryXhr(5000);
    
    	//Click on add button
   		$this->click("css=#create-category");
		$this->waitForJQueryXhr(5000);
	
		$name = $this->random('tName_', 10);
		$description = $this->random('tDescription_', 10);

		$this->type('name', $name);
		$this->type('description', $description);
		
		//Click on save button popup
		$this->click("css=.ui-button-text");
		$this->waitForJQueryXhr(5000);
  	}
  	
  	/**
  	 * 
  	 * Test to update a category
  	 */
//	public function testUpdate(){
//		$this->open($this->Url);
//    	$this->waitForPageToLoad();
//		$this->waitForJQueryXhr(5000);
//		
//		//Click on link
//		$this->click("xpath=//a[@id='update-category-44']");
//		$this->waitForJQueryXhr(5000);
//		
//		$name = $this->random('tNameCh_', 10);
//		$description = $this->random('tDescriptionCh_', 10);
//
//		$this->type('name', $name);
//		$this->type('description', $description);
//				
//		//Click on save button
//		$this->click("css=.ui-button-text");
//		$this->waitForJQueryXhr(5000);
//	}
	
  	/**
  	 * 
  	 * Generates random the text
  	 * @param string $prefix
  	 * @param int $length
  	 * @param string $chars
  	 */
	public function random($prefix = "", $length = 7, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890') {
		//Length of character list
		$charsLength = (strlen($chars) - 1);

		// Start our string
		$string = '';

		// Generate random string
		for ($i = 1; $i < $length; $i = strlen($string)) {
			// Grab a random character from our list
			$string .= $chars{rand(0, $charsLength)};
		}

		return $prefix . $string;
	}
}