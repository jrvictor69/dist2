<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_MemberClubController extends App_Controller_Action {

	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
	public function init() {
		parent::init();
	}

	/**
	 * This action shows a paginated list of members club
	 * @access public
	 */
	public function indexAction() {
		$formFilter = new Admin_Form_SearchFilter();
		$formFilter->getElement('nameFilter')->setLabel(_("First name Member Club"));
		$this->view->formFilter = $formFilter;
	}

	/**
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

		$src = '/image/profile/female_or_male_default.jpg';
		$form->setSource($src);

		$this->view->form = $form;
	}

	/**
	 * Creates a new Member Club
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
				$club = $this->_entityManager->find('Model\ClubPathfinder', (int)$formData['club']);
				$position = $this->_entityManager->find('Model\Position', (int)$formData['position']);

				$member = new Model\MemberClub();
				$member->setIdentityCard(4)
					->setFirstName($formData['firstName'])
					->setLastName($formData['lastName'])
					->setEmail($formData['email'])
					->setPhone($formData['phone'])
					->setPhonemobil($formData['phonemobil'])
					->setSex((int)$formData['sex'])
					->setClub($club)
					->setCreated(new \DateTime('now'));

				if ($_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
					if ($_FILES['file']['error'] == UPLOAD_ERR_OK) {
						$fh = fopen($_FILES['file']['tmp_name'], 'r');
						$binary = fread($fh, filesize($_FILES['file']['tmp_name']));
						fclose($fh);

						$mimeType = $_FILES['file']['type'];
						$fileName = $_FILES['file']['name'];

						$dataVaultMapper = new Model_DataVaultMapper();
						$dataVault = new Model_DataVault();
						$dataVault->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);
						$dataVaultMapper->save($dataVault);

						$member->setProfilePictureId($dataVault->getId());
					}
				}

				$this->_entityManager->persist($member);
				$this->_entityManager->flush();

				$this->_helper->flashMessenger(array('success' => _("Member Club created")));
				$this->_helper->redirector('index', 'Memberclub', 'admin', array('type'=>'pathfinder'));
			} else {
				$this->_helper->redirector('index', 'Memberclub', 'admin', array('type'=>'pathfinder'));
			}
		} else {
			$this->_helper->redirector('index', 'Memberclub', 'admin', array('type'=>'pathfinder'));
		}
	}

	/**
	 * This action shows the form to update a member club.
	 * @access public
	 */
	public function updateAction() {
		$this->_helper->layout()->disableLayout();

		$form = new Admin_Form_Directive();
		$form->getElement('sex')->setMultiOptions($this->getGenders());
		$form->getElement('club')->setMultiOptions($this->getClubPathfinders());
		$form->getElement('position')->setMultiOptions($this->getPositions());
		$form->setAction($this->_helper->url('edit'));

		$id = $this->_getParam('id', 0);
		$member = $this->_entityManager->find('Model\MemberClub', $id);
		if ($member != NULL) {
			$form->getElement('id')->setValue($member->getId());
			$form->getElement('firstName')->setValue($member->getFirstName());
			$form->getElement('lastName')->setValue($member->getLastName());
			$form->getElement('sex')->setValue($member->getSex());
			$form->getElement('email')->setValue($member->getEmail());
			$form->getElement('phonemobil')->setValue($member->getPhonemobil());
			$form->getElement('phone')->setValue($member->getPhone());
			$form->getElement('club')->setValue($member->getClub()->getId());

			$dataVaultMapper = new Model_DataVaultMapper();
			$dataVault = $dataVaultMapper->find($member->getProfilePictureId());

			if ($dataVault != NULL && $dataVault->getBinary()) {
				$src = $this->_helper->url('profile-picture', NULL, NULL, array('id' => $dataVault->getId(), 'timestamp' => time()));
			} else {
				if ($member->getSex() == Model\Person::SEX_MALE) {
					$src = '/image/profile/male_default.jpg';
				} elseif ($member->getSex() == Model\Person::SEX_FEMALE) {
					$src = '/image/profile/female_default.jpg';
				}
			}
			$form->setSource($src);
		} else {
			$this->stdResponse->success = FALSE;
			$this->stdResponse->message = _("The requested record was not found.");
			$this->_helper->json($this->stdResponse);
		}

		$this->view->form = $form;
	}

	/**
	 * Updates a Member Club of the club pathfinder
	 * @access public
	 */
	public function editAction() {
		if ($this->_request->isPost()) {
			$form = new Admin_Form_Directive();
			$form->getElement('sex')->setMultiOptions($this->getGenders());
			$form->getElement('club')->setMultiOptions($this->getClubPathfinders());
			$form->getElement('position')->setMultiOptions($this->getPositions());

			$formData = $this->getRequest()->getPost();

			if ($form->isValid($formData)) {
				$id = $this->_getParam('id', 0);
				$member = $this->_entityManager->find('Model\MemberClub', $id);
				if ($member != NULL) {
					$club = $this->_entityManager->find('Model\ClubPathfinder', (int)$formData['club']);
					$position = $this->_entityManager->find('Model\Position', (int)$formData['position']);

					$member->setIdentityCard(3)
						->setFirstName($formData['firstName'])
						->setLastName($formData['lastName'])
						->setEmail($formData['email'])
						->setPhone($formData['phone'])
						->setPhonemobil($formData['phonemobil'])
						->setSex((int)$formData['sex'])
						->setClub($club)
						->setChanged(new \DateTime('now'));

					if ($_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
						if ($_FILES['file']['error'] == UPLOAD_ERR_OK) {
							$fh = fopen($_FILES['file']['tmp_name'], 'r');
							$binary = fread($fh, filesize($_FILES['file']['tmp_name']));
							fclose($fh);

							$mimeType = $_FILES['file']['type'];
							$fileName = $_FILES['file']['name'];

							$dataVaultMapper = new Model_DataVaultMapper();

							if ($member->getProfilePictureId() != NULL) {// if it has image profile update
								$dataVault = $dataVaultMapper->find($member->getProfilePictureId(), FALSE);
								$dataVault->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);
								$dataVaultMapper->update($member->getProfilePictureId(), $dataVault);
							} elseif ($member->getProfilePictureId() == NULL) {// if it don't have image profile create
								$dataVault = new Model_DataVault();
								$dataVault->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);
								$dataVaultMapper->save($dataVault);

								$member->setProfilePictureId($dataVault->getId());
							}
						}
					}

					$this->_entityManager->persist($member);
					$this->_entityManager->flush();

					$this->_helper->flashMessenger(array('success' => _("Member Club updated")));
					$this->_helper->redirector('index', 'Memberclub', 'admin', array('type'=>'pathfinder'));
				} else {
					$this->_helper->flashMessenger(array('error' => _("Member Club do not found")));
					$this->_helper->redirector('index', 'Memberclub', 'admin', array('type'=>'pathfinder'));
				}
			} else {
				$this->_helper->redirector('index', 'Memberclub', 'admin', array('type'=>'pathfinder'));
			}
		} else {
			$this->_helper->redirector('index', 'Memberclub', 'admin', array('type'=>'pathfinder'));
		}
	}

	/**
	 * Deletes members club
	 * @access public
	 * @internal
	 * 1) Gets the model Member Club
	 * 2) Validates the existance of dependencies
	 * 3) Changes the state field or records to delete
	 */
	public function deleteAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$itemIds = $this->_getParam('itemIds', array());
		if (!empty($itemIds) ) {
			$removeCount = 0;
			foreach ($itemIds as $id) {
				$member = $this->_entityManager->find('Model\MemberClub', $id);
				$member->setState(FALSE);

				$this->_entityManager->persist($member);
				$this->_entityManager->flush();
				$removeCount++;
			}
			$message = sprintf(ngettext('%d member club removed.', '%d members club removed.', $removeCount), $removeCount);

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
	 * Sends the binary file vault to the user agent.
	 * @return void
	 */
	public function profilePictureAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$id = (int)$this->_getParam('id', 0);

		$dataVaultMapper = new Model_DataVaultMapper();
		$dataVault = $dataVaultMapper->find($id);

		if ($dataVault->getBinary()) {
			$this->_response
			//No caching
				->setHeader('Pragma', 'public')
				->setHeader('Expires', '0')
				->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
				->setHeader('Cache-Control', 'private')
				->setHeader('Content-type', $dataVault->getMimeType())
				->setHeader('Content-Transfer-Encoding', 'binary')
				->setHeader('Content-Length', strlen($dataVault->getBinary()));

			echo $dataVault->getBinary();
		} else {
			$this->_response->setHttpResponseCode(404);
		}
	}

	/**
	 * Outputs an XHR response containing all entries in members clubs.
	 * This action serves as a datasource for the index view
	 * @xhrParam int filter_name
	 * @xhrParam int iDisplayStart
	 * @xhrParam int iDisplayLength
	 */
	public function dsReadItemsAction() {
		$sortCol = $this->_getParam('iSortCol_0', 1);
		$sortDirection = $this->_getParam('sSortDir_0', 'asc');

		$filterParams['name'] = $this->_getParam('filter_name', NULL);
		$filters = $this->getFilters($filterParams);

		$start = $this->_getParam('iDisplayStart', 0);
		$limit = $this->_getParam('iDisplayLength', 10);
		$page = ($start + $limit) / $limit;

		$memberClubRepo = $this->_entityManager->getRepository('Model\MemberClub');
		$members = $memberClubRepo->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $memberClubRepo->getTotalCount($filters);

		$posRecord = $start+1;
		$data = array();
		foreach ($members as $member) {
			$changed = $member->getChanged();
			if ($changed != NULL) {
				$changed = $changed->format('d.m.Y');
			}

			$row = array();
			$row[] = $member->getId();
			$row[] = $member->getName();
			$row[] = $member->getPhonemobil();
			$row[] = $member->getPhone();
			$row[] = $member->getEmail();
			$row[] = $member->getClub()->getName();
			$row[] = $member->getCreated()->format('d.m.Y');
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
	 * Outputs an XHR response, loads the first names of the members club.
	 */
	public function autocompleteAction() {
		$filterParams['name'] = $this->_getParam('name_auto', NULL);
		$filters = $this->getFilters($filterParams);

		$memberRepo = $this->_entityManager->getRepository('Model\MemberClub');
		$members = $memberRepo->findByCriteria($filters);

		$data = array();
		foreach ($members as $member) {
			$data[] = $member->getFirstName();
		}

		$this->stdResponse->items = $data;
		$this->_helper->json($this->stdResponse);
	}

	/**
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
			$filters[] = array('field' => 'firstName', 'filter' => '%'.$filterParams['name'].'%', 'operator' => 'LIKE');
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