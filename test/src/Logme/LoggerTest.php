<?php

use Logme\Logger,
    Logme\Formatter,
    Logme\LogRecord,
    Logme\Handlers\NullHandler,
    Logme\Handlers\FileHandler;

class LoggerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Logme\Logger::__construct
     */
    public function testBeginsEmpty()
    {
        return new LoggerTestImpl;
    }
    
    /**
     * @depends testBeginsEmpty
     * @covers Logme\Logger::addLevel
     * @expectedException DomainException
     */
    public function testAddLevelThrowsExceptionOnReservedLevelInteger($log)
    {
        $log->addLevel(Logger::DEBUG, 'myLevel');
    }
    
    /**
     * @depends testBeginsEmpty
     * @covers Logme\Logger::addLevel
     * @expectedException DomainException
     */
    public function testAddLevelThrowsExceptionOnReservedLevelName($log)
    {
        $log->addLevel(42, 'debug');
    }
    
    /**
     * @depends testBeginsEmpty
     * @covers Logme\Logger::addLevel
     */
    public function testAddLevelAssignsCustomLevel($log)
    {
        $log->addHandler(new NullHandler);
        $log->addLevel(42, 'myLevel');
        $log->log(42, 'test message');
    }
    
    /**
     * @covers Logme\Logger::log
     * @expectedException RangeException
     */
    public function testLogThrowsExceptionOnNonexistentIntegerLevel()
    {
        $log = new LoggerTestImpl;
        $log->log(42, 'my log message');
    }
    
    /**
     * @covers Logme\Logger::log
     * @covers Logme\Logger::makeLogRecord
     */
    public function testLogIncorporatesExtraFields()
    {
        $log     = new LoggerTestImpl;
        $file    = 'vfs://log/log_extra_file.txt';
        $fmt     = new Formatter('%(field) - %(msg)');
        $handler = new FileHandler($file, 'w+', FALSE, $fmt);
        
        $log->addHandler($handler);
        $log->debug('my log message', array('field' => 42));
        
        $this->assertEquals('42 - my log message' . PHP_EOL,
            file_get_contents($file)
        );
    }
    
    /**
     * @covers Logme\Logger::log
     */
    public function testLogReturnsZeroIfLevelIsMasked()
    {
        $log = new LoggerTestImpl;
        $log->setThreshold(Logger::WARNING);
        $handler = new NullHandler;
        $log->addHandler($handler);
        $this->assertEquals(0, $log->debug('my log message'));
    }
    
    /**
     * @covers Logme\Logger::log
     */
    public function testLogNotifiesHandlers()
    {
        $handler = $this->getMock('Logme\Handlers\NullHandler', ['emit']);
        $handler->expects($this->once())
                ->method('emit');
        
        $logger = new LoggerTestImpl;
        $logger->addHandler($handler);
        $return = $logger->log(Logger::DEBUG, 'my log message');
        $this->assertEquals(1, $return);
    }
    
    /**
     * @covers Logme\Logger::log
     */
    public function testLogInvokesFallbackIfHandlerThrowsException()
    {
        $handler = $this->getMock('Logme\Handlers\NullHandler', array('emit'));
        $handler->expects($this->once())
                ->method('emit')
                ->will($this->throwException(new Exception));
        
        $fallback = $this->getMock('Logme\Handlers\NullHandler', array('emit'));
        $fallback->expects($this->once())
                 ->method('emit');
        
        $logger = new LoggerTestImpl(NULL, $fallback);
        $logger->addHandler($handler);
        $logger->log(Logger::DEBUG, 'my log message');
    }
    
    /**
     * @covers Logme\Logger::addHandler
     */
    public function testAddHandlerAttachesLogHandlerInstance()
    {
        $handler = $this->getMock('Logme\Handlers\NullHandler', array('emit'));
        $handler->expects($this->once())
                ->method('emit');
        
        $logger = new LoggerTestImpl;
        $this->assertEquals($handler, $logger->addHandler($handler));
        $return = $logger->log(Logger::WARNING, 'my log message');
        $this->assertEquals(1, $return);
        return $logger;
    }
    
    /**
     * @covers Logme\Logger::canHandle
     */
    public function testCanHandleReturnsBooleanIfSpecifiedLevelWillBeProcessed()
    {
        $vals = array(
            'name'      => 'handler name',
            'level'     => Logger::ERROR,
            'levelName' => 'errror',
            'msg'       => 'my test message',
            'time'      => time()
        );
        $logRecError = new LogRecord($vals);
        
        $vals['level'] = Logger::DEBUG;
        $vals['levelName'] = 'debug';
        $logRecDebug = new LogRecord($vals);
        
        $logger = new LoggerTestImpl;
        $this->assertTrue($logger->canHandle($logRecError));
        
        $logger = new LoggerTestImpl;
        $logger->setThreshold(Logger::WARNING);
        $this->assertFalse($logger->canHandle($logRecDebug));
        
        $logger->mask(Logger::ERROR);
        $this->assertFalse($logger->canHandle($logRecError));
    }
    
    /**
     * @covers Logme\Logger::mask
     */
    public function testMaskAppliesLevelMask()
    {
        $log = new LoggerTestImpl;
        $log->mask(Logger::ERROR);
        
        $handler = new NullHandler;
        $log->addHandler($handler);
        
        $this->assertEquals(0, $log->error('test'));
        
        return $log;
    }
    
    /**
     * @covers Logme\Logger::setThreshold
     */
    public function testThresholdAppliesLevelThreshold()
    {
        $log = new LoggerTestImpl;
        $log->setThreshold(Logger::ERROR);
        
        $handler = new NullHandler;
        $log->addHandler($handler);
        
        $this->assertEquals(0, $log->debug('test'));
        
        return $log;
    }
    
    /**
     * @depends testMaskAppliesLevelMask
     * @covers Logme\Logger::unmask
     */
    public function testUnMaskRemovesMask($log)
    {
        $log->unmask(Logger::ERROR);
        $this->assertEquals(1, $log->error('test'));
    }
    
    /**
     * @covers Logme\Logger::__call
     * @expectedException BadMethodCallException
     */
    public function testMagicCallThrowsExceptionOnInvalidLevelName()
    {
        $logger = new LoggerTestImpl;
        $logger->badlevel('message');
    }
    
    /**
     * @covers Logme\Logger::__call
     */
    public function testMagicCallInvokesLogMethodOnValidLevelName()
    {
        $mock = $this->getMock('LoggerTestImpl', ['log']);
        $mock->expects($this->once())
             ->method('log')
             ->with(Logger::DEBUG, 'message');
        $mock->debug('message');
    }
    
    /**
     * @covers Logme\Logger::__call
     * @expectedException InvalidArgumentException
     */
    public function testMagicCallThrowsExceptionOnMissingLogArgs()
    {
        $log = new LoggerTestImpl;
        $log->debug();
    }
}

// Hack to make PHPUnit correctly report coverage on class that uses traits
class LoggerTestImpl extends Logger {}
