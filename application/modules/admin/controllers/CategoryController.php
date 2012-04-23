<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_CategoryController extends App_Controller_Action {
	
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
		$formFilter = new Admin_Form_CategoryFilter();
		$this->view->formFilter = $formFilter;
	}
	
	/**
	 * 
	 * This action shows a form in create mode
	 * @access public
	 */
	public function createAction() {
		$this->_helper->layout()->disableLayout();
		
		$form = new Admin_Form_Category();
        $this->view->form = $form;
	}
	
	/**
	 * 
	 * Creates a new Category
	 * @access public
	 */
	public function createSaveAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        $form = new Admin_Form_Category();
                
        if ($this->_request->isPost()) {
        	$formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {            	
                try {
                	$categoryMapper = new Model_CategoryMapper();
                	if (!$categoryMapper->verifyExistName($formData['name'])) {
                		$category = new Model_Category($formData);
                		$category->setCreatedBy(Zend_Auth::getInstance()->getIdentity()->id);
                		
                		$categoryMapper->save($category);
                	
                		$this->view->success = TRUE;
                		$this->_messenger->clearMessages();
                    	$this->_messenger->addSuccess(_("Category saved"));
                    	$this->view->message = $this->view->seeMessages();	
                	} else {
						$this->view->success = FALSE;
                    	$this->_messenger->addError(_("The Category already exists"));
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
        } else {
        	$this->view->success = FALSE;
        	$this->_messenger->addNotice(_("Data submitted were not processed."));        	
        	$this->view->message = $this->view->seeMessages();
        }
        // send response to client
        $this->_helper->json($this->view);
	}
	
	/**
	 * 
	 * This action shows the form in update mode for Category.
	 * @access public
	 */
	public function updateAction() {
		$this->_helper->layout()->disableLayout();
		$form = new Admin_Form_Category();
        
        if ($this->_request->isPost()) {
        	try {
           		$id = $this->_getParam('id', 0);
            	$categoryMapper = new Model_CategoryMapper();
            	$category = $categoryMapper->find($id);
                if ($category) {//security
					$form->getElement('name')->setValue($category->getName());
					$form->getElement('description')->setValue($category->getDescription());
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
        }
        
        $this->view->form = $form;
	}
	
	/**
	 * 
	 * Updates a Category
	 * @access public
	 * 1) Get the record to edit
	 * 2) Validate the record was no deleted
	 * 3) Validate the existance of another Category with the same name.
	 * 4) Saves the changes.
	 */
	public function updateSaveAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$form = new Admin_Form_Category();
		
        if ($this->_request->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                try {
                	$id = $this->_getParam('id', 0);
                	
                	$categoryMapper = new Model_CategoryMapper();
                	$category = $categoryMapper->find($id);
                	if ($category) {
                		$category->setName($formData['name'])
                				->setDescription($formData['description'])
                				->setChangedBy(Zend_Auth::getInstance()->getIdentity()->id);
                			
                		$categoryMapper->update($id, $category);
                		
                		$this->view->success = TRUE;
                		$this->_messenger->clearMessages();
                    	$this->_messenger->addSuccess(_("Category updated"));
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
        } else {
        	$this->view->success = FALSE;
        	$this->_messenger->addNotice(_("Data submitted were not processed."));        	
        	$this->view->message = $this->view->seeMessages();
        }
        // send response to client
        $this->_helper->json($this->view);
	}
	
	/**
	 * 
	 * Deletes categories
	 * @access public
	 * @internal
	 * 1) Get the model category
	 * 2) Validate the existance of dependencies
	 * 3) Change the state field or records to delete 
	 */
	public function deleteAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
        if ($this->_request->isPost()) {
        	$itemIds = $this->_getParam('itemIds', array());
            if (!empty($itemIds) ) {
            	try {
            		$removeCount = 0;
                	foreach ($itemIds as $id) {
                		$categoryMapper = new Model_CategoryMapper();
	                	$categoryMapper->delete($id);
	                	$removeCount++;
                	}
                	$message = sprintf(ngettext('%d category removed.', '%d categories removed.', $removeCount), $removeCount);
                	
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
        } else {
        	$this->view->success = FALSE;
        	$this->_messenger->addNotice(_("Data submitted were not processed."));
        	$this->view->message = $this->view->seeMessages();
        }
        // send response to client
        $this->_helper->json($this->view);
	}
	
	/**
	 * 
	 * Outputs an XHR response containing all entries in categories.
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

		$categoryMapper = new Model_CategoryMapper();
		$categories = $categoryMapper->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $categoryMapper->getTotalCount($filters);
				
//		$this->view->sEcho = intval($_GET['sEcho']);

		$posRecord = $start+1;
		$data = array();
		foreach ($categories as $category) {
			$row = array();			
			$row[] = $category->getId();
			$row[] = $category->getName();
			$row[] = $category->getDescription();
			$row[] = $category->getCreated();
			$row[] = $category->getChanged();
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
}