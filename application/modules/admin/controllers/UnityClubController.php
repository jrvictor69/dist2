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
	 * This action shows a paginated list of unities club
	 * @access public
	 */
	public function readAction() {
		$formFilter = new Admin_Form_SearchFilter();
		$formFilter->getElement('nameFilter')->setLabel(_("Name Unity Club"));
		$this->view->formFilter = $formFilter;
	}

	/**
	 * This action shows a form in create mode
	 * @access public
	 */
	public function createAction() {
		$this->_helper->layout()->disableLayout();

		$form = new Admin_Form_UnityClub();
		$form->getElement('club')->setMultiOptions($this->getClubPathfinders());
		$form->setAction($this->_helper->url('save'));

		$src = '/image/profile/smile.jpg';
		$form->setSource($src);

		$this->view->form = $form;
	}

	/**
	 * Creates a new Unity Club
	 * @access public
	 */
	public function saveAction() {
		if ($this->_request->isPost()) {
			$form = new Admin_Form_UnityClub();
			$form->getElement('club')->setMultiOptions($this->getClubPathfinders());

			$formData = $this->getRequest()->getPost();

			if ($form->isValid($formData)) {
				$club = $this->_entityManager->find('Model\ClubPathfinder', (int)$formData['club']);

				$unityClub = new Model\UnityClub();
				$unityClub->setName($formData['name'])
					->setMotto($formData['motto'])
					->setDescription($formData['description'])
					->setClub($club)
					->setCreated(new \DateTime('now'));

				if ($_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
					if ($_FILES['file']['error'] == UPLOAD_ERR_OK) {
						$fh = fopen($_FILES['file']['tmp_name'], 'r');
						$binary = fread($fh, filesize($_FILES['file']['tmp_name']));
						fclose($fh);

						$mimeType = $_FILES['file']['type'];
						$fileName = $_FILES['file']['name'];

						$dataVaultMapper = new Model_ImageDataVaultMapper();
						$dataVault = new Model_ImageDataVault();
						$dataVault->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);
						$dataVaultMapper->save($dataVault);

						$unityClub->setLogoId($dataVault->getId());
					}
				}

				$this->_entityManager->persist($unityClub);
				$this->_entityManager->flush();

				$this->_helper->flashMessenger(array('success' => _("Unity Club saved")));
				$this->_helper->redirector('read', 'Unityclub', 'admin', array('type'=>'pathfinder'));
			} else {
				$this->_helper->redirector('read', 'Unityclub', 'admin', array('type'=>'pathfinder'));
			}
		} else {
			$this->_helper->redirector('read', 'Unityclub', 'admin', array('type'=>'pathfinder'));
		}
	}

	/**
	 * This action shows the form in update mode for Unity Club.
	 * @access public
	 */
	public function updateAction() {
		$this->_helper->layout()->disableLayout();

		$form = new Admin_Form_UnityClub();
		$form->getElement('club')->setMultiOptions($this->getClubPathfinders());
		$form->setAction($this->_helper->url('edit'));

		$id = $this->_getParam('id', 0);
		$unityClub = $this->_entityManager->find('Model\UnityClub', $id);
		if ($unityClub != NULL) {
			$form->getElement('id')->setValue($unityClub->getId());
			$form->getElement('name')->setValue($unityClub->getName());
			$form->getElement('motto')->setValue($unityClub->getMotto());
			$form->getElement('description')->setValue($unityClub->getDescription());
			$form->getElement('club')->setValue($unityClub->getClub()->getId());

			$imageDataVaultMapper = new Model_ImageDataVaultMapper();
			$imagePicture = $imageDataVaultMapper->find($unityClub->getLogoId());

			if ($imagePicture != NULL && $imagePicture->getBinary()) {
				$src = $this->_helper->url('profile-picture', NULL, NULL, array('id' => $imagePicture->getId(), 'timestamp' => time()));
			} else {
				$src = '/image/profile/smile.jpg';
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
	 * Updates an Unity Club
	 * @access public
	 */
	public function editAction() {
		if ($this->_request->isPost()) {
			$form = new Admin_Form_UnityClub();
			$form->getElement('club')->setMultiOptions($this->getClubPathfinders());

			$formData = $this->getRequest()->getPost();

			if ($form->isValid($formData)) {
				$id = $this->_getParam('id', 0);

				$club = $this->_entityManager->find('Model\ClubPathfinder', (int)$formData['club']);

				$unityClub = $this->_entityManager->find('Model\UnityClub', $id);
				$unityClub->setName($formData['name'])
					->setMotto($formData['motto'])
					->setDescription($formData['description'])
					->setClub($club)
					->setChanged(new \DateTime('now'));

				if ($_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
					if ($_FILES['file']['error'] == UPLOAD_ERR_OK) {
						$fh = fopen($_FILES['file']['tmp_name'], 'r');
						$binary = fread($fh, filesize($_FILES['file']['tmp_name']));
						fclose($fh);

						$mimeType = $_FILES['file']['type'];
						$fileName = $_FILES['file']['name'];

						$dataVaultMapper = new Model_ImageDataVaultMapper();

						if ($unityClub->getLogoId() != NULL) {// if it has image profile update
							$dataVault = $dataVaultMapper->find($unityClub->getLogoId(), FALSE);
							$dataVault->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);
							$dataVaultMapper->update($unityClub->getLogoId(), $dataVault);
						} elseif ($unityClub->getLogoId() == NULL) {// if it don't have image profile create
							$dataVault = new Model_ImageDataVault();
							$dataVault->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);
							$dataVaultMapper->save($dataVault);

							$unityClub->setLogoId($dataVault->getId());
						}
					}
				}

				$this->_entityManager->persist($unityClub);
				$this->_entityManager->flush();

				$this->_helper->flashMessenger(array('success' => _("Unity Club edited")));
				$this->_helper->redirector('read', 'Unityclub', 'admin', array('type'=>'pathfinder'));
			} else {
				$this->_helper->redirector('read', 'Unityclub', 'admin', array('type'=>'pathfinder'));
			}
		} else {
			$this->_helper->redirector('read', 'Unityclub', 'admin', array('type'=>'pathfinder'));
		}
	}

	/**
	 *
	 * Deletes Unity Club
	 * @access public
	 * @internal
	 * 1) Gets the model unity club
	 * 2) Validates the existance of dependencies
	 * 3) Changes the state field or records to delete
	 */
	public function deleteAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$itemIds = $this->_getParam('itemIds', array());
		if (!empty($itemIds) ) {
			$removeCount = 0;
			foreach ($itemIds as $id) {
				$unityClub = $this->_entityManager->find('Model\UnityClub', $id);
				$unityClub->setState(FALSE);

				$this->_entityManager->persist($unityClub);
				$this->_entityManager->flush();
				$removeCount++;
			}
			$message = sprintf(ngettext('%d unity removed.', '%d unities removed.', $removeCount), $removeCount);

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

		$imageMapper = new Model_ImageDataVaultMapper();
		$imageLogo = $imageMapper->find($id);

		if ($imageLogo->getBinary()) {
			$this->_response
			//No caching
			->setHeader('Pragma', 'public')
			->setHeader('Expires', '0')
			->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
			->setHeader('Cache-Control', 'private')
			->setHeader('Content-type', $imageLogo->getMimeType())
			->setHeader('Content-Transfer-Encoding', 'binary')
			->setHeader('Content-Length', strlen($imageLogo->getBinary()));

			echo $imageLogo->getBinary();
		} else {
			$this->_response->setHttpResponseCode(404);
		}
	}

	/**
	 * Outputs an XHR response containing all entries in unities club.
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
	 * Outputs an XHR response, loads the names of the unities club.
	 */
	public function autocompleteAction() {
		$filterParams['name'] = $this->_getParam('name_auto', NULL);
		$filters = $this->getFilters($filterParams);

		$unityClubRepo = $this->_entityManager->getRepository('Model\UnityClub');
		$unitiesClub = $unityClubRepo->findByCriteria($filters);

		$data = array();
		foreach ($unitiesClub as $unityClub) {
			$data[] = $unityClub->getName();
		}

		$this->stdResponse->items = $data;
		$this->_helper->json($this->stdResponse);
	}

	/**
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

		if (!empty($filterParams['name'])) {
			$filters[] = array('field' => 'name', 'filter' => '%'.$filterParams['name'].'%', 'operator' => 'LIKE');
		}

		return $filters;
	}

	/**
	 * Returns all club pathfinders
	 * @return array
	 */
	public function getClubPathfinders() {
		$clubRepo = $this->_entityManager->getRepository('Model\ClubPathfinder');
		return $clubRepo->findAllArray();
	}
}