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
			$row[] = $managerial->getName();
			$row[] = $managerial->getFirstName();
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
}