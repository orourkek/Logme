<?php

use Logme\Logger,
    Logme\Formatter,
    Logme\LogRecord;

class FormatterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Logme\Formatter::__construct
     * @covers Logme\Formatter::formatMsg
     */
    public function testFormatMsgReturnsExpected()
    {
        $vals = [
            'level'     => Logger::DEBUG,
            'levelName' => 'debug',
            'msg'       => 'my test message',
            'time'      => time()
        ];
        $logRec = new LogRecord($vals);
        $fmt = new Formatter;
        $this->assertEquals($vals['msg'], $fmt->formatMsg($logRec));
    }
    
    /**
     * @covers Logme\Formatter::__construct
     * @covers Logme\Formatter::formatTime
     */
    public function testFormatTimeReturnsExpected()
    {
        $vals = [
            'level'     => Logger::DEBUG,
            'levelName' => 'debug',
            'msg'       => 'my test message',
            'time'      => time()
        ];
        $logRec = new LogRecord($vals);
        $fmt = new Formatter(NULL, 'Y-m-d H:i:s');
        
        $fmtTime = $fmt->formatTime($logRec);
        $this->assertEquals(date('Y-m-d H:i:s', $vals['time']), $fmtTime);
    }
}
