<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_UserGroupController extends App_Controller_Action {
	
	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
	public function init() {
		
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
	 * This action shows a paginated list of user groups
	 * @access public
	 */
	public function readAction() {
		$formFilter = new Admin_Form_CategoryFilter();
		$formFilter->getElement('nameFilter')->setLabel(_('Name User Group'));
		$this->view->formFilter = $formFilter;
	}
	
	/**
	 * 
	 * This action shows a form in create mode
	 * @access public
	 */
	public function createAction() {
		$this->_helper->layout()->disableLayout();
		
		$form = new Admin_Form_UserGroup();
        $this->view->form = $form;
	}
	
	/**
	 * 
	 * Creates a new User group
	 * @access public
	 */
	public function createSaveAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        $form = new Admin_Form_UserGroup();
        
        $formData = $this->_request->getPost();
        if ($form->isValid($formData)) {          	
        	try {
           		$userGroupMapper = new Model_UserGroupMapper();
               	if (!$userGroupMapper->verifyExistName($formData['name'])) {
               		$userGroup = new Model_UserGroup($formData);
                	$userGroup->setCreatedBy(Zend_Auth::getInstance()->getIdentity()->id);
                		
                	$userGroupMapper->save($userGroup);
                	
                	$this->view->success = TRUE;
                	$this->_messenger->clearMessages();
                    $this->_messenger->addSuccess(_("User group saved"));
                    $this->view->message = $this->view->seeMessages();
                } else {
					$this->view->success = FALSE;
                    $this->_messenger->addError(_("The User group already exists"));
                    $this->view->message = $this->view->seeMessages();                			
                }
          	} catch (Exception $e) {
            	$this->exception($this->view, $e);
           	}
     	} else {
			$this->view->success = FALSE;
			$this->_messenger->addError(implode("<br/>", $form->getMessages('name')));
			$this->view->message = $this->view->seeMessages();
       	}
        // send response to client
        $this->_helper->json($this->view);
	}
	
	/**
	 * 
	 * This action shows the form in update mode for User group.
	 * @access public
	 */
	public function updateAction() {
		$this->_helper->layout()->disableLayout();
		$form = new Admin_Form_UserGroup();
        
        try {
        	$id = $this->_getParam('id', 0);
            $userGroupMapper = new Model_UserGroupMapper();
            $userGroup = $userGroupMapper->find($id);
            if ($userGroup != NULL) {//security
				$form->getElement('name')->setValue($userGroup->getName());
				$form->getElement('description')->setValue($userGroup->getDescription());
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
	 * Updates a User group
	 * @access public
	 * 1) Gets the record to edit
	 * 2) Validates the record was no deleted
	 * 3) Validates the existance of another User group with the same name.
	 * 4) Saves the changes.
	 */
	public function updateSaveAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$form = new Admin_Form_UserGroup();
		
		$formData = $this->_request->getPost();
        if ($form->isValid($formData)) {
            try {
                $id = $this->_getParam('id', 0);
                	
                $userGroupMapper = new Model_UserGroupMapper();
                $userGroup = $userGroupMapper->find($id);
                if ($userGroup != NULL) {
               		$userGroup->setName($formData['name'])
                			->setDescription($formData['description'])
                			->setChangedBy(Zend_Auth::getInstance()->getIdentity()->id);
                			
                	$userGroupMapper->update($id, $userGroup);
                		
                	$this->view->success = TRUE;
                	$this->_messenger->clearMessages();
                    $this->_messenger->addSuccess(_("User group updated"));
                    $this->view->message = $this->view->seeMessages();
                }
        	} catch (Exception $e) {
                $this->exception($this->view, $e);
         	}
		} else {
            $this->view->success = FALSE;
			$this->_messenger->addError(implode("<br/>", $form->getMessages('name')));
			$this->view->message = $this->view->seeMessages();
    	}
        // send response to client
        $this->_helper->json($this->view);
	}
	
	/**
	 * 
	 * Deletes user groups
	 * @access public
	 * @internal
	 * 1) Gets the model user group
	 * 2) Validates the existance of dependencies
	 * 3) Changes the state field or records to delete
	 */
	public function deleteAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
        $itemIds = $this->_getParam('itemIds', array());
       	if (!empty($itemIds) ) {
        	try {
           		$userGroupMapper = new Model_UserGroupMapper();
           		$removeCount = 0;
               	foreach ($itemIds as $id) {              		
	                $userGroupMapper->delete($id);
	                $removeCount++;
                }
                $message = sprintf(ngettext('%d user group removed.', '%d user groups removed.', $removeCount), $removeCount);
                	
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
	 * Outputs an XHR response containing all entries in user groups.
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

		$userGroupMapper = new Model_UserGroupMapper();
		$userGroups = $userGroupMapper->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $userGroupMapper->getTotalCount($filters);

		$posRecord = $start+1;
		$data = array();
		foreach ($userGroups as $userGroup) {
			$row = array();			
			$row[] = $userGroup->getId();
			$row[] = $userGroup->getName();
			$row[] = $userGroup->getDescription();
			$row[] = $userGroup->getCreated();
			$row[] = $userGroup->getChanged();
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