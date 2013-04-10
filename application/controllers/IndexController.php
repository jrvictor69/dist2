<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class IndexController extends App_Controller_Action {

	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
	public function init() {
		parent::init();
	}

	public function indexAction() {
	    $this->view->navigation()->getContainer()->findOneBy('id', 'home')->setActive(TRUE);

	    $pictureRepo = $this->_entityManager->getRepository('Model\Picture');
	    $pictures = $pictureRepo->findAll();

	    $this->view->pictures = $pictures;
	}

	public function homeAction() {
		$this->view->navigation()->getContainer()->findOneBy('id', 'home')->setActive(TRUE);

		$pictureRepo = $this->_entityManager->getRepository('Model\Picture');
		$pictures = $pictureRepo->findAll();

		$this->view->pictures = $pictures;
	}

	public function aboutAction() {

	}

	public function contactAction() {

	}
}