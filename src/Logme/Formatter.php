<?php

/**
 * Formatter Class File
 * 
 * PHP version 5.4
 * 
 * @category     Logme
 * @package      Base
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
 
namespace Logme;

/**
 * Log Formatter Class
 * 
 * ### Message Formatting
 * 
 * Message formatting strings expect field names to be templated in the
 * following format:
 * 
 * ```php
 * $msgFormat1 = '%(time) - %(msg)';
 * $msgFormat2 = '%(time) [%(levelName)] %(msg)';
 * $msgFormat3 = '%(level) - %(msg)';
 * ```
 * 
 * If no message format string is specified, the formatter defaults to:
 * 
 * ```php
 * $msgFormat = '%(msg)';
 * ```
 * 
 * Built-in fields include:
 * 
 * * %(msg)
 * * %(time)
 * * %(fmtTime)
 * * %(level)
 * * %(levelName)
 * * %(file)
 * * %(line)
 * * %(class)
 * * %(function)
 * 
 * You may also specify fields named in the `$extra` array passed to
 * log events in your templates. Consider the following:
 * 
 * ```php
 * use Logme\Logger, Logme\Formatter, Logme\Handlers\ConsoleHandler;
 * 
 * $fmt = new Formatter('%(msg) :: %(my_field)');
 * $log = new Logger;
 * $console = ConsoleHandler(Logger::DEBUG, $fmt);
 * $log->addHandler($console);
 * 
 * $log->debug('my debug message', array('my_field' => 42));
 * ```
 * 
 * The above code will result in the following console output:
 * 
 * ```
 * my debug message :: 42
 * ```
 * 
 * ### Timestamp Formatting
 * 
 * The `$dateFormat` string expects formatting characters of the form
 * accepted by PHP's `date` function:
 * 
 * http://php.net/manual/en/function.date.php
 * 
 * If no date formatting string is specified, the Formatter class will
 * return the unformatted unix timestamp.
 * 
 * ##### Examples
 * 
 * ```php
 * $fmt = new Formatter('%(timeFmt) %(msg)', 'Y-m-d H:i:s');
 * $fmt = new Formatter(NULL, 'H:i:s');
 * ```
 * 
 * @category     Logme
 * @package      Base
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
class Formatter implements FormatterInterface
{
    /**
     * Optional datetime formatting string
     * @var string
     */
    private $dateFormat;
    
    /**
     * Optional log message formatting string
     * @var string
     */
    private $msgFormat;
    
    /**
     * Initializes formatting settings
     * 
     * @param string $msgFormat  An optional message formatting string
     * @param string $dateFormat An optional timestamp formatting string
     * 
     * @return void
     */
    public function __construct($msgFormat = NULL, $dateFormat = NULL)
    {
        $this->msgFormat  = NULL === $msgFormat ? '%(msg)' : $msgFormat;
        $this->dateFormat = NULL === $dateFormat ? 'Y-m-d H:i:s' : $dateFormat;
    }
    
    /**
     * Formats a LogRecordInterface message for output
     * 
     * @param LogRecordInterface $logRec A LogRecordInterface object
     * 
     * @return string Returns a formatted log message string
     */
    public function formatMsg(LogRecordInterface $logRec)
    {
        $vals = $logRec->getValsArr();
        $vals['fmtTime'] = $this->formatTime($logRec);
        
        $keys = array_map(function($key){ return "%($key)"; }, array_keys($vals));
        $vals = array_values($vals);
        
        return str_replace($keys, $vals, $this->msgFormat);
    }
    
    /**
     * Formats a LogRecordInterface timestamp for output
     * 
     * @param LogRecordInterface $logRec A LogRecordInterface object
     * 
     * @return string Returns a formatted timestamp string
     */
    public function formatTime(LogRecordInterface $logRec)
    {
        return $this->dateFormat
            ? date($this->dateFormat, $logRec->fetch('time'))
            : $logRec->fetch('time');
    }
}
