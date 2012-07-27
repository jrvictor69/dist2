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
		$form->setAction($this->_helper->url('save'));
		$this->view->form = $form;
	}

	/**
	 *
	 * Creates a new Directive
	 * @access public
	 */
// 	public function createSaveAction() {
// 		$this->_helper->viewRenderer->setNoRender(TRUE);

// 		$form = new Admin_Form_Directive();
// 		$form->getElement('sex')->setMultiOptions($this->getGenders());
// 		$form->getElement('club')->setMultiOptions($this->getClubPathfinders());
// 		$form->getElement('position')->setMultiOptions($this->getPositions());

// 		$formData = $this->getRequest()->getPost();
// 		if ($form->isValid($formData)) {
// 			$club = $this->_entityManager->find('Model\ClubPathfinder', (int)$formData['club']);
// 			$position = $this->_entityManager->find('Model\Position', (int)$formData['position']);

// 			$directive = new Model\Directive();
// 			$directive->setIdentityCard(3)
// 					->setFirstName($formData['firstName'])
// 					->setLastName($formData['lastName'])
// 					->setEmail($formData['email'])
// 					->setPhone($formData['phone'])
// 					->setPhonemobil($formData['phonemobil'])
// 					->setSex((int)$formData['sex'])
// 					->setClub($club)
// 					->setPosition($position)
// 					->setCreated(new \DateTime('now'));

// 			$this->_entityManager->persist($directive);
// 			$this->_entityManager->flush();

// 			$this->stdResponse->success = TRUE;
// 			$this->stdResponse->message = _("Directive saved");

