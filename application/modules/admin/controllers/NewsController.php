<?php
 
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
		$countriesNames = $this->getCategoriesFilter();

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
		
		$form = new Admin_Form_News();
		$form->getElement('categoryId')->setMultiOptions($this->getCategories());
        $this->view->form = $form;
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
			$row = array();
			$row[] = $information->getId();
			$row[] = $information->getTitle();
			$row[] = $information->getSummary();
			$row[] = $information->getCategory()->getName();
			$row[] = $information->getCreated();
			$row[] = $information->getChanged();
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
		$data[-1] = "All";
		
		foreach ($categories as $key => $value) {
			$data[$key] = $value;
		}
		
		return $data;
	}
}