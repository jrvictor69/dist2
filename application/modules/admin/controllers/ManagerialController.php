<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_ManagerialController extends App_Controller_Action {
	
	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
	public function init() {
		parent::init();
	}
	
	/**
	 * 
	 * This action shows a paginated list of privileges
	 * @access public
	 */
	public function indexAction() {
		$formFilter = new Admin_Form_ManagerialFilter();
		$this->view->formFilter = $formFilter;
	}
	
	/**
	 * 
	 * This action shows a form in create mode
	 * @access public
	 */
	public function createAction() {
		$this->_helper->layout()->disableLayout();
		
		$form = new Admin_Form_Managerial();
		$form->getElement('userGroupId')->setMultiOptions($this->getUserGroups());
		$form->getElement('sex')->setMultiOptions(array(Model_Person::SEX_MALE => _("Male"), Model_Person::SEX_FEMALE => _("Female")));
		$form->getElement('sex')->setOptions(array('separator' => '')); 
		
        $this->view->form = $form;
	}
	
	/**
	 * 
	 * Creates a new Managerial
	 * @access public
	 */
	public function createSaveAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        $form = new Admin_Form_Managerial();
        $form->getElement('userGroupId')->setMultiOptions($this->getUserGroups());
        $form->getElement('sex')->setMultiOptions(array(Model_Person::SEX_MALE => _("Male"), Model_Person::SEX_FEMALE => _("Female")));
        
        $formData = $this->_request->getPost();
        if ($form->isValid($formData)) {          	
        	try {
           		$managerialMapper = new Model_ManagerialMapper();
               	if (!$managerialMapper->verifyExistIdentityCard($formData['identityCard'])) {
               		$account = new Model_Account();
               		$account->setUsername($formData['username'])
               				->setPassword($formData['password'])
               				->setEmail($formData['email'])
               				->setRole("Managerial")
               				->setAccountType(Model_Account::ACCOUNT_TYPE_MANAGERIAL)
               				;
               				
               		$accountMapper = new Model_AccountMapper();
               		$accountMapper->save($account); 
               		
               		$account = $accountMapper->findLast();
               		
               		$userGroupMapper = new Model_UserGroupMapper();
               		$userGroup = $userGroupMapper->find((int)$formData['userGroupId']);
               		
               		$managerial = new Model_Managerial();
               		$managerial
               				->setUserGroup($userGroup)
               				->setAccount($account)
               				->setFirstName($formData['firstName'])
               				->setLastName($formData['lastName'])
               				->setDateOfBirth(date('Y-m-d H:i:s'))
               				->setPhone($formData['phone'])
               				->setPhonework($formData['phonework'])
               				->setPhonemobil((int)$formData['phonemobil'])
               				->setIdentityCard((int)$formData['identityCard'])
               				->setSex((int)$formData['sex'])
               				->setType(2)               				
               				;
                		
                	$managerialMapper->save($managerial);
                	
                	$this->view->success = TRUE;
                	$this->_messenger->clearMessages();
                    $this->_messenger->addSuccess(_("Managerial saved"));
                    $this->view->message = $this->view->seeMessages();
                } else {
					$this->view->success = FALSE;
					$this->view->identityCard_duplicate = TRUE;
                    $this->_messenger->addError(_("The Managerial already exists"));
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
	 * This action shows the form in update mode for Managerial.
	 * @access public
	 */
	public function updateAction() {
		$this->_helper->layout()->disableLayout();
		$form = new Admin_Form_Managerial();
        $form->getElement('userGroupId')->setMultiOptions($this->getUserGroups());
		$form->getElement('sex')->setMultiOptions(array(Model_Person::SEX_MALE => _("Male"), Model_Person::SEX_FEMALE => _("Female")));
		$form->getElement('sex')->setOptions(array('separator' => ''));
		
        try {
           	$id = $this->_getParam('id', 0);
            $managerialMapper = new Model_ManagerialMapper();
            $managerial = $managerialMapper->find($id);
            if ($managerial != NULL) {//security
            	$account = $managerial->getAccount();
				$form->getElement('firstName')->setValue($managerial->getFirstName());
				$form->getElement('lastName')->setValue($managerial->getLastName());
				$form->getElement('identityCard')->setValue($managerial->getIdentityCard());
				$form->getElement('sex')->setValue($managerial->getSex());
				
				$form->getElement('username')->setValue($account->getUsername());
				$form->getElement('email')->setValue($account->getEmail());
				
				$form->getElement('phone')->setValue($managerial->getPhone());
				$form->getElement('phonework')->setValue($managerial->getPhonework());
				$form->getElement('phonemobil')->setValue($managerial->getPhonemobil());
				$form->getElement('userGroupId')->setValue($managerial->getUserGroup()->getId());
         	} else {
                // response to client
	       		$this->view->success = FALSE;
	            $this->_messenger->addError(_("The requested record was not found."));
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
	 * Updates a managerial
	 * @access public
	 * 1) Get the record to edit
	 * 2) Validate the record was no deleted
	 * 3) Validate the existance of another Managerial with the same identity card.
	 * 4) Saves the changes.
	 */
	public function updateSaveAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$form = new Admin_Form_Managerial();
		$form->getElement('userGroupId')->setMultiOptions($this->getUserGroups());
        $form->getElement('sex')->setMultiOptions(array(Model_Person::SEX_MALE => _("Male"), Model_Person::SEX_FEMALE => _("Female")));
		
     	$formData = $this->getRequest()->getPost();
       	if ($form->isValid($formData)) {
        	try {
                $id = $this->_getParam('id', 0);
                	
                $managerialMapper = new Model_ManagerialMapper();                
                $managerial = $managerialMapper->find($id);
                if ($managerial != NULL) {
//                	if (!$managerialMapper->verifyExistIdentityCard($formData['identityCard']) || $managerialMapper->verifyExistIdAndIdentityCard($id, $formData['identityCard'])) {
                				
	                	$account = $managerial->getAccount();
               			$account->setUsername($formData['username'])
               				->setPassword($formData['password'])
               				->setEmail($formData['email'])
               				->setRole("Managerial")
               				->setAccountType(Model_Account::ACCOUNT_TYPE_MANAGERIAL)
               				;
               				
	               		$accountMapper = new Model_AccountMapper();
	               		$accountMapper->update($account->getId(), $account);
	               		
	               		$userGroupMapper = new Model_UserGroupMapper();
               			$userGroup = $userGroupMapper->find((int)$formData['userGroupId']);
               		
	               		$managerial
	               				->setUserGroup($userGroup)
	               				->setAccount($account)
	               				->setFirstName($formData['firstName'])
	               				->setLastName($formData['lastName'])
	               				->setDateOfBirth(date('Y-m-d H:i:s'))
	               				->setPhone($formData['phone'])
	               				->setPhonework($formData['phonework'])
	               				->setPhonemobil((int)$formData['phonemobil'])
	               				->setIdentityCard((int)$formData['identityCard'])
	               				->setSex((int)$formData['sex'])
	               				->setType(2)               				
	               				;
	                		
	                	$managerialMapper->update($id, $managerial);
                		
	                	$this->view->success = TRUE;
	                	$this->_messenger->clearMessages();
	                   	$this->_messenger->addSuccess(_("Managerial updated"));
	                   	$this->view->message = $this->view->seeMessages();
                	} else {
                		$this->view->success = FALSE;
                		$this->view->identityCard_duplicate = TRUE;
                    	$this->_messenger->addError(_("The Managerial already exists"));
                    	$this->view->message = $this->view->seeMessages();
                	}
//                } else {
//                	$this->view->success = FALSE;
//                    $this->_messenger->addError(_("The Managerial does not exists"));
//                    $this->view->message = $this->view->seeMessages();
//                }
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
	 * Deletes managerials
	 * @access public
	 * @internal
	 * 1) Get the model country
	 * 2) Validate the existance of dependencies
	 * 3) Change the state field or records to delete 
	 */
	public function deleteAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
        $itemIds = $this->_getParam('itemIds', array());
      	if (!empty($itemIds) ) {
        	try {
           		$removeCount = 0;
                foreach ($itemIds as $id) {
                	$managerialMapper = new Model_ManagerialMapper();
	                $managerialMapper->delete($id);
	                
	                $accountMapper = new Model_AccountMapper();
	                $managerial = $managerialMapper->find($id);
	                $accountMapper->delete($managerial->getAccount()->getId());
	                $removeCount++;
                }
                $message = sprintf(ngettext('%d managerial removed.', '%d managerials removed.', $removeCount), $removeCount);
                	
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
	 * Outputs an XHR response containing all entries in managerials.
	 * This action serves as a datasource for the read/index view
	 * @xhrParam int filter_name
	 * @xhrParam int iDisplayStart
	 * @xhrParam int iDisplayLength
	 */
	public function readItemsAction() {
		$sortCol = $this->_getParam('iSortCol_0', 1);
		$sortDirection = $this->_getParam('sSortDir_0', 'asc');

		$filterParams['firstname'] = $this->_getParam('filter_firstname', NULL);
		$filters = $this->getFilters($filterParams);
		
		$start = $this->_getParam('iDisplayStart', 0);
        $limit = $this->_getParam('iDisplayLength', 10);
        $page = ($start + $limit) / $limit;

		$managerialMapper = new Model_ManagerialMapper();
		$managerials = $managerialMapper->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $managerialMapper->getTotalCount($filters);
		
		$posRecord = $start+1;
		$data = array();
		foreach ($managerials as $managerial) {
			$created = new Zend_Date($managerial->getCreated());
			
			$changed = $managerial->getChanged();
			if ($changed != NULL) {
				$changed = new Zend_Date($managerial->getChanged());
				$changed = $changed->toString("dd.MM.YYYY");
			}
			
			$row = array();			
			$row[] = $managerial->getId();
			$row[] = $managerial->getFirstName();
			$row[] = $managerial->getLastName();
			$row[] = $managerial->getPhonemobil();
			$row[] = $managerial->getPhone();
			$row[] = $managerial->getAccount()->getEmail();
			$row[] = '';
			$row[] = $managerial->getUserGroup()->getName();
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
		
		if (!empty($filterParams['firstname'])) {
			$filters[] = array('field' => 'firstName', 'filter' => '%'.$filterParams['firstname'].'%', 'operator' => 'LIKE');
		}
				
		return $filters;
	}
	
	/**
	 * 
	 * Returns the ids and names of user groups
	 * @return array
	 */
	private function getUserGroups() {
		$userGroupMapper = new Model_UserGroupMapper();
		return $userGroupMapper->findAllName();
	}
}