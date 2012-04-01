<?php
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

class ControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase {
	
	/**
	 * @var DBTestCase
	 */
	private $dbTestCase;
	
	/**
	 * @var EntityManager
	 */
	public $em;
	
	public function setUp() {
		$this->bootstrap = array($this, 'appBootstrap');
		parent::setUp();		
		$this->setupDatabase();
		
	}
	
	/**
	 * Bootstrap the application, and gets a doctrine entity manager.
	 */
	public function appBootstrap() {
		$this->application = 
			new Zend_Application(APPLICATION_ENV,
								APPLICATION_PATH . '/configs/application.ini');
		$this->application->bootstrap();
		
		Zend_Controller_Front::getInstance()->setParam('bootstrap', $this->application->getBootstrap());
		$this->em = $this->application->getBootstrap()->getResource('doctrine'); 
	}
	
	/**
	 * If the class has the method 'getDataSet', creates
	 * an instance of the class ControllerDbTestCase.
	 */
	public function setupDatabase() {
		if (!method_exists($this, 'getDataSet'))
			return;
		
		$this->dbTestCase = new DbTestCase();
		
		$this->dbTestCase->setTestCase($this);
		
		$this->dbTestCase->setUp();
		
	}
	
	/**
     * Creates a new XMLDataSet with the given $xmlFile. (absolute path.)
     *
     * @param string $xmlFile
     * @return PHPUnit_Extensions_Database_DataSet_XmlDataSet
     */
	public function createXmlDataSet($filename) {
		return $this->dbTestCase->createXmlDataSet($filename);
	}
	
	/**
     * Returns the test database connection.
     *
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
	public function getConnection() {
		return $this->dbTestCase->getConnection();
	}
	
	/**
     * Asserts that two given tables are equal.
     *
     * @param PHPUnit_Extensions_Database_DataSet_ITable $expected
     * @param PHPUnit_Extensions_Database_DataSet_ITable $actual
     * @param string $message
     */
	public function assertTablesEqual($expected, $actual, $message = '') 
	{
		$this->dbTestCase->assertTablesEqual($expected, $actual, $message);
	}
	
}
