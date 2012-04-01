<?php

require_once 'Zend/View/Interface.php';

/**
 * 
 * 
 */
class App_View_Helper_SeeMessages {
	
	/**
	 * @var Zend_View_Interface 
	 */
	public $view;
	
	private $_types = array(
		App_Controller_Action_Helper_Messenger::ERROR,
		App_Controller_Action_Helper_Messenger::NOTICE,
		App_Controller_Action_Helper_Messenger::SUCCESS,
		App_Controller_Action_Helper_Messenger::WARNING
	);
	
	/**
	 * 
	 */
	public function seeMessages() {
		$messenger = Zend_Controller_Action_HelperBroker::getStaticHelper("Messenger");
		$html = '';
		foreach ($this->_types as $type) {
			$messages = $messenger->getCurrentMessages($type);
			if (!$messages) {
				$messages = $messenger->getMessages($type);
			}
			if($messages) {
				foreach ($messages as $message) {
					$html .= $this->view->translate($message->message);
				}
			}
		}
		return $html;
	}
	
	/**
	 * @deprecated
	 */
	private function messages() {
		$messenger = Zend_Controller_Action_HelperBroker::getStaticHelper("Messenger");
		$html = '';
		foreach ($this->_types as $type) {
			$messages = $messenger->getCurrentMessages($type);
			if (!$messages) {
				$messages = $messenger->getMessages($type);
			}
			if($messages) {
				if (!$html) {
					$html .= '<ul class="messages">'; 
				}
				$html .= '<li class="' . $type . '-message">';
				$html .= '<ul>';
				foreach ($messages as $message) {
					$html .= '<li>';
					$html .= $message->message;
					$html .= '</li>';
				}
				$html .= '</ul>';
				$html .= '</li>';
			}
		} 
		if ($html) {
			$html .= '</ul>';
		}
		return $html;
	}
	
	/**
	 * 
	 * @param $view Zend_View_Interface
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}
