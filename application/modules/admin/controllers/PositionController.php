<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_PositionController extends App_Controller_Action {
	
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
	}
	
	/**
	 * 
	 * This action shows a form in create mode
	 * @access public
	 */
	public function createAction() {
		$this->_helper->layout()->disableLayout();
		
		$form = new Admin_Form_Position();
		$this->view->form = $form;
	}
	
	/**
	 * 
	 * Creates a new Position
	 * @access public
	 */
	public function createSaveAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        $form = new Admin_Form_Position();
        
        $formData = $this->getRequest()->getPost();
      	if ($form->isValid($formData)) {
			$positionMapper = new Model_PositionMapper();
			if (!$positionMapper->verifyExistName($formData['name'])) {
				$position = new Model_Position($formData);
				$position->setCreatedBy(Zend_Auth::getInstance()->getIdentity()->id);
				$positionMapper->save($position);
				$this->stdResponse->success = TRUE;
				$this->stdResponse->message = _("Position saved");	
			} else {
				$this->stdResponse->success = FALSE;
				$this->stdResponse->name_duplicate = TRUE;
				$this->stdResponse->message = _("The Position already exists");
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
	 * This action shows the form in update mode for Position.
	 * @access public
	 */
	public function updateAction() {
		$this->_helper->layout()->disableLayout();
		$form = new Admin_Form_Position();
		
		$id = $this->_getParam('id', 0);
		$positionMapper = new Model_PositionMapper();
		$position = $positionMapper->find($id);
		if ($position != NULL) {//security
			$form->getElement('name')->setValue($position->getName());
			$form->getElement('description')->setValue($position->getDescription());
		} else {
			// response to client
			$this->stdResponse->success = FALSE;
			$this->stdResponse->message = _("The requested record was not found.");
			$this->_helper->json($this->stdResponse);
		}
		$this->view->form = $form;
	}

	/**
	 * 
	 * Updates a Position
	 * @access public
	 * 1) Gets the record to edit
	 * 2) Validates the record was no deleted
	 * 3) Validates the existance of another Position with the same name.
	 * 4) Saves the changes.
	 */
	public function updateSaveAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$form = new Admin_Form_Position();

		$formData = $this->getRequest()->getPost();
		if ($form->isValid($formData)) {
			$id = $this->_getParam('id', 0);

			$positionMapper = new Model_PositionMapper();
			$position = $positionMapper->find($id);
			if ($position != NULL) {
				if (!$positionMapper->verifyExistName($formData['name']) || $positionMapper->verifyExistIdAndName($id, $formData['name'])) {
					$position->setName($formData['name'])
							->setDescription($formData['description'])
							->setChangedBy(Zend_Auth::getInstance()->getIdentity()->id);

					$positionMapper->update($id, $position);

					$this->stdResponse->success = TRUE;
					$this->stdResponse->message = _("Position updated");
				} else {
					$this->stdResponse->success = FALSE;
					$this->stdResponse->name_duplicate = TRUE;
					$this->stdResponse->message = _("The Position already exists");
				}
			} else {
				$this->stdResponse->success = FALSE;
				$this->stdResponse->message = _("The Position does not exists");
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
	 * Deletes positions
	 * @access public
	 * @internal
	 * 1) Gets the model position
	 * 2) Validates the existance of dependencies
	 * 3) Changes the state field or records to delete
	 */
	public function deleteAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$itemIds = $this->_getParam('itemIds', array());
		if (!empty($itemIds) ) {

			$removeCount = 0;
			foreach ($itemIds as $id) {
				$positionMapper = new Model_PositionMapper();
				$positionMapper->delete($id);
				$removeCount++;
			}
			$message = sprintf(ngettext('%d position removed.', '%d positions removed.', $removeCount), $removeCount);

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
	 * Outputs an XHR response containing all entries in positions.
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

		$positionMapper = new Model_PositionMapper();
		$positions = $positionMapper->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $positionMapper->getTotalCount($filters);
		
		$posRecord = $start+1;
		$data = array();
		foreach ($positions as $position) {
			$created = new Zend_Date($position->getCreated());
			$changed = $position->getChanged();
			if ($changed != NULL) {
				$changed = new Zend_Date($position->getChanged());
				$changed = $changed->toString("dd.MM.YYYY");
			}

			$row = array();			
			$row[] = $position->getId();
			$row[] = $position->getName();
			$row[] = $position->getDescription();
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
	 *
	 * Outputs an XHR response, loads the names of the positions.
	 */
	public function autocompleteAction() {
		$filterParams['name'] = $this->_getParam('name_auto', NULL);
		$filters = $this->getFilters($filterParams);

		$positionMapper = new Model_PositionMapper();
		$this->stdResponse->items = $positionMapper->findByCriteriaOnlyName($filters);
		$this->_helper->json($this->stdResponse);
	}
}