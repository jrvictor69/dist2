<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_NewsController extends App_Controller_Action {
	
	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
	public function init() {
		parent::init();
	}
	
	/**
	 * 
	 * This action shows a paginated list of news
	 * @access public
	 */
	public function indexAction() {
		$formFilter = new Admin_Form_DepartmentFilter();
		$formFilter->getElement('nameFilter')->setLabel(_("Title news"));
		$formFilter->getElement('countryFilter')->setLabel(_("Category"));
		$formFilter->getElement('countryFilter')->setMultiOptions($this->getCategoriesFilter());
		$this->view->formFilter = $formFilter;
	}
	
	/**
	 * 
	 * This action shows a form in create mode on the view 
	 * @access public
	 */
	public function addAction() {
		$form = new Admin_Form_News();
		$form->getElement('categoryId')->setMultiOptions($this->getCategories());
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
	        if ($form->isValid($formData)) {
        		$newsMapper = new Model_NewsMapper();
        		if (!$newsMapper->verifyExistTitle($formData['title'])) {
        			$fileName = $_FILES['imageFile']['name'];

        			$imageFile = $form->getElement('imageFile');
        			try {
		 				$imageFile->receive();
					} catch (Zend_File_Transfer_Exception $e) {
						$e->getMessage();
					}
					// Managerial
					$managerialId = Zend_Auth::getInstance()->getIdentity()->id;
					$managerial = new Model_Managerial();
					$managerial->setId($managerialId);
					
					// Category
					$categoryMapper = new Model_CategoryMapper();
					$category = $categoryMapper->find($formData['categoryId']);
					
					$news = new Model_News();				
					$news
						->setSummary($formData['summary'])
						->setContain($formData['contain'])
						->setFount($formData['fount'])
						->setCategory($category)
						->setManagerial($managerial)
						->setTitle($formData['title'])
						->setImagename($fileName)
						->setCreatedBy($managerialId)
						;
					
					$newsMapper->save($news);
					
					$this->_helper->flashMessenger(array('success' => _("News saved")));
	                $this->_helper->redirector('index', 'news', 'admin', array('type'=>'information'));
        		} else {
        			$this->_helper->flashMessenger(array('error' => _("The News already exists")));
        		}
	     	} else {
	     		$this->_helper->flashMessenger(array('error' => _("The form contains error and is not saved")));
	       	}
		}
		
		$this->view->form = $form;
	}
		
	/**
	 * 
	 * This action shows the form in update mode for News on the view.
	 * @access public
	 */
	public function editAction() {
		$form = new Admin_Form_News();
        $form->getElement('categoryId')->setMultiOptions($this->getCategories());
        $form->getElement('update')->setLabel(_('Update'));
        
        if ($this->_request->isPost()) {
        	$formData = $this->_request->getPost();
	        if ($form->isValid($formData)) {
                $id = $this->_getParam('id', 0);
        		$newsMapper = new Model_NewsMapper();
				$news = $newsMapper->find($id);
            	if ($news != NULL) {//security
            		if (!$newsMapper->verifyExistTitle($formData['title']) || $newsMapper->verifyExistIdAndTitle($id, $formData['title'])) {
            			// Managerial
						$managerialId = Zend_Auth::getInstance()->getIdentity()->id;
						$managerial = new Model_Managerial();
						$managerial->setId($managerialId);
						
						// Category
						$categoryMapper = new Model_CategoryMapper();
						$category = $categoryMapper->find($formData['categoryId']);
						
						$news->setCategory($category)
							->setManagerial($managerial)
							->setSummary($formData['summary'])
							->setFount($formData['fount'])
							->setTitle($formData['title'])
							->setContain($formData['contain'])
							->setImagename("image test")
							->setChangedBy($managerialId)
							;
						
						$newsMapper->update($id, $news);

						$this->_helper->flashMessenger(array('success' => _("News updated")));
						$this->_helper->redirector('index', 'news', 'admin', array('type'=>'information'));
            		} else {
            			$this->_helper->flashMessenger(array('error' => _("The News already exists")));
            		}
                } else {
	               	$this->_helper->flashMessenger(array('error' => _("The News does not exists")));
                }
			} else {
				$this->_helper->flashMessenger(array('error' => _("The form contains error and is not updated")));
	    	}
        } else {
        	$id = $this->_getParam('id', 0);
	        $newsMapper = new Model_NewsMapper();
			$news = $newsMapper->find($id);
	            
	     	if ($news != NULL) {//security
	        	$form->getElement('newsId')->setValue($id);
				$form->getElement('title')->setValue($news->getTitle());
				$form->getElement('summary')->setValue($news->getSummary());
				$form->getElement('contain')->setValue($news->getContain());
				$form->getElement('fount')->setValue($news->getFount());
				$form->getElement('categoryId')->setValue($news->getCategory()->getId());
	        }
        }
       	
        $this->view->form = $form;
	}
		
	/**
	 * 
	 * This action returns content to load the form
	 */
	public function loadAction() {
		try {
			$id = $this->_getParam('id', 0);
            $newsMapper = new Model_NewsMapper();
            $news = $newsMapper->find($id);
          	if ($news != NULL) {//security	
				$this->stdResponse->htmlContent = $news->getContain();
        	} else {
                	
       		}
		} catch (Exception $e) {
			$this->exception($this->view, $e);
		}
		$this->_helper->json($this->stdResponse);
	}
	
	/**
	 * 
	 * Deletes news
	 * @access public
	 * @internal
	 * 1) Get the model news
	 * 2) Validate the existance of dependencies
	 * 3) Change the state field or records to delete 
	 */
	public function deleteAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
        $itemIds = $this->_getParam('itemIds', array());
      	if (!empty($itemIds) ) {
       		$removeCount = 0;
          	foreach ($itemIds as $id) {
           		$newsMapper = new Model_NewsMapper();
	          	$newsMapper->delete($id);
	          	$removeCount++;
         	}
           	$message = sprintf(ngettext('%d news removed.', '%d news removed.', $removeCount), $removeCount);
                	
           	$this->stdResponse->success = TRUE;
          	$this->_messenger->addSuccess(_($message));
      	} else {
        	$this->stdResponse->success = FALSE;
          	$this->_messenger->addNotice(_("Data submitted is empty."));
      	}
        $this->stdResponse->message = $this->view->seeMessages();
        // sends response to client
        $this->_helper->json($this->stdResponse);
	}
	
	/**
	 * 
	 * Outputs an XHR response containing all entries in news.
	 * This action serves as a datasource for the read/index view
	 * @xhrParam int filter_category
	 * @xhrParam int filter_name
	 * @xhrParam int iDisplayStart
	 * @xhrParam int iDisplayLength
	 */
	public function readItemsAction() {
		$sortCol = $this->_getParam('iSortCol_0', 1);
		$sortDirection = $this->_getParam('sSortDir_0', 'asc');

		$filterParams['nameFilter'] = $this->_getParam('filter_name', NULL);
		$filterParams['countryFilter'] = $this->_getParam('filter_category', -1);
		
		$filters = $this->getFilters($filterParams);
		
		$start = $this->_getParam('iDisplayStart', 0);
        $limit = $this->_getParam('iDisplayLength', 10);
        $page = ($start + $limit) / $limit;

		$newsMapper = new Model_NewsMapper();
		$news = $newsMapper->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $newsMapper->getTotalCount($filters);
		
		$posRecord = $start+1;
		$data = array();
		foreach ($news as $information) {
			$created = new Zend_Date($information->getCreated());
			$changed = $information->getChanged();
			if ($changed != NULL) {
				$changed = new Zend_Date($information->getChanged());
				$changed = $changed->toString("dd.MM.YYYY");
			}
			
			$row = array();
			$row[] = $information->getId();
			$row[] = $information->getTitle();
			$row[] = $information->getSummary();
			$row[] = $information->getCategory()->getName();
			$row[] = $information->getImagename();
			$row[] = $information->getNewsdate();
			$row[] = $information->getFount();
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
		foreach ($filterParams as $field => $filter) {
			$filterParams[$field] = trim($filter);
		}
		
		$filters = array ();
		
		// condition when: country != All and name == ''
		if ($filterParams['countryFilter'] != -1 && $filterParams['nameFilter'] == '') {
			$filters[] = array('field' => 'categoryId', 'filter' => $filterParams['countryFilter'], 'operator' => '=' );
			return $filters;
		}

		// condition when: mainGroup != All and name != ''
		if ($filterParams['countryFilter'] != -1 && $filterParams['nameFilter'] != '') {
			$filters[] = array('field' => 'categoryId', 'filter' => $filterParams['countryFilter'], 'operator' => '=' );
			$filters[] = array('field' => 'title', 'filter' => '%'.$filterParams['nameFilter'].'%', 'operator' => 'LIKE');
			return $filters;
		}
		
		if (!empty($filterParams['nameFilter'])) {
			$filters[] = array('field' => 'title', 'filter' => '%'.$filterParams['nameFilter'].'%', 'operator' => 'LIKE');
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
	 * Returns the ids and names of categories
	 * @return array
	 */
	private function getCategories() {
		$categoryMapper = new Model_CategoryMapper();
		return $categoryMapper->fetchAllName();
	}
	
	/**
	 * 
	 * Returns the ids, names and the item "All" of categories
	 */
	private function getCategoriesFilter() {
		$categories = $this->getCategories();
		$data = array();
		$data[-1] = _("All");
		
		foreach ($categories as $key => $value) {
			$data[$key] = $value;
		}
		
		return $data;
	}
}