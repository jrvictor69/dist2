<?php

require_once 'PHPUnit/Extensions/SeleniumTestCase.php';
class SeleniumTestCase extends PHPUnit_Extensions_SeleniumTestCase {
	protected $name = 'Browser Name';
	
	/**
	 * @var DBTestCase
	 */
	private $dbTestCase;
	
	public static $browsers = array(
		array(
			'name'		=> SELENIUM_NAME,
			'browser'	=> SELENIUM_BROWSER,
			'host'		=> SELENIUM_HOST,
			'port'		=> SELENIUM_PORT,
			'timeout'	=> SELENIUM_TIMEOUT
		)
	);
	
	public function setUp() {
		parent::setUp();
		$this->setBrowserUrl(SELENIUM_BROWSER_URL);
		$this->setupDatabase();
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
         * Make the test wait until there isn't any active XHR request
         * 
         * @param int $timeout milliseconds
         */
        public function waitForJQueryXhr($timeout = NULL) {
                $js = 'selenium.browserbot.getCurrentWindow().$.active == 0';
                if (NULL !== $timeout) {
                        $this->waitForCondition($js, $timeout);
                } else {
                        $this->waitForCondition($js);
                }
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
