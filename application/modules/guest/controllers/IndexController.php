<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Guest_IndexController extends App_Controller_Action {

	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
	public function init() {
		parent::init();
	}
	
	/**
	 * 
	 * This action shows a paginated list of categories
	 * @access public
	 */
	public function indexAction() {
		$formFilter = new Admin_Form_SearchFilter();
		$formFilter->getElement('nameFilter')->setLabel(_("Name country"));
		$this->view->formFilter = $formFilter;
	}
	
	/**
	 * 
	 * This action shows a form in create mode
	 * @access public
	 */
	public function createAction() {
		$this->_helper->layout()->disableLayout();
		
		$form = new Guest_Form_Country();
        $this->view->form = $form;
	}
	
	/**
	 * 
	 * Creates a new Country is called by the Xhr Form
	 * @access public
	 */
	public function createSaveAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        $form = new Admin_Form_Country();
                
        $formData = $this->getRequest()->getPost();
        if ($form->isValid($formData)) {
        	try {
                $countryMapper = new Model_CountryMapper();
                if (!$countryMapper->verifyExistName($formData['name'])) {
                	$country = new Model_Country($formData);
                	$countryMapper->save($country);
                	
                	$this->stdResponse->success = TRUE;
                    $this->stdResponse->message = _("Country saved");
                } else {
					$this->stdResponse->success = FALSE;
					$this->stdResponse->name_duplicate = TRUE;
                    $this->stdResponse->message = _("The Country already exists");                			
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
	 * This action shows the form in update mode for Country.
	 * @access public
	 */
	public function updateAction() {
		$this->_helper->layout()->disableLayout();
		$form = new Admin_Form_Country();
        
        try {
           	$id = $this->_getParam('id', 0);
            $countryMapper = new Model_CountryMapper();
            $country = $countryMapper->find($id);
            if ($country != NULL) {//security
				$form->getElement('name')->setValue($country->getName());
				$form->getElement('description')->setValue($country->getDescription());
         	} else {
                // response to client
	       		$this->stdResponse->success = FALSE;
	            $this->stdResponse->message = _("The requested record was not found.");
	            $this->_helper->json($this->stdResponse);
           	}
        } catch (Exception $e) {
        	$this->exception($this->view, $e);
            $this->_helper->json($this->view);
        }
        
        $this->view->form = $form;
	}
	
	/**
	 * 
	 * Updates a Country
	 * @access public
	 * 1) Get the record to edit
	 * 2) Validate the record was no deleted
	 * 3) Validate the existance of another Country with the same name.
	 * 4) Saves the changes.
	 */
	public function updateSaveAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$form = new Admin_Form_Country();
		
     	$formData = $this->getRequest()->getPost();
       	if ($form->isValid($formData)) {
        	try {
                $id = $this->_getParam('id', 0);
                	
                $countryMapper = new Model_CountryMapper();
                $country = $countryMapper->find($id);
                if ($country != NULL) {
                	if (!$countryMapper->verifyExistName($formData['name']) || $countryMapper->verifyExistIdAndName($id, $formData['name'])) {
	                	$country->setName($formData['name'])
	                			->setDescription($formData['description']);
	                			
	                	$countryMapper->update($id, $country);
	                		
	                	$this->stdResponse->success = TRUE;
	                   	$this->stdResponse->message = _("Country updated");
                	} else {
                		$this->stdResponse->success = FALSE;
                		$this->stdResponse->name_duplicate = TRUE;
                    	$this->stdResponse->message = _("The Country already exists");
                	}
                } else {
                	$this->stdResponse->success = FALSE;
                    $this->stdResponse->message = _("The Country does not exists");
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
	 * Deletes countries
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
                	$countryMapper = new Model_CountryMapper();
	                $countryMapper->delete($id);
	                $removeCount++;
                }
                $message = sprintf(ngettext('%d country removed.', '%d countries removed.', $removeCount), $removeCount);
                	
                $this->stdResponse->success = TRUE;
                $this->stdResponse->message = _($message);
          	} catch (Exception $e) {
               	$this->exception($this->view, $e);
            }
     	} else {
        	$this->stdResponse->success = FALSE;
            $this->stdResponse->message = _("Data submitted is empty.");
      	}
        // send response to client
        $this->_helper->json($this->stdResponse);
	}
	
	/**
	 * 
	 * Outputs an XHR response containing all entries in countries.
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

		$countryMapper = new Model_CountryMapper();
		$countries = $countryMapper->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $countryMapper->getTotalCount($filters);
		
		$posRecord = $start+1;
		$data = array();
		foreach ($countries as $country) {
			$row = array();			
			$row[] = $country->getId();
			$row[] = $country->getName();
			$row[] = $country->getDescription();
			$row[] = $country->getCreated();
			$row[] = $country->getChanged();
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
		
		if (!empty($filterParams['name'])) {
			$filters[] = array('field' => 'name', 'filter' => '%'.$filterParams['name'].'%', 'operator' => 'LIKE');
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
	
	public function aboutAction() {
		
	}
	
	public function contactAction() {
		
	}
}