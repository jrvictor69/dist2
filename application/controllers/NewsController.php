<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-t.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class NewsController extends App_Controller_Action {
	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
	public function init() {
		parent::init();
		$response = $this->getResponse();
		$response->insert("sidebar", $this->view->render("sidebar.phtml"));
	}

    public function indexAction() {
		$this->_helper->redirector("ja");
    }
    
	public function jaAction() {
    }
}