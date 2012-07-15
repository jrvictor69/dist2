<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_PictureTypeController extends App_Controller_Action {

	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
	public function init() {
		parent::init();
	}

	/**
	 * 
	 * In case no action redirects the action read will be executed
	 * @access public
	 */
	public function indexAction() {
		$this->_helper->redirector('read');
	}

	/**
	 * 
	 * This action shows a paginated list of positions
	 * @access public
	 */
	public function readAction() {
		$formFilter = new Admin_Form_SearchFilter();
		$formFilter->getElement('nameFilter')->setLabel(_("Name position"));
		$this->view->formFilter = $formFilter;
		
//		$bases = $this->_entityManager->createQuery("select s from Model\Base s")->getResult();
//		echo "<pre>";
//		var_dump($bases);
//		echo "</pre>";
//		echo "----------------------------";
//		$directives = $this->_entityManager->createQuery("select s from Model\Directive s")->getResult();
//		echo "<pre>";
//		var_dump($directives);
//		echo "</pre>";
//		echo "----------------------------";
//		$guests = $this->_entityManager->createQuery("select s from Model\Guest s")->getResult();
//		echo "<pre>";
//		var_dump($guests);
//		echo "</pre>";
		$person = new Model\Person();
		$person->setIdentityCard(21221)
			->setFirstName("victor")
			->setLastName("Villca")
			->setDateOfBirth(new \DateTime('now'))
			->setCreated(new \DateTime('now'));
//		$person->setIdentityCard(3434)->setFirstName("namam")->setLastName("dsad")->setCreated(new \DateTime('now'));
		$this->_entityManager->persist($person);
		$this->_entityManager->flush();
	}
}