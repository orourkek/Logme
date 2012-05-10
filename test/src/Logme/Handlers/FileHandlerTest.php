<?php

use Logme\Handlers\FileHandler,
    Logme\Logger,
    Logme\LogRecord;

class FileHandlerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Logme\Handlers\FileHandler::__construct
     * @covers Logme\Handlers\HandlerAbstract::__construct
     */
    public function testBeginsEmpty()
    {
        $file = 'vfs://log/log_file.txt';
        $handler = new FileHandler($file);
    }
    
    /**
     * @covers Logme\Handlers\FileHandler::emit
     */
    public function testEmitWritesLogEventToSpecifiedFile()
    {
        $vals = [
            'name'      => 'handler name',
            'level'     => 1,
            'levelName' => 'critical',
            'msg'       => 'my test message',
            'time'      => time()
        ];
        
        $logRec   = new LogRecord($vals);
        
        $logFile  = 'vfs://log/log_file.txt';
        $handler  = new FileHandler($logFile, 'w+');
        $handler->handle($logRec);
        
        $expected = $vals['msg'] . PHP_EOL;
        $actual   = file_get_contents($logFile);
        $this->assertEquals($expected, $actual);
        
        $handler  = new FileHandler($logFile, 'w+', TRUE);
        $logRec   = new LogRecord($vals);
        $handler->handle($logRec);
        
        $actual   = file_get_contents($logFile);
        $this->assertEquals($expected, $actual);
    }
}