// 		} else {
// 			$this->stdResponse->success = FALSE;
// 			$this->stdResponse->messageArray = $form->getMessages();
// 			$this->stdResponse->message = _("The form contains error and is not saved");
// 		}
// 		// sends response to client
// 		$this->_helper->json($this->stdResponse);
// 	}

	/**
	 *
	 * Creates a new Directive
	 * @access public
	 */
	public function saveAction() {
		if ($this->_request->isPost()) {
			$form = new Admin_Form_Directive();
			$form->getElement('sex')->setMultiOptions($this->getGenders());
			$form->getElement('club')->setMultiOptions($this->getClubPathfinders());
			$form->getElement('position')->setMultiOptions($this->getPositions());

			$formData = $this->getRequest()->getPost();

			if ($form->isValid($formData)) {
				$fh = fopen($_FILES['file']['tmp_name'], 'r');
				$binary = fread($fh, filesize($_FILES['file']['tmp_name']));
				fclose($fh);

				$mimeType = $_FILES['file']['type'];
				$fileName = $_FILES['file']['name'];

				$dataVaultMapper = new Model_DataVaultMapper();
				$dataVault = new Model_DataVault();
				$dataVault->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);
				$dataVaultMapper->save($dataVault);

				$club = $this->_entityManager->find('Model\ClubPathfinder', (int)$formData['club']);
				$position = $this->_entityManager->find('Model\Position', (int)$formData['position']);

				$directive = new Model\Directive();
				$directive->setIdentityCard(3)
					->setFirstName($formData['firstName'])
					->setLastName($formData['lastName'])
					->setEmail($formData['email'])
					->setPhone($formData['phone'])
					->setPhonemobil($formData['phonemobil'])
					->setSex((int)$formData['sex'])
					->setClub($club)
					->setPosition($position)
					->setCreated(new \DateTime('now'))
					->setProfilePictureId($dataVault->getId());

				$this->_entityManager->persist($directive);
				$this->_entityManager->flush();

				$this->_helper->flashMessenger(array('success' => _("Archive saved")));
				$this->_helper->redirector('read', 'Directive', 'admin', array('type'=>'pathfinder'));
			}
		} else {
			$this->_helper->redirector('read', 'Directive', 'admin', array('type'=>'pathfinder'));
		}
	}

	/**
	 *
	 * This action shows the form in update mode for Directive.
	 * @access public
	 */
	public function updateAction() {
		$this->_helper->layout()->disableLayout();

		$form = new Admin_Form_Directive();
		$form->getElement('sex')->setMultiOptions($this->getGenders());
		$form->getElement('club')->setMultiOptions($this->getClubPathfinders());
		$form->getElement('position')->setMultiOptions($this->getPositions());

		$id = $this->_getParam('id', 0);
		$directive = $this->_entityManager->find('Model\Directive', $id);
		if ($directive != NULL) {
			$form->getElement('firstName')->setValue($directive->getFirstName());
			$form->getElement('lastName')->setValue($directive->getLastName());
			$form->getElement('sex')->setValue($directive->getSex());
			$form->getElement('email')->setValue($directive->getEmail());
			$form->getElement('phonemobil')->setValue($directive->getPhonemobil());
			$form->getElement('phone')->setValue($directive->getPhone());
			$form->getElement('club')->setValue($directive->getClub()->getId());
			$form->getElement('position')->setValue($directive->getPosition()->getId());
		} else {
			$this->stdResponse->success = FALSE;
			$this->stdResponse->message = _("The requested record was not found.");
			$this->_helper->json($this->stdResponse);
		}

		$this->view->form = $form;
	}

	/**
	 *
	 * Updates a Directive
	 * @access public
	 * 1) Gets the record to edit
	 * 2) Validates the record was no deleted
	 * 3) Validates the existance of another Directive with the same name.
	 * 4) Saves the changes.
	 */
	public function updateSaveAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$form = new Admin_Form_Directive();
		$form->getElement('sex')->setMultiOptions($this->getGenders());
		$form->getElement('club')->setMultiOptions($this->getClubPathfinders());
		$form->getElement('position')->setMultiOptions($this->getPositions());

		$formData = $this->getRequest()->getPost();
		if ($form->isValid($formData)) {
			$id = $this->_getParam('id', 0);
			$directive = $this->_entityManager->find('Model\Directive', $id);
			if ($directive != NULL) {
				$club = $this->_entityManager->find('Model\ClubPathfinder', (int)$formData['club']);
				$position = $this->_entityManager->find('Model\Position', (int)$formData['position']);

				$directive->setIdentityCard(3)
						->setFirstName($formData['firstName'])
						->setLastName($formData['lastName'])
						->setEmail($formData['email'])
						->setPhone($formData['phone'])
						->setPhonemobil($formData['phonemobil'])
						->setSex((int)$formData['sex'])
						->setClub($club)
						->setPosition($position)
						->setChanged(new \DateTime('now'));

				$this->_entityManager->persist($directive);
				$this->_entityManager->flush();

				$this->stdResponse->success = TRUE;
				$this->stdResponse->message = _("Directive updated");
			} else {
				$this->stdResponse->success = FALSE;
				$this->stdResponse->message = _("The Directive does not exists");
			}
		} else {
			$this->stdResponse->success = FALSE;
			$this->stdResponse->messageArray = $form->getMessages();
			$this->stdResponse->message = _("The form contains error and is not updated");
		}
		// sends response to client
		$this->_helper->json($this->stdResponse);
	}

	/**
	 *
	 * Deletes directives
	 * @access public
	 * @internal
	 * 1) Gets the model directive
	 * 2) Validates the existance of dependencies
	 * 3) Changes the state field or records to delete
	 */
	public function deleteAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$itemIds = $this->_getParam('itemIds', array());
		if (!empty($itemIds) ) {
			$removeCount = 0;
			foreach ($itemIds as $id) {
				$directive = $this->_entityManager->find('Model\Directive', $id);
				$directive->setState(FALSE);

				$this->_entityManager->persist($directive);
				$this->_entityManager->flush();
				$removeCount++;
			}
			$message = sprintf(ngettext('%d directive removed.', '%d directives removed.', $removeCount), $removeCount);

			$this->stdResponse->success = TRUE;
			$this->stdResponse->message = _($message);
		} else {
			$this->stdResponse->success = FALSE;
			$this->stdResponse->message = _("Data submitted is empty.");
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
			$row[] = $directive->getName();
			$row[] = $directive->getPosition()->getName();
			$row[] = $directive->getPhonemobil();
			$row[] = $directive->getPhone();
			$row[] = $directive->getEmail();
			$row[] = $directive->getClub()->getName();
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