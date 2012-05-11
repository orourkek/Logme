<?php

/**
 * LoggerInterface File
 * 
 * PHP version 5.4
 * 
 * @category     Logme
 * @package      Base
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */

namespace Logme;
use Logme\Handlers\HandlerInterface;

/**
 * An interface for logger classes
 *
 * @category     Logme
 * @package      Base
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
interface LoggerInterface extends EmitterInterface
{
    /**
     * Attach a handler to the logger
     * 
     * @param HandlerInterface $handler A log handler instance
     */
    public function addHandler(HandlerInterface $handler);
    
    /**
     * Add a custom error reporting level
     * 
     * @param int    $level     The integer log level
     * @param string $levelName The name of the custom log reporting level
     */
    public function addLevel($level, $levelName);
    
    /**
     * Notify registered handlers of a log event
     * 
     * @param int    $level The integer log level
     * @param string $msg   The log event message
     * @param array  $extra Optional key-value array of additional log fields
     */
    public function log($level, $msg, array $extra);
    
    /**
     * Factory method for creating LogRecord instances
     * 
     * @param array $vals An array of log values to populate the log record
     */
    public function makeLogRecord(array $vals);
}
