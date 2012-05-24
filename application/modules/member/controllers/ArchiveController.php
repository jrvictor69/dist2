<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-t.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Member_ArchiveController extends App_Controller_Action {
	
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
	 * This action shows a paginated list of archives
	 * @access public
	 */
	public function readAction() {
		$formFilter = new Admin_Form_SearchFilter();
		$formFilter->getElement('nameFilter')->setLabel(_("Name archive"));
		$this->view->formFilter = $formFilter;
	}
	
	/**
	 * 
	 * This action shows a form in create mode
	 * @access public
	 */
	public function createAction() {
		$this->_helper->layout()->disableLayout();
		
		$form = new Member_Form_Archive();
		$form->setAction($this->_helper->url("save"));
		$this->view->form = $form;
	}
	
	/**
	 * 
	 * Creates a new Archive
	 * @access public
	 */
	public function saveAction() {
		if ($this->_request->isPost()) {
			$form = new Member_Form_Archive();
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				$memberFileMapper = new Model_MemberFileMapper();
				if (!$memberFileMapper->verifyExistName($formData['name'])) {
					$accountMapper = new Model_AccountMapper();
					$account = $accountMapper->find(11);
					
					$fh = fopen($_FILES['file']['tmp_name'], 'r');
					$binary = fread($fh, filesize($_FILES['file']['tmp_name']));
					$binary = addslashes($binary);
					fclose($fh);
					
					$mimeType = $_FILES['file']['type'];
					$fileName = $_FILES['file']['name'];

					$dataVault = new Model_DataVault();
					$dataVault->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);

					$archive = new Model_MemberFile();
					$archive->setName($formData['name'])
							->setNote($formData['note'])
							->setCreatedBy($account)
							->setFile($dataVault);

					$memberFileMapper->save($archive);

					$this->_helper->flashMessenger(array('success' => _("Archive saved")));
					$this->_helper->redirector('read', 'Archive', 'member', array('type'=>'log'));								            
				}
			}
		}
	}
	
	/**
	 * 
	 * Downloads the Archives
	 * @access public
	 */
	public function downloadAction() {
//		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

//		$dispatcher = Front::getInstance()->getDispatcher();
		$dispatcher = $this->getFrontController()->getInstance()->getDispatcher();
		$id = (int)$this->_getParam('id', 0);
    	 
		$archiveMapper = new Model_MemberFileMapper();
		$archive = $archiveMapper->find($id);

   	 	$file = $archive->getFile();
    	if ($file->getBinary()) {
    		$this->_response
    			//For downloading
    			->setHeader('Content-type', $file->getMimeType())
				->setHeader('Content-Disposition', sprintf('attachment; filename="%s"', $file->getFilename()))
				->setHeader('Content-Transfer-Encoding', 'binary')
				->setHeader('Content-Length', strlen($file->getBinary()))
				// IE headers to make sure it will download it in https
				->setHeader('Expires', '0', TRUE)
				->setHeader('Cache-Control', 'private', TRUE)
				->setHeader('Pragma', 'cache');
    		echo $file->getBinary();
    	} else {
    		$this->_response->setHttpResponseCode(404);
    	}
	}
	
	/**
	 * 
	 * Outputs an XHR response containing all entries in archives.
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

		$memberFileMapper = new Model_MemberFileMapper();
		$archives = $memberFileMapper->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $memberFileMapper->getTotalCount($filters);
		
		$posRecord = $start+1;
		$data = array();
		foreach ($archives as $archive) {
			$created = new Zend_Date($archive->getCreated());
			$changed = $archive->getChanged();
			if ($changed != NULL) {
				$changed = new Zend_Date($archive->getChanged());
				$changed = $changed->toString("dd.MM.YYYY");
			}
			
			$row = array();			
			$row[] = $archive->getId();
			$row[] = $archive->getName();
			$row[] = $archive->getNote();
			$row[] = $archive->getFile()->getFilename();
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
		if (empty($filterParams)) {
			return array();
		}
		
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