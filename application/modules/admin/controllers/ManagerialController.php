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
		$formFilter = new Admin_Form_CategoryFilter();
		$formFilter->getElement('nameFilter')->setLabel(_('Name Managerial'));
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
	 * Outputs an XHR response containing all entries in managerials.
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
			$row[] = $managerial->getFullName();
			$row[] = $managerial->getPhone();
			$row[] = $managerial->getLastName();
			$row[] = $managerial->getDateOfBirth();
			$row[] = $managerial->getPhone();
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