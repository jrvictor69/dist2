<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_PrivilegeController extends App_Controller_Action {
	
	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
	public function init() {
		parent::init();
	}
	
	/**
	 * 
	 * In case no action is sent read action will be executed
	 * @access public
	 */
	public function indexAction() {
		$this->_helper->redirector('read');
	}
	
	/**
	 * 
	 * This action shows a paginated list of privileges
	 * @access public
	 */
	public function readAction() {
		$formFilter = new Admin_Form_SearchFilter();
		$formFilter->getElement('nameFilter')->setLabel(_('Name Privilege'));
		$this->view->formFilter = $formFilter;
	}
	
	/**
	 * 
	 * This action shows a form in create mode
	 * @access public
	 */
	public function createAction() {
		$this->_helper->layout()->disableLayout();
		
		$form = new Admin_Form_Privilege();
        $this->view->form = $form;
	}
	
	/**
	 * 
	 * Creates a new Privilege
	 * @access public
	 */
	public function createSaveAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        $form = new Admin_Form_Privilege();
        
        $formData = $this->_request->getPost();
        if ($form->isValid($formData)) {          	
        	try {
           		$privilegeMapper = new Model_PrivilegeMapper();
               	if (!$privilegeMapper->verifyExistName($formData['name'])) {
               		$privilege = new Model_Privilege($formData);
                	$privilege->setCreatedBy(Zend_Auth::getInstance()->getIdentity()->id);
                		
                	$privilegeMapper->save($privilege);
                	
                	$this->view->success = TRUE;
                	$this->_messenger->clearMessages();
                    $this->_messenger->addSuccess(_("Privilege saved"));
                    $this->view->message = $this->view->seeMessages();
                } else {
					$this->view->success = FALSE;
					$this->view->name_duplicate = TRUE;
                    $this->_messenger->addError(_("The Privilege already exists"));
                    $this->view->message = $this->view->seeMessages();                			
                }
          	} catch (Exception $e) {
            	$this->exception($this->view, $e);
           	}
     	} else {
			$this->view->success = FALSE;
			$this->view->messageArray = $form->getMessages();
			$this->_messenger->addError(_("The form contains error and is not saved"));
			$this->view->message = $this->view->seeMessages();
       	}
        // send response to client
        $this->_helper->json($this->view);
	}
	
	/**
	 * 
	 * This action shows the form in update mode for Privilege.
	 * @access public
	 */
	public function updateAction() {
		$this->_helper->layout()->disableLayout();
		$form = new Admin_Form_Privilege();
        
        try {
        	$id = $this->_getParam('id', 0);
            $privilegeMapper = new Model_PrivilegeMapper();
            $privilege = $privilegeMapper->find($id);
            if ($privilege != NULL) {//security
				$form->getElement('name')->setValue($privilege->getName());
				$form->getElement('description')->setValue($privilege->getDescription());
				$form->getElement('module')->setValue($privilege->getModule());
				$form->getElement('controller')->setValue($privilege->getController());
				$form->getElement('action')->setValue($privilege->getAction());
          	} else {
            	// response to client
	            $this->view->success = FALSE;
	            $this->_messenger->addSuccess(_("The requested record was not found."));
	          	$this->view->message = $this->view->seeMessages();
	            $this->_helper->json($this->view);
          	}
        } catch (Exception $e) {
        	$this->exception($this->view, $e);
            $this->_helper->json($this->view);
        }
        
        $this->view->form = $form;
	}
	
	/**
	 * 
	 * Updates a Privilege
	 * @access public
	 * 1) Get the record to edit
	 * 2) Validate the record was no deleted
	 * 3) Validate the existance of another Privilege with the same name.
	 * 4) Saves the changes.
	 */
	public function updateSaveAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$form = new Admin_Form_Privilege();
		
		$formData = $this->_request->getPost();
        if ($form->isValid($formData)) {
            try {
                $id = $this->_getParam('id', 0);
                	
                $privilegeMapper = new Model_PrivilegeMapper();
                $privilege = $privilegeMapper->find($id);
                if ($privilege != NULL) {
                	if (!$privilegeMapper->verifyExistName($formData['name']) || $privilegeMapper->verifyExistIdAndName($id, $formData['name'])) {
	               		$privilege->setName($formData['name'])
	                			->setDescription($formData['description'])
	                			->setModule($formData['module'])
	                			->setController($formData['controller'])
	                			->setAction($formData['action'])
	                			->setChangedBy(Zend_Auth::getInstance()->getIdentity()->id);
	                			
	                	$privilegeMapper->update($id, $privilege);
	                	
	                	$this->view->success = TRUE;
                		$this->_messenger->clearMessages();
                    	$this->_messenger->addSuccess(_("Privilege updated"));
                    	$this->view->message = $this->view->seeMessages();
                	} else {
                		$this->view->success = FALSE;
                		$this->view->name_duplicate = TRUE;
                    	$this->_messenger->addError(_("The Privilege already exists"));
                    	$this->view->message = $this->view->seeMessages();	
                	}
                } else {
					$this->view->success = FALSE;
                    $this->_messenger->addError(_("The Privilege does not exists"));
                    $this->view->message = $this->view->seeMessages();                	                	
                }
        	} catch (Exception $e) {
                $this->exception($this->view, $e);
         	}
		} else {
            $this->view->success = FALSE;
			$this->view->messageArray = $form->getMessages();
			$this->_messenger->addError(_("The form contains error and is not updated"));
			$this->view->message = $this->view->seeMessages();
    	}
        // send response to client
        $this->_helper->json($this->view);
	}
	
	/**
	 * 
	 * Deletes privileges
	 * @access public
	 * @internal
	 * 1) Get the model privilege
	 * 2) Validate the existance of dependencies
	 * 3) Change the state field or records to delete
	 */
	public function deleteAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
        $itemIds = $this->_getParam('itemIds', array());
       	if (!empty($itemIds) ) {
        	try {
           		$privilegeMapper = new Model_PrivilegeMapper();
           		$removeCount = 0;
               	foreach ($itemIds as $id) {              		
	                $privilegeMapper->delete($id);
	                $removeCount++;
                }
                $message = sprintf(ngettext('%d privilege removed.', '%d privileges removed.', $removeCount), $removeCount);
                	
                $this->view->success = TRUE;
               	$this->_messenger->addSuccess(_($message));
               	$this->view->message = $this->view->seeMessages();
        	} catch (Exception $e) {
            	$this->exception($this->view, $e);
           	}
      	} else {
        	$this->view->success = FALSE;
            $this->_messenger->addNotice(_("Data submitted is empty."));
        	$this->view->message = $this->view->seeMessages();
      	}
        // send response to client
        $this->_helper->json($this->view);
	}
	
	/**
	 * 
	 * Outputs an XHR response containing all entries in privileges.
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

		$privilegeMapper = new Model_PrivilegeMapper();
		$privileges = $privilegeMapper->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $privilegeMapper->getTotalCount($filters);

		$posRecord = $start+1;
		$data = array();
		foreach ($privileges as $privilege) {
			$created = new Zend_Date($privilege->getCreated());
			
			$changed = $privilege->getChanged();
			if ($changed != NULL) {
				$changed = new Zend_Date($privilege->getChanged());
				$changed = $changed->toString("dd.MM.YYYY");
			}
			
			$row = array();			
			$row[] = $privilege->getId();
			$row[] = $privilege->getName();
			$row[] = $privilege->getDescription();
			$row[] = $privilege->getModule();
			$row[] = $privilege->getController();
			$row[] = $privilege->getAction();
			$row[] = $created->toString("dd.MM.YYYY");
			$row[] = $changed;
			$row[] = '[]';
			$data[] = $row;
			$posRecord++;
		}
		// response
		$this->view->iTotalRecords = $total;
		$this->view->iTotalDisplayRecords = $total;
		$this->view->aaData = $data;
		$this->_helper->json($this->view);
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
		foreach ($filterParams as $field => $filter) {
			$filterParams[$field] = trim($filter);
		}
		
		$filters = array ();
		
		if (!empty($filterParams['name'])) {
			$filters[] = array('field' => 'name', 'filter' => '%'.$filterParams['name'].'%', 'operator' => 'LIKE');
		}
				
		return $filters;
	}
}