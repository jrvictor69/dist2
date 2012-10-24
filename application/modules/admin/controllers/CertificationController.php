<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_CertificationController extends App_Controller_Action {

	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
	public function init() {
		parent::init();
	}

	/**
	 * @access public
	 */
	public function indexAction() {
		$a = array(1, 2, 3);
		var_dump($a);

		$range = range(1, 10);

		echo "<pre>";
		var_dump($range);
		echo "</pre>";

		$slite = array_slice($range, 2, 6);

		echo "<pre>";
		var_dump($slite);
		echo "</pre>";
	}
}