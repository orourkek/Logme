<?php

/**
 * FileHandler Class File
 * 
 * PHP version 5.4
 * 
 * @category     Logme
 * @package      Handlers
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */

namespace Logme\Handlers;
use Logme\FormatterInterface;

/**
 * Logs events to a specified file
 * 
 * @category     Logme
 * @package      Handlers
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
class FileHandler extends StreamHandler {
    
    /**
     * Assigns the stream to which log events will be written
     * 
     * @param string             $file      Where log events will be written
     * @param string             $mode      A standard fopen file mode flag
     * @param bool               $flock     Whether or not a file lock should be
     *                                      obtained when writing to the log file
     * @param FormatterInterface $formatter An optional formatting object
     * @param FilterInterface    $filter    An optional filtering object  
     * 
     * @return void
     */
    public function __construct($file, $mode = 'a', $flock = FALSE,
        FormatterInterface $formatter = NULL, FilterInterface $filter = NULL
    )
    {
        $mode   = is_null($mode) ? 'a' : $mode;
        $stream = fopen($file, $mode);
        
        parent::__construct($stream, $flock, $formatter, $filter);
    }
}
