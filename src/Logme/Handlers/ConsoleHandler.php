<?php

/**
 * Console Log Handler Class File
 * 
 * PHP version 5.4
 * 
 * @category     Logme
 * @package      Handlers
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */

namespace Logme\Handlers;
use Logme\LogRecordInterface;

/**
 * A handler that logs messages to the STDERR output stream
 * 
 * @category     Logme
 * @package      Handlers
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
class ConsoleHandler extends HandlerAbstract
{    
    /**
     * Log all errors to the STDERR stream
     * 
     * We specify STDERR as the `$stream` property so that we can use
     * Reflection to test that the emit method writes messages as expected
     * without actually writing to the STDERR stream.
     * 
     * @var resource
     */
    protected $stream = STDERR;
    
    /**
     * Log an event to STDERR
     * 
     * @param array $vals A key-value array of formatted log record values
     * 
     * @return void
     */
    protected function emit(array $vals)
    {
        fwrite($this->stream, $vals['fmtMsg'] . PHP_EOL);
    }
}
