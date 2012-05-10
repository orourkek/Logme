<?php

use Logme\Logger,
    Logme\LogRecord,
    Logme\Handlers\ConsoleHandler;

class ConsoleHandlerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Uses reflection since we can't test that the emit method actually
     * wrote to STDERR
     */
    public function setUp() {
        $this->handler = new ConsoleHandler;
        $streamProp = new ReflectionProperty($this->handler, 'stream');
        $this->stream = fopen('php://memory', 'rw');
        $streamProp->setAccessible(TRUE);
        $streamProp->setValue($this->handler, $this->stream);
    }
    
    /**
     * @covers Logme\Handlers\ConsoleHandler::handle
     * @covers Logme\Handlers\ConsoleHandler::emit
     */
    public function testEmitWritesLogEventToConsole()
    {
        $vals = [
            'name'      => 'handler name',
            'level'     => 1,
            'levelName' => 'critical',
            'msg'       => 'my test message',
            'time'      => time()
        ];
        
        $logRec = new LogRecord($vals);
        
        $this->handler->handle($logRec);
        
        rewind($this->stream);
        $this->assertEquals($vals['msg'] . PHP_EOL,
            stream_get_contents($this->stream)
        );
        fclose($this->stream);
    }
}
