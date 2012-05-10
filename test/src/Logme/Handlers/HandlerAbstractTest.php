<?php

use Logme\Logger,
    Logme\LogRecord,
    Logme\Formatter,
    Logme\Handlers\NullHandler;

class HandlerAbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Logme\Handlers\HandlerAbstract::canHandle
     * @covers Logme\Handlers\HandlerAbstract::handle
     */
    public function testCanHandleReturnsBooleanIfSpecifiedLevelWillBeProcessed()
    {
        $vals = [
            'name'      => 'handler name',
            'level'     => Logger::ERROR,
            'levelName' => 'error',
            'msg'       => 'my test message',
            'time'      => time()
        ];
        $logRec = new LogRecord($vals);
        
        $handler = new NullHandler;
        $this->assertTrue($handler->canHandle($logRec));
        $handler->mask(Logger::ERROR);
        $this->assertFalse($handler->canHandle($logRec));
        $this->assertFalse($handler->handle($logRec));
    }
    
    /**
     * @covers Logme\Handlers\HandlerAbstract::mask
     */
    public function testFilterAppliesLevelMask()
    {
        $handler = new NullHandler;
        $handler->mask(Logger::ERROR);
        
        $log = new Logger;
        $log->addHandler($handler);
        $this->assertEquals(0, $log->error('test'));
        
        return $handler;
    }
    
    /**
     * @depends testFilterAppliesLevelMask
     * @covers Logme\Handlers\HandlerAbstract::unmask
     */
    public function testUnFilterRemovesFilterAndReturnsChainableInstance($handler)
    {
        $log = new Logger;
        $log->addHandler($handler);
        $this->assertEquals(0, $log->error('test'));
        $handler->unmask(Logger::ERROR);
        $this->assertEquals(1, $log->error('test'));
    }
    
    /**
     * @covers Logme\Handlers\HandlerAbstract::emit
     * @covers Logme\Handlers\HandlerAbstract::getFormattedRecordArr
     */
    public function testHandleEmitsIfCanHandleSpecifiedLogLevel()
    {
        $handler = new NullHandler;
        $log = new Logger;
        $log->addHandler($handler);
        
        $vals = [
            'name'      => 'emitr name',
            'level'     => Logger::DEBUG,
            'levelName' => 'critical',
            'msg'       => 'my test message',
            'time'      => new DateTime
        ];
        
        $this->assertEquals(1, $log->debug('test'));
    }
}
