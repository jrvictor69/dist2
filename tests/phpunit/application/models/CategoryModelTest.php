<?php
/**
 * PHPunit Model for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class CategoryModelTest extends ControllerTestCase {
	
	public function getDataSet() {
		$filename = TEST_DATA_APP_PATH . '/Category/initial.xml';
		return $this->createXmlDataSet($filename);
	}
}