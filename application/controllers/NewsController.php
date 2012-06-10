<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-t.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class NewsController extends App_Controller_Action {
	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
	public function init() {
		parent::init();
		$response = $this->getResponse();
		$response->insert("sidebar", $this->view->render("sidebar.phtml"));
	}

    public function indexAction() {
//		$this->_helper->redirector("read");
		$this->_helper->redirector('read', NULL, NULL, array('type'=>'news'));
    }
    
	public function jaAction() {
		$this->view->navigation()->getContainer()->findOneBy('id', 'newsja')->setActive(TRUE);
	}
    
	public function readAction() {
		$formFilter = new Form_SearchFilter();
		$this->view->formFilter = $formFilter;
		
		$newsMapper = new Model_NewsMapper();
		$news = $newsMapper->findByCriteria();
		$this->view->news = $news; 
    }
    
	public function resultAction() {
		$this->view->navigation()->getContainer()->findOneBy('id', 'newsja')->setActive(TRUE);
		
		$formFilter = new Form_SearchFilter();
		$this->view->formFilter = $formFilter;
    }
    
	public function singleAction() {
		$this->view->navigation()->getContainer()->findOneBy('id', 'newsja')->setActive(TRUE);
		
    }
    
	/**
	 * 
	 * Outputs an XHR response containing all entries in news.
	 * This action serves as a datasource for the read/index view
	 * @xhrParam int filter_title
	 * @xhrParam int iDisplayStart
	 * @xhrParam int iDisplayLength
	 */
	public function readItemsAction() {
		$sortCol = $this->_getParam('iSortCol_0', 1);
		$sortDirection = $this->_getParam('sSortDir_0', 'asc');

		$filterParams['nameFilter'] = $this->_getParam('filter_name', NULL);
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
			$row[] = $information->getImagename();
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
				
		if (!empty($filterParams['nameFilter'])) {
			$filters[] = array('field' => 'title', 'filter' => '%'.$filterParams['nameFilter'].'%', 'operator' => 'LIKE');
		}
				
		return $filters;
	}
}