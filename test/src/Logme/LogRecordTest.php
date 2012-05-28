<?php

use Logme\Logger,
    Logme\LogRecord,
    Logme\Formatter;

class LogRecordTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Logme\LogRecord::__construct
     * @covers Logme\LogRecord::populateFromArr
     */
    public function testConstructorPopulatesProperties()
    {
        $vals = array(
            'level'     => Logger::DEBUG,
            'levelName' => 'debug',
            'msg'       => 'my test message',
            'time'      => time()
        );
        $logRec = new LogRecord($vals);
        
        $this->assertEquals($vals['msg'], $logRec->fetch('msg'));
        $this->assertEquals($vals['level'], $logRec->fetch('level'));
        $this->assertEquals($vals['levelName'], $logRec->fetch('levelName'));
        $this->assertEquals($vals['time'], $logRec->fetch('time'));
    }
    
    /**
     * @covers Logme\LogRecord::fetch
     */
    public function testFetchReturnsExtraArrayElementAsNeeded()
    {
        $vals = array(
            'level'     => Logger::DEBUG,
            'levelName' => 'debug',
            'msg'       => 'my test message',
            'time'      => time(),
            'myField'   => 'myField val'
        );
        $logRec = new LogRecord($vals);
        
        $this->assertEquals($vals['myField'], $logRec->fetch('myField'));
    }
    
    /**
     * @covers Logme\LogRecord::fetch
     * @expectedException OutOfBoundsException
     */
    public function testFetchThrowsExceptionOnNonexistentProperty()
    {
         $vals = array(
            'level'     => Logger::DEBUG,
            'levelName' => 'debug',
            'msg'       => 'my test message',
            'time'      => time()
        );
        $logRec = new LogRecord($vals);
        
        $test = $logRec->fetch('invalidProperty');
    }
    
    /**
     * @covers Logme\LogRecord::populateFromArr
     * @expectedException InvalidArgumentException
     */
    public function testPopulateFromArrThrowsExceptionOnInvalidArray()
    {
        $logRec = new LogRecord(array());
    }
    
    /**
     * @covers Logme\LogRecord::getValsArr
     */
    public function testGetValsArrReturnsPropertyArray()
    {
        $vals = array(
            'level'     => Logger::DEBUG,
            'levelName' => 'debug',
            'msg'       => 'my test message',
            'time'      => time(),
            'myField'   => 'my val'
        );
        $logRec = new LogRecord($vals);
        $arr = $logRec->getValsArr();
        
        $this->assertEquals($arr['myField'], $logRec->fetch('myField'));
        $this->assertEquals($arr['time'], $logRec->fetch('time'));
    }
    
    /**
     * @covers Logme\LogRecord::jsonSerialize
     */
    public function testJsonSerializeReturnsArrayPropertyList()
    {
        $vals = array(
            'level'     => Logger::DEBUG,
            'levelName' => 'debug',
            'msg'       => 'my test message',
            'time'      => time(),
            'myField'   => 'my val'
        );
        $logRec = new LogRecord($vals);
        $arr = $logRec->getValsArr();
        $this->assertEquals($arr, $logRec->jsonSerialize());
    }
    
    /**
     * @covers Logme\LogRecord::serialize
     */
    public function testSerializeReturnsSerializedArray()
    {
        $vals = array(
            'level'     => Logger::DEBUG,
            'levelName' => 'debug',
            'msg'       => 'my test message',
            'time'      => time()
        );
        $logRec = new LogRecord($vals);
        $arr = $logRec->getValsArr();
        $this->assertEquals(serialize($arr), $logRec->serialize());
    }
    
    /**
     * @covers Logme\LogRecord::unserialize
     */
    public function testUnserializeBuildsObjectFromSerializedString()
    {
        $vals = array(
            'level'     => Logger::DEBUG,
            'levelName' => 'debug',
            'msg'       => 'my test message',
            'time'      => time()
        );
        $logRec = new LogRecord($vals);
        $serialized = serialize($logRec);
        
        $vals2 = array(
            'level'     => Logger::ERROR,
            'levelName' => 'error',
            'msg'       => 'error message',
            'time'      => time()
        );
        
        $unserialized = unserialize($serialized);
        $this->assertEquals($logRec, $unserialized);
    }
}
