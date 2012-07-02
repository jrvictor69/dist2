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

		$id = 5;
		$imageMapper = new Model_ImageDataVaultMapper();
		$imageLogo = $imageMapper->find($id);

		if ($imageLogo->getBinary()) {
			$src = $this->_helper->url('profile-logo', NULL, NULL, array('id' => $imageLogo->getId(), 'timestamp' => time()));
		}
	}

	/**
	 * 
	 * This action shows a form in create mode
	 * @access public
	 */
	public function createAction() {
		$this->_helper->layout()->disableLayout();

		$form = new Admin_Form_ClubPathfinder();
		$this->view->form = $form;
		
		$id = 7;
		$imageMapper = new Model_ImageDataVaultMapper();
		$imageLogo = $imageMapper->find($id);

		if ($imageLogo->getBinary()) {
			$this->view->src = $this->_helper->url('profile-logo', NULL, NULL, array('id' => $imageLogo->getId(), 'timestamp' => time()));
		} else {
			$this->view->src = '/js/lib/ajax-upload/ep.jpg';
		}
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

		$pathfinderRepo = $this->_entityManager->getRepository('Model\ClubPathfinder');
		$pathfinders = $pathfinderRepo->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $pathfinderRepo->getTotalCount($filters);

		$posRecord = $start+1;
		$data = array();
		foreach ($pathfinders as $pathfinder) {
			$changed = $pathfinder->getChanged();
			if ($changed != NULL) {
				$changed = $changed->format('d.m.Y');
			}

			$row = array();			
			$row[] = $pathfinder->getId();
			$row[] = $pathfinder->getName();
			$row[] = $pathfinder->getTextbible();
			$row[] = $pathfinder->getAddress();
			$row[] = $pathfinder->getCreated()->format('d.m.Y');
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
		$this->_helper->viewRenderer->setNoRender(TRUE);

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

	/**
	 * 
	 * Uploads the profile logo
	 * @access public
	 */
	public function uploadLogoAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$memberFileMapper = new Model_MemberFileMapper();

		$fh = fopen($_FILES['myfile']['tmp_name'], 'r');
		$binary = fread($fh, filesize($_FILES['myfile']['tmp_name']));
		fclose($fh);

		$mimeType = $_FILES['myfile']['type'];
		$fileName = $_FILES['myfile']['name'];

		$imageLogo = new Model_ImageDataVault();
		$imageLogo->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);

		$imageDataVaultMapper = new Model_ImageDataVaultMapper();
		$imageDataVaultMapper->save($imageLogo);

		if ($imageLogo->getBinary()) {
			echo $this->_helper->url('profile-logo', NULL, NULL, array('id' => $imageLogo->getId(), 'timestamp' => time()));
		} else {
			echo "/js/lib/ajax-upload/ep.jpg";
		}
	}

	/**
	 *
	 * Sends the binary file vault to the user agent.
	 * @return void
	 */
	public function profileLogoAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$id = (int)$this->_getParam('id', 0);

		$imageMapper = new Model_ImageDataVaultMapper();
		$imageLogo = $imageMapper->find($id);

		if ($imageLogo->getBinary()) {
			$this->_response
			//No caching
				->setHeader('Pragma', 'public')
				->setHeader('Expires', '0')
				->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
				->setHeader('Cache-Control', 'private')
				->setHeader('Content-type', $imageLogo->getMimeType())
				->setHeader('Content-Transfer-Encoding', 'binary')
				->setHeader('Content-Length', strlen($imageLogo->getBinary()));

			echo $imageLogo->getBinary();
		} else {
			$this->_response->setHttpResponseCode(404);
		}
	}
}