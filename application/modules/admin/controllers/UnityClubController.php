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

		$club = $this->_entityManager->find('Model\ClubPathfinder',1);
		$unity = new Model\UnityClub();


		$unity->setName("second unity new")->setDescription("description")->setClub($club)->setMotto("motto")->setCreated(new \DateTime('now'));

		$this->_entityManager->persist($unity);
		$this->_entityManager->flush();

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
	 * Outputs an XHR response containing all entries in directives.
	 * This action serves as a datasource for the read/index view
	 * @xhrParam int filter_name
	 * @xhrParam int iDisplayStart
	 * @xhrParam int iDisplayLength
	 */
	public function readItemsAction() {
		$sortCol = $this->_getParam('iSortCol_0', 1);
		$sortDirection = $this->_getParam('sSortDir_0', 'asc');

		$filterParams['name'] = $this->_getParam('filter_name', NULL);
		$filters = $this->getFilters($filterParams);

		$start = $this->_getParam('iDisplayStart', 0);
		$limit = $this->_getParam('iDisplayLength', 10);
		$page = ($start + $limit) / $limit;

		$unityClubRepo = $this->_entityManager->getRepository('Model\UnityClub');
		$unitiesClub = $unityClubRepo->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $unityClubRepo->getTotalCount($filters);

		$posRecord = $start+1;
		$data = array();
		foreach ($unitiesClub as $unityClub) {
			$changed = $unityClub->getChanged();
			if ($changed != NULL) {
				$changed = $changed->format('d.m.Y');
			}

			$row = array();
			$row[] = $unityClub->getId();
			$row[] = $unityClub->getName();
			$row[] = $unityClub->getMotto();
			$row[] = $unityClub->getDescription();
			$row[] = $unityClub->getClub()->getName();
			$row[] = $unityClub->getCreated()->format('d.m.Y');
			$row[] = $changed;
			$row[] = '[]';
			$data[] = $row;
			$posRecord++;
		}
		// response
		$this->stdResponse->iTotalRecords = $total;
		$this->stdResponse->iTotalDisplayRecords = $total;
		$this->stdResponse->aaData = $data;
		$this->_helper->json($this->stdResponse);
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