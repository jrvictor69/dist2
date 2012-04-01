<?php
/**
 * PHPunit for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class CategoryControllerTest extends ControllerTestCase {
	
	/**
	 * 
	 * @var int
	 */
	private $rowCount;
	
	public function setUp() {
		parent::setUp();
		$this->urlCreate = 'admin/country/index/type/organization';		
		$this->rowCount = $this->getConnection()->getRowCount('tblCategory', 'state = 1');
	}
	
	public function getDataSet() {
		$filename = TEST_DATA_APP_PATH . '/admin/Category/initial.xml';
		return $this->createXmlDataSet($filename);
	}
}