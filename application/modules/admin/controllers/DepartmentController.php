<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_DepartmentController extends App_Controller_Action {
	
	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
	public function init() {
		parent::init();
	}
	
	/**
	 * 
	 * This action shows a paginated list of departments
	 * @access public
	 */
	public function indexAction() {
		$countriesNames = $this->getCountriesFilter();

		$formFilter = new Admin_Form_DepartmentFilter();
		$formFilter->getElement('countryFilter')->setMultiOptions($countriesNames);
		$this->view->formFilter = $formFilter;
	}
	
	/**
	 * 
	 * This action shows a form in create mode
	 * @access public
	 */
	public function createAction() {
		$this->_helper->layout()->disableLayout();
		
		$form = new Admin_Form_Department();
		$form->getElement('country')->setMultiOptions($this->getCountries());
        $this->view->form = $form;
	}
	
	/**
	 * 
	 * Creates a new Department is called by the Xhr Form
	 * @access public
	 */
	public function createSaveAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        $form = new Admin_Form_Department();
       	$form->getElement('country')->setMultiOptions($this->getCountries());
       	            
        $formData = $this->getRequest()->getPost();
        if ($form->isValid($formData)) {
            try {
               	$departmentMapper = new Model_DepartmentMapper();
               	
               	$countryMapper = new Model_CountryMapper();
               	$country = $countryMapper->find((int)$formData['country']);
               	if (!$departmentMapper->verifyExistNameByCountry($formData['name'], $country)) {
               		$department = new Model_Department();
               		$department->setName($formData['name'])
               					->setDescription($formData['description'])
      							->setCountry($country);
      								
               		$departmentMapper->save($department);
                	
               		$this->stdResponse->success = TRUE;
               		$this->stdResponse->message = _("Department saved");	
               	} else {
					$this->stdResponse->success = FALSE;
					$this->stdResponse->name_duplicate = TRUE;
					$this->stdResponse->message = _("The Department already exists");                			
               	}
          	} catch (Exception $e) {
                $this->exception($this->view, $e);
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
	 * This action shows the form in update mode for Department.
	 * @access public
	 */
	public function updateAction() {
		$this->_helper->layout()->disableLayout();
		
		$form = new Admin_Form_Department();
        $form->getElement('country')->setMultiOptions($this->getCountries());
        
        try {
           	$id = $this->_getParam('id', 0);
            $departmentMapper = new Model_DepartmentMapper();
            $department = $departmentMapper->find($id);
            if ($department != NULL) {//security
				$form->getElement('name')->setValue($department->getName());
				$form->getElement('description')->setValue($department->getDescription());
				$form->getElement('country')->setValue($department->getCountry()->getId());
            } else {
            	// response to client
	       		$this->stdResponse->success = FALSE;
	            $this->stdResponse->message = _("The requested record was not found.");
	            $this->_helper->json($this->stdResponse);
          	}
        } catch (Exception $e) {
        	$this->exception($this->view, $e);
            $this->_helper->json($this->stdResponse);
        }
        
        $this->view->form = $form;
	}
	
	/**
	 * 
	 * Updates a Department is called by the Xhr Form
	 * @access public
	 * 1) Get the record to edit
	 * 2) Validate the record was no deleted
	 * 3) Validate the existance of another Department with the same name.
	 * 4) Saves the changes.
	 */
	public function updateSaveAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$form = new Admin_Form_Department();
		$form->getElement('country')->setMultiOptions($this->getCountries());
		
        $formData = $this->getRequest()->getPost();
        if ($form->isValid($formData)) {
        	try {
               	$id = $this->_getParam('id', 0);
                	
               	$departmentMapper = new Model_DepartmentMapper();
               	$department = $departmentMapper->find($id);
               	if ($department != NULL) {// security
               		$countryMapper = new Model_CountryMapper();
	               	$country = $countryMapper->find((int)$formData['country']);
               		if (!$departmentMapper->verifyExistNameByCountry($formData['name'], $country) || $departmentMapper->verifyExistIdAndNameByCountry($id, $formData['name'], $country)) {		
	               		$department->setName($formData['name'])
	               				->setDescription($formData['description'])
	               				->setCountry($country);
	                			
	               		$departmentMapper->update($id, $department);
	                		
	               		$this->stdResponse->success = TRUE;
	                   	$this->stdResponse->message = _("Department updated");
	              	} else {
                   		$this->stdResponse->success = FALSE;
                		$this->stdResponse->name_duplicate = TRUE;
                    	$this->stdResponse->message = _("The Department already exists");
                   	}
               	} else {
               		$this->stdResponse->success = FALSE;
                    $this->stdResponse->message = _("The Department does not exists");
               	}
         	} catch (Exception $e) {
            	$this->exception($this->view, $e);
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
	 * Deletes depatments
	 * @access public
	 * @internal
	 * 1) Gets the model department
	 * 2) Validates the existance of dependencies
	 * 3) Changes the state field or records to delete 
	 */
	public function deleteAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
        $itemIds = $this->_getParam('itemIds', array());
      	if (!empty($itemIds) ) {
        	try {
           		$removeCount = 0;
                foreach ($itemIds as $id) {
                	$departmentMapper = new Model_DepartmentMapper();
	                $departmentMapper->delete($id);
	                $removeCount++;
                }
                $message = sprintf(ngettext('%d department removed.', '%d departments removed.', $removeCount), $removeCount);
                	
                $this->stdResponse->success = TRUE;
                $this->stdResponse->message = _($message);
          	} catch (Exception $e) {
               	$this->exception($this->view, $e);
            }
     	} else {
        	$this->stdResponse->success = FALSE;
            $this->stdResponse->message = _("Data submitted is empty.");
      	}
        // sends response to client
        $this->_helper->json($this->stdResponse);
	}
	
	/**
	 * 
	 * Outputs an XHR response containing all entries in departments.
	 * This action serves as a datasource for the read/index view
	 * @xhrParam int filter_country
	 * @xhrParam int filter_name
	 * @xhrParam int iDisplayStart
	 * @xhrParam int iDisplayLength
	 */
	public function readItemsAction() {
		$sortCol = $this->_getParam('iSortCol_0', 1);
		$sortDirection = $this->_getParam('sSortDir_0', 'asc');

		$filterParams['nameFilter'] = $this->_getParam('filter_name', NULL);
		$filterParams['countryFilter'] = $this->_getParam('filter_country', -1);
		
		$filters = $this->getFilters($filterParams);
		
		$start = $this->_getParam('iDisplayStart', 0);
        $limit = $this->_getParam('iDisplayLength', 10);
        $page = ($start + $limit) / $limit;

		$deparmentMapper = new Model_DepartmentMapper();
		$departments = $deparmentMapper->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $deparmentMapper->getTotalCount($filters);
		
		$posRecord = $start+1;
		$data = array();
		foreach ($departments as $department) {
			$row = array();			
			$row[] = $department->getId();
			$row[] = $department->getName();
			$row[] = $department->getDescription();
			$row[] = $department->getCountry()->getName();
			$row[] = $department->getCreated();
			$row[] = $department->getChanged();
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
		foreach ($filterParams as $field => $filter) {
			$filterParams[$field] = trim($filter);
		}
		
		$filters = array ();
		
		// condition when: country != All and name == ''
		if ($filterParams['countryFilter'] != -1 && $filterParams['nameFilter'] == '') {
			$filters[] = array('field' => 'countryId', 'filter' => $filterParams['countryFilter'], 'operator' => '=' );
			return $filters;
		}

		// condition when: mainGroup != All and name != ''
		if ($filterParams['countryFilter'] != -1 && $filterParams['nameFilter'] != '') {
			$filters[] = array('field' => 'countryId', 'filter' => $filterParams['countryFilter'], 'operator' => '=' );
			$filters[] = array('field' => 'name', 'filter' => '%'.$filterParams['nameFilter'].'%', 'operator' => 'LIKE');
			return $filters;
		}
		
		if (!empty($filterParams['nameFilter'])) {
			$filters[] = array('field' => 'name', 'filter' => '%'.$filterParams['nameFilter'].'%', 'operator' => 'LIKE');
		}
				
		return $filters;
	}
	
	/**
	 * 
	 * Verifies if it exist some error with the application
	 * @param Zend_View $view
	 * @param Exception $e
	 */
	private function exception(Zend_View $view, Exception $e) {
		$this->_logger->err($e->getMessage());
        // response to client
      	$view->success = FALSE;
        if ($e->getCode() == Model_EnumErrorType::APPLICATION)
        	$this->_messenger->addError($e->getMessage());
   		elseif ($e->getCode() == Model_EnumErrorType::DUP_NAME)
        	$this->_messenger->addError('name_warning');
    	else
        	$this->_messenger->addError(_("An error occurred while processing the data. <br/> Please Try again."));
       	$view->message = $view->seeMessages();
	}
	
	/**
	 * 
	 * Returns the ids and names of countries
	 * @return array
	 */
	private function getCountries() {
		$countryMapper = new Model_CountryMapper();
		return $countryMapper->findAllName();
	}
	
	/**
	 * 
	 * Returns the ids, names and the item "All" of countries
	 */
	private function getCountriesFilter() {
		$countries = $this->getCountries();
		$data = array();
		$data[-1] = _("All");
		
		foreach ($countries as $key => $value) {
			$data[$key] = $value;
		}
		
		return $data;
	}
}