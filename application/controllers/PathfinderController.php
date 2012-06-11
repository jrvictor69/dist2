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
}