<?php

/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_PictureController extends App_Controller_Action {
	const SRC_PICTURE = "/image/upload/galleryview/photos/";
	const SRC_CROP_PICTURE = "/image/upload/galleryview/photos/crops/";

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
		$formFilter->getElement('nameFilter')->setLabel(_("Title picture"));
		$this->view->formFilter = $formFilter;
	}

	/**
	 *
	 * This action shows a form in create mode
	 * @access public
	 */
	public function createAction() {
		$this->_helper->layout()->disableLayout();

		$form = new Admin_Form_Picture();
		$form->setAction($this->_helper->url("save"));
		$form->getElement('club')->setMultiOptions($this->getClubPathfinders());
		$form->getElement('pictureCategory')->setMultiOptions($this->getPictureCategories());
		$form->getElement('pictureType')->setMultiOptions($this->getPictureTypes());
		$this->view->form = $form;
	}

	/**
	 *
	 * Creates a new Picture
	 * @access public
	 */
	public function saveAction() {
		if ($this->_request->isPost()) {
			$form = new Admin_Form_Picture();
			$form->getElement('club')->setMultiOptions($this->getClubPathfinders());
			$form->getElement('pictureCategory')->setMultiOptions($this->getPictureCategories());
			$form->getElement('pictureType')->setMultiOptions($this->getPictureTypes());

			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				$pictureRepo = $this->_entityManager->getRepository('Model\Picture');
				if (!$pictureRepo->verifyExistTitle($formData['title'])) {
					$imageFile = $form->getElement('file');
					$imageFilecrop = $form->getElement('filecrop');

					try {
						$imageFile->receive();
						$imageFilecrop->receive();
					} catch (Zend_File_Transfer_Exception $e) {
						$e->getMessage();
					}

					$mimeType = $_FILES['file']['type'];
					$filename = $_FILES['file']['name'];

					$mimeTypeCrop = $_FILES['filecrop']['type'];
					$filenameCrop = $_FILES['filecrop']['name'];

					$club = $this->_entityManager->find('Model\ClubPathfinder', (int)$formData['club']);
					$pictureType = $this->_entityManager->find('Model\PictureType', (int)$formData['pictureType']);
					$pictureCategory = $this->_entityManager->find('Model\PictureCategory', (int)$formData['pictureCategory']);

					$picture = new Model\Picture();
					$picture->setTitle($formData['title'])
							->setDescription($formData['description'])
							->setSrc(self::SRC_PICTURE)
							->setFilename($filename)
							->setMimeType($mimeType)
							->setSrcCrop(self::SRC_CROP_PICTURE)
							->setFilenameCrop($filenameCrop)
							->setMimeTypeCrop(self::SRC_CROP_PICTURE)
							->setClub($club)
							->setPictureType($pictureType)
							->setPictureCategory($pictureCategory)
							->setCreated(new DateTime('now'));

					$this->_entityManager->persist($picture);
					$this->_entityManager->flush();

					$this->_helper->flashMessenger(array('success' => _("Picture saved")));
					$this->_helper->redirector('read', 'Picture', 'admin', array('type'=>'information'));
				} else {
					$this->_helper->flashMessenger(array('success' => _("erro saved")));
					$this->_helper->redirector('read', 'Picture', 'admin', array('type'=>'information'));
				}
			} else {
				$this->_helper->redirector('read', 'Picture', 'admin', array('type'=>'information'));
			}
		} else {
			$this->_helper->redirector('read', 'Picture', 'admin', array('type'=>'information'));
		}
	}

	/**
	 *
	 * This action shows the form in update mode.
	 * @access public
	 */
	public function updateAction() {
		$this->_helper->layout()->disableLayout();

		$form = new Admin_Form_Picture();
		$form->removeElement('file');
		$form->removeElement('filecrop');

		$id = $this->_getParam('id', 0);
		$picture = $this->_entityManager->find('Model\Picture', $id);
		if ($picture != NULL) {//security
			$form->getElement('title')->setValue($picture->getTitle());
			$form->getElement('description')->setValue($picture->getDescription());
		} else {
			$this->stdResponse->success = FALSE;
			$this->stdResponse->message = _("The requested record was not found.");
			$this->_helper->json($this->stdResponse);
		}
		$this->view->form = $form;
	}

	/**
	 *
	 * Updates the title and description of a Picture
	 * @access public
	 * 1) Get the record to edit
	 * 2) Validate the record was no deleted
	 * 3) Validate the existance of another Picture with the same title.
	 * 4) Saves the changes.
	 */
	public function updateSaveAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$form = new Admin_Form_Picture();
		$form->removeElement('file');
		$form->removeElement('filecrop');

		$formData = $this->getRequest()->getPost();
		if ($form->isValid($formData)) {
			$id = $this->_getParam('id', 0);
			$picture = $$this->_entityManager->find('Model\Picture', $id);
			if ($picture != NULL) {
				$pictureRepo = $this->_entityManager->getRepository('Model\Picture');
				if (!$pictureRepo->verifyExistTitle($formData['title']) || $pictureRepo->verifyExistIdAndTitle($id, $formData['title'])) {
					$picture->setTitle($formData['title'])
							->setDescription($formData['description'])
							->setSrc(self::SRC_PICTURE)
							->setFilename("filenamechanged")
							->setMimeType("mimetype changed")
							->setSrcCrop(self::SRC_CROP_PICTURE)
							->setFilenameCrop("file name crop")
							->setMimeTypeCrop("mimetyope crop")
							->setChanged(new DateTime('now'));

					$this->stdResponse->success = TRUE;
					$this->stdResponse->message = _("Picture updated");
				} else {
					$this->stdResponse->success = FALSE;
					$this->stdResponse->name_duplicate = TRUE;
					$this->stdResponse->message = _("The Picture already exists");
				}
			} else {
				$this->stdResponse->success = FALSE;
				$this->stdResponse->message = _("The Picture does not exists");
			}
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
	 * Downloads Picture
	 * @access public
	 */
	public function downloadAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$id = (int)$this->_getParam('id', 0);

		$pictureMapper = new Model_PictureMapper();
		$picture = $pictureMapper->find($id);

		$file = $picture->getFile();
		$this->_response
				->setHeader('Content-type', $file->getMimeType())
				->setHeader('Content-Disposition', sprintf('attachment; filename="%s"', $file->getFilename()));

		$protocol = 'http:';
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) {
			$protocol = 'https:';
		}

		readfile(sprintf('%s//%s%s%s', $protocol, $_SERVER['SERVER_NAME'], $picture->getSrc(), $file->getFilename()));
	}

	/**
	 *
	 * Deletes pictures
	 * @access public
	 * @internal
	 * 1) Gets the model Picture
	 * 2) Validate the existance of dependencies
	 * 3) Change the state field or records to delete
	 */
	public function deleteAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$itemIds = $this->_getParam('itemIds', array());
		if (!empty($itemIds) ) {
			$removeCount = 0;
			foreach ($itemIds as $id) {
				$picture = $this->_entityManager->find('Model\Picture', $id);
				$picture->setState(FALSE);

				$this->_entityManager->persist($picture);
				$this->_entityManager->flush();
				$removeCount++;
			}
			$message = sprintf(ngettext('%d picture removed.', '%d pictures removed.', $removeCount), $removeCount);
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
	 * Outputs an XHR response containing all entries in pictures.
	 * This action serves as a datasource for the read/index view
	 * @xhrParam int filter_title
	 * @xhrParam int iDisplayStart
	 * @xhrParam int iDisplayLength
	 */
	public function readItemsAction() {
		$sortCol = $this->_getParam('iSortCol_0', 1);
		$sortDirection = $this->_getParam('sSortDir_0', 'asc');

		$filterParams['title'] = $this->_getParam('filter_title', NULL);
		$filters = $this->getFilters($filterParams);

		$start = $this->_getParam('iDisplayStart', 0);
		$limit = $this->_getParam('iDisplayLength', 10);
		$page = ($start + $limit) / $limit;

		$pictureRepo = $this->_entityManager->getRepository('Model\Picture');
		$pictures = $pictureRepo->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $pictureRepo->getTotalCount($filters);

		$posRecord = $start+1;
		$data = array();
		foreach ($pictures as $picture) {
			$changed = $picture->getChanged();
			if ($changed != NULL) {
				$changed = $changed->format('d.m.Y');
			}

			$row = array();
			$row[] = $picture->getId();
			$row[] = $picture->getTitle();
			$row[] = $picture->getDescription();
			$row[] = $picture->getFilename();
			$row[] = "";
			$row[] = $picture->getCreated()->format('d.m.Y');
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
			$filters[] = array('field' => 'title', 'filter' => '%'.$filterParams['title'].'%', 'operator' => 'LIKE');
		}

		return $filters;
	}

	/**
	 *
	 * Outputs an XHR response, loads the titles of the pictures.
	 */
	public function autocompleteAction() {
		$filterParams['title'] = $this->_getParam('title_auto', NULL);
		$filters = $this->getFilters($filterParams);

		$pictureRepo = $this->_entityManager->getRepository('Model\Picture');
		$this->stdResponse->items = $pictureRepo->findByCriteriaOnlyTitle($filters);
		$this->_helper->json($this->stdResponse);
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

	/**
	 * Returns all picture categories
	 * @return array
	 */
	public function getPictureCategories() {
		$pictureCategoryRepo = $this->_entityManager->getRepository('Model\PictureCategory');
		$pictureCategories = $pictureCategoryRepo->findAll();

		$pictureCategoryArray = array();
		foreach ($pictureCategories as $pictureCategory) {
			$pictureCategoryArray[$pictureCategory->getId()] = $pictureCategory->getName();
		}

		return $pictureCategoryArray;
	}

	/**
	 * Returns all picture types
	 * @return array
	 */
	public function getPictureTypes() {
		$pictureTypeRepo = $this->_entityManager->getRepository('Model\PictureType');
		$pictureTypes = $pictureTypeRepo->findAll();

		$pictureTypeArray = array();
		foreach ($pictureTypes as $pictureType) {
			$pictureTypeArray[$pictureType->getId()] = $pictureType->getName();
		}

		return $pictureTypeArray;
	}
}