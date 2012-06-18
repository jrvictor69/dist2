<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_PathfinderController extends App_Controller_Action {
		
	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
	public function init() {
		parent::init();
	}
	
    public function indexAction() {
		$this->_helper->redirector('read');
    }
    
    public function readAction() {
    	$formFilter = new Admin_Form_SearchFilter();
		$formFilter->getElement('nameFilter')->setLabel(_("Name category"));
		$this->view->formFilter = $formFilter;
	}
	
	/**
	 * 
	 * This action shows a form in create mode
	 * @access public
	 */
	public function createAction() {
		$this->_helper->layout()->disableLayout();
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
	
	public function uploadAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$target_path = "image/upload/";
		// Set the new path with the file name
		$target_path = $target_path . basename( $_FILES['myfile']['name']); 
		// Move the file to the upload folder
		if(move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) {
			// print the new image path in the page, and this will recevie the javascripts 'response' variable
    		echo "/".$target_path;
		} else{
			// Set default the image path if any error in upload.
    		echo "default.jpg";
		}
	}
}