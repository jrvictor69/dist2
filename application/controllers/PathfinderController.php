<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */


use Doctrine\DBAL\DriverManager;
class PathfinderController extends App_Controller_Action {

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
    }

	public function alasorionAction() {
		$pictureMapper = new Model_PictureMapper();
		$pictures = $pictureMapper->findByCriteria();
		$this->view->pictures = $pictures;
	}

	public function estrelladavidAction() {
	}

	public function leonjudaAction() {
		$pictureRepo = $this->_entityManager->getRepository('Model\Picture');
		$pictures = $pictureRepo->findAll();
		$this->view->pictures = $pictures;
	}

	public function profileAction() {
		$id = $this->_request->getParam('pageId');
		$this->activePage($id);

		$club = $this->getClubPathfinderById($id);
		$this->view->title = sprintf(_('Club %s'), $club->getName());
		$this->view->subTitle = sprintf(_('Profile of the Club %s'), $club->getName());
	}

	public function gallerypictureAction() {
		$id = $this->_request->getParam('pageId');
		$this->activePage($id);

		$club = $this->getClubPathfinderById($id);
		$this->view->title = sprintf(_('Club %s'), $club->getName());
		$this->view->subTitle = sprintf(_('Gallery of pictures of the Club %s'), $club->getName());
	}

	public function galleryvideoAction() {
		$id = $this->_request->getParam('pageId');
		$this->activePage($id);

		$club = $this->getClubPathfinderById($id);
		$this->view->title = sprintf(_('Club %s'), $club->getName());
		$this->view->subTitle = sprintf(_('Gallery of videos of the Club %s'), $club->getName());
	}

	public function directiveAction() {
		$id = $this->_request->getParam('pageId');
		$this->activePage($id);

		$club = $this->getClubPathfinderById($id);
		$this->view->title = sprintf(_('Club %s'), $club->getName());
		$this->view->subTitle = sprintf(_('Directive of the Club %s'), $club->getName());
	}

	public function memberAction() {
		$id = $this->_request->getParam('id');
		$this->activePage($id);

		$club = $this->getClubPathfinderById($id);
		$this->view->title = sprintf(_('Club %s'), $club->getName());
		$this->view->subTitle = sprintf(_('Members of the Club %s'), $club->getName());
	}

	public function escuelasabaticaAction() {
		$this->view->title = sprintf(_('Club %s'), 'Escuela Sabatica');
		$this->view->subTitle = sprintf(_('Members of the Escuela Sabatica %s'), 'Nuevo Palmar');
	}

	/**
	 *
	 * This action shows the form to view the profile of the Directive.
	 * @access public
	 */
	public function updateAction() {
		$this->_helper->layout()->disableLayout();

		$form = new Form_DirectiveProfile();
// 		$form->getElement('sex')->setMultiOptions($this->getGenders());
// 		$form->getElement('club')->setMultiOptions($this->getClubPathfinders());
// 		$form->getElement('position')->setMultiOptions($this->getPositions());

		$id = $this->_getParam('id', 0);

		$directive = $this->_entityManager->find('Model\Directive', $id);
		if ($directive != NULL) {
			$form->getElement('firstName')->setValue($directive->getFirstName());
			$form->getElement('lastName')->setValue($directive->getLastName());
			$form->getElement('sex')->setValue($directive->getSex());
			$form->getElement('email')->setValue($directive->getEmail());
			$form->getElement('phonemobil')->setValue($directive->getPhonemobil());
			$form->getElement('phone')->setValue($directive->getPhone());
			$form->getElement('club')->setValue($directive->getClub()->getId());
			$form->getElement('position')->setValue($directive->getPosition()->getId());

			$imageDataVaultMapper = new Model_ImageDataVaultMapper();
			$imagePicture = $imageDataVaultMapper->find($directive->getProfilePictureId());

			if ($imagePicture != NULL && $imagePicture->getBinary()) {
				$src = $this->_helper->url('profile-picture', NULL, NULL, array('id' => $imagePicture->getId(), 'timestamp' => time()));
				$form->setSource($src);
			} else {
				$src = '/js/lib/ajax-upload/ep.jpg';
				$form->setSource($src);
			}
		} else {
			$this->stdResponse->success = FALSE;
			$this->stdResponse->message = _("The requested record was not found.");
			$this->_helper->json($this->stdResponse);
		}

		$this->view->form = $form;
	}

	/**
	 *
	 * This action shows the form to view the profile of the Directive.
	 * @access public
	 */
	public function profiledirectiveAction() {
		$this->_helper->layout()->disableLayout();

		$form = new Form_DirectiveViewProfile();

		$id = $this->_getParam('id', 0);
		$directive = $this->_entityManager->find('Model\Directive', $id);
		if ($directive != NULL) {
			if ($directive->getSex() == Model\Person::SEX_MALE) {
				$gender = _('Male');
			} elseif ($directive->getSex() == Model\Person::SEX_FEMALE) {
				$gender = _('Female');
			}

			$form->getElement('firstName')->setValue($directive->getFirstName());
			$form->getElement('lastName')->setValue($directive->getLastName());
			$form->getElement('sex')->setValue($gender);
			$form->getElement('email')->setValue($directive->getEmail());
			$form->getElement('phonemobil')->setValue($directive->getPhonemobil());
			$form->getElement('phone')->setValue($directive->getPhone());
			$form->getElement('club')->setValue($directive->getClub()->getName());
			$form->getElement('position')->setValue($directive->getPosition()->getName());

			$imageDataVaultMapper = new Model_ImageDataVaultMapper();
			$imagePicture = $imageDataVaultMapper->find($directive->getProfilePictureId());

			if ($imagePicture != NULL && $imagePicture->getBinary()) {
				$src = $this->_helper->url('profile-picture', NULL, NULL, array('id' => $imagePicture->getId(), 'timestamp' => time()));
			} else {
				if ($directive->getSex() == Model\Person::SEX_MALE) {
					$src = '/image/profile/male_default.jpg';
				} elseif ($directive->getSex() == Model\Person::SEX_FEMALE) {
					$src = '/image/profile/female_default.jpg';
				}
			}
			$form->setSource($src);
		} else {
			$this->stdResponse->success = FALSE;
			$this->stdResponse->message = _("The requested record was not found.");
			$this->_helper->json($this->stdResponse);
		}

		$this->view->form = $form;
	}

	/**
	 * Sends the binary file vault to the user agent.
	 * @return void
	 */
	public function profilePictureAction() {
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

	/**
	 *
	 * Outputs an XHR response containing all entries in directives.
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

		$filters = array();
		$directiveRepo = $this->_entityManager->getRepository('Model\Directive');
		$directives = $directiveRepo->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $directiveRepo->getTotalCount($filters);

		$posRecord = $start+1;
		$data = array();
		foreach ($directives as $directive) {
			$changed = $directive->getChanged();
			if ($changed != NULL) {
				$changed = $changed->format('d.m.Y');
			}

			$row = array();
			$row[] = $directive->getId();
			$row[] = $directive->getName();
			$row[] = $directive->getPosition()->getName();
			$row[] = $directive->getPhonemobil();
			$row[] = $directive->getPhone();
			$row[] = $directive->getEmail();
			$row[] = $directive->getClub()->getName();
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
	 * Returns a model ClubPathdinder by id request
	 * @param string $id
	 * @return Model\ClubPathfinder
	 */
	private function getClubPathfinderById($id) {
		switch ($id) {
			case 'leon':
				$club = $this->_entityManager->find('Model\ClubPathfinder', 1);
				break;

			case 'orion':
				$club = $this->_entityManager->find('Model\ClubPathfinder', 2);
				break;
			default:
				$club = $this->_entityManager->find('Model\ClubPathfinder', 1);;
			break;
		}
		return $club;
	}

	/**
	 * Active the current page
	 * @param string $id
	 */
	private function activePage($id) {
		$page = $this->view->navigation()->findOneBy('id', $id);
		$page->setActive(TRUE);
	}

	public function nuevoamanecerAction() {
		$position = $this->_entityManager->find('Model\Position', 1);
		var_dump($position->getCreated()->format('Y.m'));
		echo "<br>";
		$positionRepo = $this->_entityManager->getRepository('Model\Position');
		$positions = $positionRepo->findByCriteria();

		echo "<pre>";
		var_dump($positions);
		echo "</pre>";


	}

	public function nuevoorienteAction() {
	}

	public function orionAction() {
		$connectionParams = array(
			'dbname' => 'dbch',
			'user' => 'root',
			'password' => '',
			'host' => 'localhost',
			'driver' => 'pdo_mysql',
			);
		$conn = DriverManager::getConnection($connectionParams);
		$users = $conn->fetchAll('SELECT * FROM tblPosition');
		echo "<pre>";
		var_dump($users);
		echo "</pre>";
	}

// 	public function uploadAction() {
// 		$this->_helper->layout()->disableLayout();
// 		$this->_helper->viewRenderer->setNoRender(true);

// 		$target_path = "image/upload/";
// 		// Set the new path with the file name
// 		$target_path = $target_path . basename( $_FILES['myfile']['name']);
// 		// Move the file to the upload folder
// 		if(move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) {
// 			// print the new image path in the page, and this will recevie the javascripts 'response' variable
//     		echo "/".$target_path;
// 		} else{
// 			// Set default the image path if any error in upload.
//     		echo "default.jpg";
// 		}
// 	}
}