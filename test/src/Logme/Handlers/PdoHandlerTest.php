<?php

use Logme\Logger,
    Logme\Formatter,
    Logme\Handlers\PdoHandler;

require_once "PHPUnit/Extensions/Database/TestCase.php";

class PdoHandlerTest extends PHPUnit_Extensions_Database_TestCase
{
    /**
     * Only instantiate PDO object once for test clean-up/fixture load
     * 
     * @static
     * @var PDO
     */
    static private $pdo;
    
    /**
     * Used to only instantiate the object once per test
     * @var PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    private $conn;
    
    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection()
    {
      if (NULL === $this->conn) {
        $fixture = LOGME_SYSDIR . '/test/fixture/PdoHandler.sqlite';
        if (NULL == self::$pdo) {
            self::$pdo = new PDO("sqlite:$fixture");
        }
        $this->conn = $this->createDefaultDBConnection(self::$pdo, $fixture);
      }
      return $this->conn;
    }
    
    /**
     * Reloads default data set
     */
    public function getDataSet()
    {
        $xml = LOGME_SYSDIR . '/test/fixture/PdoHandlerSeed.xml';
        return $this->createXMLDataSet($xml);
    }
    
    /**
     * @covers Logme\Handlers\PdoHandler::__construct
     * @covers Logme\Handlers\HandlerAbstract::__construct
     */
    public function testBeginsEmpty()
    {
        $fields = ['msg', ['fmtTime'=>'time'], 'level'];
        $handler = new PdoHandler(self::$pdo, 'log_table', $fields);
    }
    
    /**
     * @covers Logme\Handlers\PdoHandler::emit
     */
    public function testEmitLogsMsgToDatabaseTable()
    {
        $fields  = ['msg', ['fmtTime'=>'time'], 'level'];
        $fmt     = new Formatter(NULL, 'Y-m-d H:i:s');
        $handler = new PdoHandler(self::$pdo, 'log_table', $fields, $fmt);
        $log     = new Logger;
        $log->addHandler($handler);
        
        $msg = 'my test message';
        $log->debug($msg);
        
        $stmt = self::$pdo->query('SELECT * FROM log_table');
        $rows = $stmt->fetchAll();
        $this->assertEquals(1, count($rows));
        $this->assertEquals(Logger::DEBUG, $rows[0]['level']);
        $this->assertEquals($msg, $rows[0]['msg']);
    }
}
