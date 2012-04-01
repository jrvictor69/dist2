<?php 

class App_Acl {
	
	private $_entityManager;
	
	/**
	 * 
	 * @param stdClass $user
	 * @param Zend_Controller_Request_Abstract $request
	 * @param unknown_type $privilege
	 */
	public function isAllowed($user = null, $request = null, $privilege = null) 
	{
		if (isset($user->id) && is_null($user) === false && $user !== false) {
			$userId = $user->id;
		} else {
			$userId = 0;
		}
		
		$privilege = array();
		
		if (count($privilege)) {
			return true;
		} else {
			return false;
		}
	}
}