<?php

/**
 * LoggerInterface File
 * 
 * PHP version 5.4
 * 
 * @category     Logme
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */

namespace Logme;
use Logme\Handlers\HandlerInterface;

/**
 * An interface for logger classes
 *
 * @category     Logme
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
     * @param int    $extra Optional additional fields for logging
     */
    public function log($level, $msg, array $extra);
    
    /**
     * Factory method for creating LogRecord instances
     * 
     * @param array $vals An array of log values to populate the log record
     */
    public function makeLogRecord(array $vals);
}
