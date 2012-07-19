<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_DirectiveController extends App_Controller_Action {

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
	 * This action shows a paginated list of directives
	 */
	public function readAction() {
		$formFilter = new Admin_Form_SearchFilter();
		$formFilter->getElement('nameFilter')->setLabel(_("Firstname Directive"));
		$this->view->formFilter = $formFilter;
	}

	/**
	 *
	 * This action shows a form in create mode
	 * @access public
	 */
	public function createAction() {
		$this->_helper->layout()->disableLayout();

		$form = new Admin_Form_Directive();
		$form->getElement('sex')->setMultiOptions($this->getGenders());
		$form->getElement('club')->setMultiOptions($this->getClubPathfinders());
		$form->getElement('position')->setMultiOptions($this->getPositions());

		$this->view->form = $form;
	}

	/**
	 *
	 * Creates a new Directive
	 * @access public
	 */
	public function createSaveAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$form = new Admin_Form_Directive();
		$form->getElement('sex')->setMultiOptions($this->getGenders());
		$form->getElement('club')->setMultiOptions($this->getClubPathfinders());
		$form->getElement('position')->setMultiOptions($this->getPositions());

		$formData = $this->getRequest()->getPost();
		if ($form->isValid($formData)) {
			$club = $this->_entityManager->find('Model\ClubPathfinder', (int)$formData['club']);
			$position = $this->_entityManager->find('Model\Position', (int)$formData['position']);
			$sex = (int)$formData['sex'];

			$directive = new Model\Directive();
			$directive->setIdentityCard(3)
					->setFirstName($formData['firstName'])
					->setLastName($formData['lastName'])
					->setEmail($formData['email'])
					->setPhone($formData['phone'])
					->setPhonemobil($formData['phonemobil'])
					->setSex($sex)
					->setClub($club)
					->setCreated(new \DateTime('now'));

			$this->_entityManager->persist($directive);
			$this->_entityManager->flush();

			$this->stdResponse->success = TRUE;
			$this->stdResponse->message = _("Directive saved");

		} else {
			$this->stdResponse->success = FALSE;
			$this->stdResponse->messageArray = $form->getMessages();
			$this->stdResponse->message = _("The form contains error and is not saved");
		}
		// sends response to client
		$this->_helper->json($this->stdResponse);
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

		$directiveRepo = $this->_entityManager->getRepository('Model\Directive');
		$directives = $directiveRepo->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $directiveRepo->getTotalCount($filters);

		$posRecord = $start+1;
		$data = array();
		foreach ($directives as $directive) {
			$changed = $directive->getChanged();
			if ($changed != NULL) {
				$changed = $changed->format('d.m.Y');
			}

			$row = array();
			$row[] = $directive->getId();
			$row[] = $directive->getFirstName();
			$row[] = $directive->getLastName();
			$row[] = $directive->getPhone();
			$row[] = $directive->getCreated()->format('d.m.Y');
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
	 * field: name of the table field
	 * filter: value to match
	 * operator: the sql operator.
	 * @param array $filterParams contains the values selected by the user.
	 * @return array(field, filter, operator)
	 */
	private function getFilters($filterParams) {
		$filters = array ();

		if (empty($filterParams)) {
			return $filters;
		}

		if (!empty($filterParams['name'])) {
			$filters[] = array('field' => 'name', 'filter' => '%'.$filterParams['name'].'%', 'operator' => 'LIKE');
		}

		return $filters;
	}

	/**
	 * Returns the ids and names of all positions
	 * @return array
	 */
	private function getPositions() {
		$positionRepo = $this->_entityManager->getRepository('Model\Position');
		$positions = $positionRepo->findAll();

		$positionArray = array();
		foreach ($positions as $position) {
			$positionArray[$position->getId()] = $position->getName();
		}

		return $positionArray;
	}

	/**
	 * Returns the ids and names of all club pathfinders
	 * @return array
	 */
	private function getClubPathfinders() {
		$clubRepo = $this->_entityManager->getRepository('Model\ClubPathfinder');
		$clubs = $clubRepo->findAll();

		$clubPathfinderArray = array();
		foreach ($clubs as $club) {
			$clubPathfinderArray[$club->getId()] = $club->getName();
		}

		return $clubPathfinderArray;
	}

	/**
	 * Returns the genders of a person
	 * @return array
	 */
	private function getGenders() {
		return array(Model_Person::SEX_MALE => _("Male"), Model_Person::SEX_FEMALE => _("Female"));
	}
}