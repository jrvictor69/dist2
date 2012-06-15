<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

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
	}
	
	public function nuevoamanecerAction() {
	}
	
	public function nuevoorienteAction() {
	}
	
	public function orionAction() {
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