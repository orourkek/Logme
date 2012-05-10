<?php

/**
 * Artax Stream Logging Handler Class File
 * 
 * PHP version 5.4
 * 
 * @category     Logme
 * @package      Handlers
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */

namespace Logme\Handlers;
use Logme\FormatterInterface,
    Logme\FilterInterface;

/**
 * A stream handler that logs events to a specified output stream
 * 
 * @category     Logme
 * @package      Handlers
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
class StreamHandler extends HandlerAbstract
{
    /**
     * The file handle to which log events are written
     * @var resource
     */
    protected $stream;
    
    /**
     * Whether or not to obtain a write-lock using flock() when logging events
     * @var bool
     */
    protected $flock;
    
    /**
     * Assigns the stream to which log events will be written
     * 
     * @param string             $stream    The stream to which log events will be written
     * @param bool               $flock     Whether or not a file lock should be obtained
     *                                      when writing to the log file
     * @param FormatterInterface $formatter An optional formatting object
     * @param FilterInterface    $filter    An optional filtering object
     * 
     * @return void
     */
    public function __construct($stream, $flock = FALSE,
        FormatterInterface $formatter = NULL, FilterInterface $filter = NULL
    )
    {
        parent::__construct($formatter, $filter);
        
        $this->stream = $stream;
        $this->flock  = (bool) $flock;
    }
    
    /**
     * Log an event to the output stream
     * 
     * @param array $vals A key-value array of formatted log record values
     * 
     * @return void
     */
    protected function emit(array $vals)
    {
        $this->lockWrite($vals['fmtMsg'] . PHP_EOL);
    }
    
    /**
     * Writes the log message subject to the object's flock property
     * 
     * @param string $msg The log message to be written
     * 
     * @return void
     */
    private function lockWrite($msg)
    {
        if ($this->flock) {
            flock($this->stream, LOCK_EX);
            fwrite($this->stream, $msg);
            flock($this->stream, LOCK_UN);
        } else {
            fwrite($this->stream, $msg);
        }
    }
}
