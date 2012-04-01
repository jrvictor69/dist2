<?php

class Admin_SecurityLogController extends Zend_Controller_Action {
	
	public function init() {
		
	}
	
	public function indexAction() {
		$this->_helper->redirector('index', 'underdevelopment');
	}
}