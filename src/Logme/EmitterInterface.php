<?php

/**
 * EmitterInterface File
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
 * EmitterInterface
 *
 * @category     Logme
 * @package      Base
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
interface EmitterInterface
{
    /**
     * Determine if the specified log record should be emitted
     * 
     * @param LogRecordInterface $logRec A log record object
     */
    public function canHandle(LogRecordInterface $logRec);
    
    /**
     * Mask specific log levels from being reported
     * 
     * @param int $level The log event level
     */
    public function mask($level);
    
    /**
     * Set a threshold level above which log messages will be ignored
     * 
     * @param int $level The log event threshold level
     */
    public function setThreshold($level);
    
    /**
     * Unmask a previously masked log event level
     * 
     * This does not allow log levels that are less severe than the specified
     * minimum threshold set by `setLevel()`. Instead, this method simply 
     * removes log levels added to the filter list using the `filter()` method.
     * 
     * @param int $level The log event level
     */
    public function unmask($level);
}
