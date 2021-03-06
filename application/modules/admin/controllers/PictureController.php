<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-t.com>
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
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				$pictureMapper = new Model_PictureMapper();
				if (!$pictureMapper->verifyExistTitle($formData['title'])) {
					$imageFile = $form->getElement('file');
            		$imageFilecrop = $form->getElement('filecrop');
            		
					try {
						$imageFile->receive();
						$imageFilecrop->receive();
					} catch (Zend_File_Transfer_Exception $e) {
						$e->getMessage();
					}
					
//					$fh = fopen($_FILES['file']['tmp_name'], 'r');
//					$binary = fread($fh, filesize($_FILES['file']['tmp_name']));
//					fclose($fh);
					
					$mimeType = $_FILES['file']['type'];
					$fileName = $_FILES['file']['name'];

					$dataVault = new Model_ImageDataVault();
					$dataVault->setFilename($fileName)->setMimeType($mimeType)->setBinary("");

					$picture = new Model_Picture();
					$picture->setFile($dataVault)
							->setCreatedBy(1)
							->setDescription($formData['description'])
							->setSrc(self::SRC_PICTURE)
							->setSrcCrops(self::SRC_CROP_PICTURE)
							->setTitle($formData['title']);

					$pictureMapper->save($picture);

					$this->_helper->flashMessenger(array('success' => _("Picture saved")));
					$this->_helper->redirector('read', 'Picture', 'admin', array('type'=>'information'));								            
				}
			}
		} else {
			$this->_helper->redirector('read', 'Picture', 'admin', array('type'=>'information'));
		}
	}
	
	/**
	 * 
	 * This action shows the form in update the title and description for Picture.
	 * @access public
	 */
	public function updateAction() {
		$this->_helper->layout()->disableLayout();
		
		$form = new Admin_Form_Picture();
        $form->removeElement('file');
        $form->removeElement('filecrop');

		$id = $this->_getParam('id', 0);
		$pictureMapper = new Model_PictureMapper();
		$picture = $pictureMapper->find($id);
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
                	
			$pictureMapper = new Model_PictureMapper();
			$picture = $pictureMapper->find($id);
			if ($picture != NULL) {
				if (!$pictureMapper->verifyExistTitle($formData['title']) || $pictureMapper->verifyExistIdAndTitle($id, $formData['title'])) {
					$picture->setTitle($formData['title'])
						->setDescription($formData['description'])
						->setChangedBy(Zend_Auth::getInstance()->getIdentity()->id);
					
					$pictureMapper->update($id, $picture);

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
				$pictureMapper = new Model_PictureMapper();
				$pictureMapper->delete($id);
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

		$pictureMapper = new Model_PictureMapper();
		$pictures = $pictureMapper->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $pictureMapper->getTotalCount($filters);
		
		$posRecord = $start+1;
		$data = array();
		foreach ($pictures as $picture) {
			$created = new Zend_Date($picture->getCreated());
			$changed = $picture->getChanged();
			if ($changed != NULL) {
				$changed = new Zend_Date($picture->getChanged());
				$changed = $changed->toString("dd.MM.YYYY");
			}
			
			$row = array();
			$row[] = $picture->getId();
			$row[] = $picture->getTitle();
			$row[] = $picture->getDescription();
			$row[] = $picture->getFile()->getFilename();
			$row[] = $picture->getCategory()->getName();
			$row[] = $created->toString("dd.MM.YYYY");
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
	 * @param array $filterParams contains the values selected by the user.
	 * @return array(field, filter, operator)
	 */
	private function getFilters($filterParams) {
		if (empty($filterParams)) {
			return array();
		}
		
		foreach ($filterParams as $field => $filter) {
			$filterParams[$field] = trim($filter);
		}
		
		$filters = array ();
		
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
		$titleAuto = $this->_getParam('title_auto', NULL);

		$this->stdResponse->items = array('one', 'javascript', 'java');
		$this->_helper->json($this->stdResponse);		
	}
}