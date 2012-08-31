<?php

/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_UnityClubController extends App_Controller_Action {

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
	 * This action shows a paginated list of pictures
	 * @access public
	 */
	public function readAction() {
		$formFilter = new Admin_Form_SearchFilter();
		$formFilter->getElement('nameFilter')->setLabel(_("Name Unity Club"));
		$this->view->formFilter = $formFilter;
	}

	/**
	 *
	 * This action shows a form in create mode
	 * @access public
	 */
	public function createAction() {
		$this->_helper->layout()->disableLayout();
	}

	/**
	 *
	 * Returns an associative array where:
	 * field: title of the table field
	 * filter: value to match
	 * operator: the sql operator.
	 * @param array $filterParams
	 * @return array(field, filter, operator)
	 */
	private function getFilters($filterParams) {
		$filters = array ();
		if (empty($filterParams)) {
			return $filters;
		}

		if (!empty($filterParams['title'])) {
			$filters[] = array('field' => 'name', 'filter' => '%'.$filterParams['title'].'%', 'operator' => 'LIKE');
		}

		return $filters;
	}

	/**
	 * Returns all club pathfinders
	 * @return array
	 */
	public function getClubPathfinders() {
		$clubRepo = $this->_entityManager->getRepository('Model\ClubPathfinder');
		$clubs = $clubRepo->findAll();

		$clubPathfinderArray = array();
		foreach ($clubs as $club) {
			$clubPathfinderArray[$club->getId()] = $club->getName();
		}

		return $clubPathfinderArray;
	}
}