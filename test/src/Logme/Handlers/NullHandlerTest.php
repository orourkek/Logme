<?php

use Logme\Logger,
    Logme\LogRecord,
    Logme\Handlers\NullHandler;

class NullHandlerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Logme\Handlers\NullHandler::handle
     * @covers Logme\Handlers\NullHandler::emit
     * @covers Logme\Handlers\HandlerAbstract::__construct
     */
    public function testEmitReturnsNull()
    {
        $vals = [
            'name'      => 'handler name',
            'level'     => 1,
            'levelName' => 'critical',
            'msg'       => 'my test message',
            'time'      => time()
        ];
        $logRec  = new LogRecord($vals);
        $handler = new NullHandler;
        $this->assertEquals(1, $handler->handle($logRec));
    }
}
