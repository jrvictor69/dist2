<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_CertificationController extends App_Controller_Action {

	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
	public function init() {
		parent::init();
	}

	/**
	 * @access public
	 */
	public function indexAction() {
		$a = array(1, 2, 3);
		var_dump($a);

		$range = range(1, 10);

		echo "<pre>";
		var_dump($range);
		echo "</pre>";

		$slite = array_slice($range, 2, 6);

		echo "<pre>";
		var_dump($slite);
		echo "</pre>";
	}

	public function fireAction() {
		$writer = new Zend_Log_Writer_Firebug();
		$logger = new Zend_Log($writer);
		$logger->log('this message victor y verito', Zend_Log::INFO);

		$writer->setPriorityStyle(8, 'TABLE');
		$logger->addPriority('TABLE', 8);

		$table = array('Summary line for the table',
			array(
				array('Column 1', 'Column 2'),
				array('Row 1 c 1',' Row 1 c 2'),
				array('Row 2 c 1',' Row 2 c 2')
			)
		);

		$memberClubRepo = $this->_entityManager->getRepository('Model\MemberClub');
		$members = $memberClubRepo->findByCriteria();

		$logger->table($table);
		$logger->table($members);

		$params = array(
			'host'		=> '127.0.0.1',
			'username'	=> 'root',
			'password'	=> '',
			'dbname'	=> 'dbch',
			'profiler'	=> true  // turn on profiler
			// set to false to disable (disabled by default)
		);

		$db = Zend_Db::factory('PDO_MYSQL', $params);
		echo "<pre>";
		var_dump($db->fetchAll('select * from tblPerson'));
		echo "</pre>";

		// Instantiate the profiler in your bootstrap file
		$profiler = new Zend_Db_Profiler_Firebug('All Database Queries:');
		// Enable it
		$profiler->setEnabled(true);
		// Attach the profiler to your db adapter
		$db->setProfiler($profiler);

		// Run your queries
		$result1 = $db->fetchAll('SELECT * FROM tblPerson');
		$result2 = $db->fetchAll('SELECT * FROM tblPerson where id = ?', 3);


		//---

		$this->_message = new \Zend_Wildfire_Plugin_FirePhp_TableMessage('Test');
		$this->_message->setBuffered(true);
		$this->_message->setHeader(array('One','Two','Three'));
		$this->_message->setOption('includeLineNumbers', false);
		$this->_message->addRow(array(1,2,3));
		$this->_message->addRow(array(4,5,6));
		Zend_Wildfire_Plugin_FirePhp::getInstance()->send($this->_message, 'Test');
	}

	public function cacheAction() {
		$frontendOptions = array(
			'lifetime' => 7200, // cache lifetime of 2 hours
			'automatic_serialization' => true
		);

		$backendOptions = array(
			'cache_dir' => APPLICATION_PATH .  '/../var/temp/' // Directory where to put the cache files
		);

// 		// getting a Zend_Cache_Core object
		$cache = Zend_Cache::factory('Core',
			'File',
			$frontendOptions,
			$backendOptions);


		$memberClubRepo = $this->_entityManager->getRepository('Model\Position');
		$result = $memberClubRepo->findByCriteria();

// 		var_dump($result);
		if( ($result = $cache->load('myresult2')) === false ) {
			$cache->save($result, 'myresult2');
		} else {
			// cache hit! shout so that we know
			echo "This one is from cache!\n\n";

		}
var_dump($result);
// 		print_r($result);
	}
}