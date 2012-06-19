<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_PositionController extends App_Controller_Action {
	
	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
	public function init() {
		parent::init();
	}

	/**
	 * 
	 * In case no action redirects the action read will be executed
	 * @access public
	 */
	public function indexAction() {
		$this->_helper->redirector('read');
	}
	
	/**
	 * 
	 * This action shows a paginated list of positions
	 * @access public
	 */
	public function readAction() {
		$formFilter = new Admin_Form_SearchFilter();
		$formFilter->getElement('nameFilter')->setLabel(_("Name position"));
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
	 * Creates a new Position
	 * @access public
	 */
	public function createSaveAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        $form = new Admin_Form_Position();
        
        $formData = $this->getRequest()->getPost();
      	if ($form->isValid($formData)) {
			$positionMapper = new Model_PositionMapper();
			if (!$positionMapper->verifyExistName($formData['name'])) {
				$position = new Model_Position($formData);
				$position->setCreatedBy(Zend_Auth::getInstance()->getIdentity()->id);
				$positionMapper->save($position);
				$this->stdResponse->success = TRUE;
				$this->stdResponse->message = _("Position saved");	
			} else {
				$this->stdResponse->success = FALSE;
				$this->stdResponse->name_duplicate = TRUE;
				$this->stdResponse->message = _("The Position already exists");
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
	 * Outputs an XHR response containing all entries in positions.
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

		$positionMapper = new Model_PositionMapper();
		$positions = $positionMapper->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $positionMapper->getTotalCount($filters);
		
		$posRecord = $start+1;
		$data = array();
		foreach ($positions as $position) {
			$row = array();			
			$row[] = $position->getId();
			$row[] = $position->getName();
			$row[] = $position->getDescription();
			$row[] = $position->getCreated();
			$row[] = $position->getChanged();
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
	
	/**
	 *
	 * Outputs an XHR response, loads the names of the positions.
	 */
	public function autocompleteAction() {
		$filterParams['name'] = $this->_getParam('name_auto', NULL);
		$filters = $this->getFilters($filterParams);

		$positionMapper = new Model_PositionMapper();
		$this->stdResponse->items = $positionMapper->findByCriteriaOnlyName($filters);
		$this->_helper->json($this->stdResponse);
	}
}