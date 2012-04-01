<?php 

require_once ('Zend/Controller/Plugin/Abstract.php');

class App_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
	/* (non-PHPdoc)
	 * @see Zend_Controller_Plugin_Abstract::preDispatch()
	 */
	//@victor commet by victor tem
	public function preDispatch(Zend_Controller_Request_Abstract $request) 
	{
		return true;
//		if ( ( $request->getModuleName() == 'um' && $request->getControllerName() == 'login') 
//			||
//			( $request->getModuleName() == 'admin' && $request->getControllerName() == 'error' ) ) 
//		{ 
//			return true; 
//		}
//		
//		$acl = new App_Acl();
//		
//		if ($acl->isAllowed(Zend_Auth::getInstance()->getIdentity(), $request, null) === false) {
////var_dump(Zend_Auth::getInstance()->getIdentity());	
////exit;
//			$request->setModuleName('um')
//					->setControllerName('login')
//					->setActionName('login')
//					->setDispatched(false);
//			return false;
//		}		
	}
}