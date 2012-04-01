<?php

require_once 'PHPUnit/Extensions/Database/Constraint/TableIsEqual.php';
require_once 'PHPUnit/Extensions/Database/TestCase.php';

require_once 'PHPUnit/Extensions/Database/ITester.php';
require_once 'PHPUnit/Extensions/Database/AbstractTester.php';
require_once 'PHPUnit/Extensions/Database/DefaultTester.php';

require_once 'PHPUnit/Extensions/Database/DB/IMetaData.php';
require_once 'PHPUnit/Extensions/Database/DB/IDatabaseConnection.php';
require_once 'PHPUnit/Extensions/Database/DB/MetaData.php';
require_once 'PHPUnit/Extensions/Database/DB/MetaData/MySQL.php';
require_once 'PHPUnit/Extensions/Database/DB/MetaData.php';

require_once 'PHPUnit/Extensions/Database/DB/DefaultDatabaseConnection.php';

require_once 'PHPUnit/Extensions/Database/DataSet/IDataSet.php';
require_once 'PHPUnit/Extensions/Database/DataSet/ITable.php';
require_once 'PHPUnit/Extensions/Database/DataSet/ITableMetaData.php';
require_once 'PHPUnit/Extensions/Database/DataSet/AbstractDataSet.php';
require_once 'PHPUnit/Extensions/Database/DB/DataSet.php';
require_once 'PHPUnit/Extensions/Database/DataSet/AbstractXmlDataSet.php';
require_once 'PHPUnit/Extensions/Database/DataSet/XmlDataSet.php';
require_once 'PHPUnit/Extensions/Database/DataSet/AbstractTableMetaData.php';
require_once 'PHPUnit/Extensions/Database/DataSet/DefaultTableMetaData.php';
require_once 'PHPUnit/Extensions/Database/DataSet/AbstractTable.php';
require_once 'PHPUnit/Extensions/Database/DataSet/DefaultTable.php';
require_once 'PHPUnit/Extensions/Database/DataSet/QueryTable.php';

require_once 'PHPUnit/Extensions/Database/DataSet/ITableIterator.php';
require_once 'PHPUnit/Extensions/Database/DataSet/DefaultTableIterator.php';


require_once 'PHPUnit/Extensions/Database/Operation/IDatabaseOperation.php';
require_once 'PHPUnit/Extensions/Database/Operation/RowBased.php';
require_once 'PHPUnit/Extensions/Database/Operation/Truncate.php';
require_once 'PHPUnit/Extensions/Database/Operation/Insert.php';
require_once 'PHPUnit/Extensions/Database/Operation/Factory.php';
require_once 'PHPUnit/Extensions/Database/Operation/Composite.php';
require_once 'PHPUnit/Extensions/Database/Operation/Exception.php';



class DbTestCase extends PHPUnit_Extensions_Database_TestCase {
	protected $backupGlobals = FALSE;
	public function __construct() {
		$this->backupGlobals = false;
        $this->backupStaticAttributes = false;
		parent::__construct();
		
	}
	
	public function setUp() {
		parent::setUp();
	}
	
	// only instanciate pdo once for test clean-up/fixture load
	//static private $pdo = NULL;
	private $pdo = NULL;
	
	// only instantiate PHPUnit_Extensions_DB_IDatabaseConnection once
	private $conn = NULL;
	
	/**
	 * 
	 * @var ControllerTest|SeleniumTestCase 
	 */
	private $testCase = NULL;
	
	public function setTestCase($testCase) {
		$this->testCase = $testCase;
	}
	
	/**
     * Returns the test database connection.
     *
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
	public function getConnection() {
//		if ($this->conn == NULL) {
//			if (self::$pdo == NULL) {
//				self::$pdo = new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PWD']);
//				
//				// in order to work with InnoDB. Disable the foreign key checks 
//				self::$pdo->exec('SET FOREIGN_KEY_CHECKS=0');
//			}
//			
//			$this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
//		}

		if ($this->conn == NULL) {
			if ($this->pdo == NULL) {
				$this->pdo = new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PWD']);
				
				// in order to work with InnoDB. Disable the foreign key checks 
				$this->pdo->exec('SET FOREIGN_KEY_CHECKS=0');
			}
			
			$this->conn = $this->createDefaultDBConnection($this->pdo, $GLOBALS['DB_DBNAME']);
		}
		
		return $this->conn;
	}
	

	/**
     * Returns the test dataset.
     *
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
	public function getDataSet() {
		return $this->testCase->getDataSet();
	}
	
	/**
     * Asserts that two given tables are equal.
     *
     * @param PHPUnit_Extensions_Database_DataSet_ITable $expected
     * @param PHPUnit_Extensions_Database_DataSet_ITable $actual
     * @param string $message
     */
	public function createXmlDataSet($filename) {
		return parent::createXmlDataSet($filename);
	}
}