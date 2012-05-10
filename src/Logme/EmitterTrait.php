<?php

/**
 * EmitterTrait File
 * 
 * PHP version 5.4
 * 
 * @category     Logme
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */

namespace Logme;

/**
 * Provides implementation for the EmitterInterface
 *
 * @category     Logme
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
trait EmitterTrait
{
    /**
     * An optional filtering object
     * @var FilterInterface
     */
    private $filter;
    
    /**
     * An array listing log event levels the logger should ignore
     * @var array
     */
    private $masked = [];
    
    /**
     * The minimum severity required for log handling
     * @var int
     */
    private $threshold;
    
    /**
     * Determine if the specified log record should be emitted
     * 
     * @param LogRecordInterface $logRec A log record object
     * 
     * @return bool Returns TRUE if a log event of the specified level will 
     *              result in handler invocation or FALSE otherwise. FALSE 
     *              is also returned if the specified log level does not exist.
     */
    public function canHandle(LogRecordInterface $logRec)
    {
        $level = $logRec->fetch('level');
        
        if ((NULL === $this->threshold || $this->threshold >= $level)
            && !in_array($level, $this->masked)
            && (!$this->filter || !$this->filter->shouldMask($logRec))
        ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * Mask specific log levels from being reported
     * 
     * @param int $level The log event level
     * 
     * @return void
     */
    public function mask($level)
    {
        if (!in_array($level, $this->masked)) {
            $this->masked[] = $level;
        }
    }
    
    /**
     * Set a threshold level above which log messages will be ignored
     * 
     * @param int $level The log event threshold level
     * 
     * @return mixed Returns current object instance
     */
    public function setThreshold($level)
    {
        $this->threshold = (int) $level;
        return $this;
    }
    
    /**
     * Unmask a previously masked logging level
     * 
     * @param int $level The log event level
     * 
     * @return void
     */
    public function unmask($level)
    {
        $key = array_search($level, $this->masked);
        if (FALSE !== $key) {
            unset($this->masked[$key]);
        }
    }
}
