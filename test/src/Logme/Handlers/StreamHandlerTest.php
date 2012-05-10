<?php

use Logme\Handlers\StreamHandler,
    Logme\Logger;

class StreamHandlerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Logme\Handlers\StreamHandler::__construct
     * @covers Logme\Handlers\HandlerAbstract::__construct
     */
    public function testConstructorAssignsProperties()
    {
        $fh = fopen('vfs://log/log_file.txt', 'w+');
        $handler = new StreamHandler(Logger::DEBUG, $fh);
        fclose($fh);
    }
    
    /**
     * @covers Logme\Handlers\StreamHandler::handle
     * @covers Logme\Handlers\StreamHandler::emit
     * @covers Logme\Handlers\StreamHandler::lockWrite
     */
    public function testEmitWritesLogEventToStream()
    {
        $name      = 'handler name';
        $level     = 1;
        $levelName = 'critical';
        $msg       = 'my test message';
        $dt        = new DateTime;
        $time      = $dt->format('Y-m-d H:i:s');
        
        $fh = fopen('vfs://log/log_file.txt', 'w+');
        
        $handler = new StreamHandler($fh);
        $log = new Logger;
        $log->addHandler($handler);
        $log->log($level, $msg);
        
        rewind($fh);
        $actual = stream_get_contents($fh);
        $this->assertEquals($msg . PHP_EOL, $actual);
        
        $fh = fopen('vfs://log/log_file.txt', 'w+');
        $handler = new StreamHandler($fh, TRUE);
        $log = new Logger;
        $log->addHandler($handler);
        $log->log($level, $msg);
        
        rewind($fh);
        $actual = stream_get_contents($fh);
        $this->assertEquals($msg . PHP_EOL, $actual);
    }
}
