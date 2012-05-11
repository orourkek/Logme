<?php

/**
 * Artax\Logger HandlerAbstract Class File
 * 
 * PHP version 5.4
 * 
 * @category     Logme
 * @package      Handlers
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */

namespace Logme\Handlers;
use Logme\Logger,
    Logme\EmitterAbstract,
    Logme\Formatter,
    Logme\FormatterInterface,
    Logme\FilterInterface,
    Logme\EmitterTrait,
    Logme\LogRecordInterface;

/**
 * Abstract Logging Handler Class
 *
 * @category     Logme
 * @package      Handlers
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
abstract class HandlerAbstract extends EmitterAbstract implements HandlerInterface
{
    /**
     * An optional message formatting object
     * @var Formatter
     */
    private $formatter;
    
    /**
     * Specifies the minimum severity level and formatting settings
     * 
     * @param FormatterInterface $formatter An optional msg/time formatter
     * @param FilterInterface    $filter    An optional message filter
     * 
     * @return void
     */
    public function __construct(FormatterInterface $formatter = NULL,
        FilterInterface $filter = NULL
    )
    {
        $this->filter    = $filter;
        $this->formatter = $formatter ?: new Formatter;
        $this->threshold = Logger::DEBUG;
    }
    
    /**
     * Handle a log event
     * 
     * @param LogRecordInterface $logRec A log record to handle
     * 
     * @return bool Returns TRUE if the log record could be handled and FALSE otherwise
     * 
     * @uses HandlerAbstract::getFormattedRecordArr
     * @uses HandlerAbstract::emit
     */
    public function handle(LogRecordInterface $logRec)
    {
        if ($this->canHandle($logRec)) {
            $this->emit($this->getFormattedRecordArr($logRec));
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * Output a record to the handler's target destination
     * 
     * @param array $vals A key-value array of formatted log record values
     */
    abstract protected function emit(array $vals);
    
    /**
     * Returns an array of log record values with formatting
     * 
     * @param LogRecordInterface $logRec A log record to format
     * 
     * @return array Returns a key-value array of formatted log record values
     * 
     * @used-by HandlerAbstract::handle
     */
    private function getFormattedRecordArr(LogRecordInterface $logRec)
    {
        $arr            = $logRec->getValsArr();
        $arr['fmtMsg']  = $this->formatter->formatMsg($logRec);
        $arr['fmtTime'] = $this->formatter->formatTime($logRec);
        
        return $arr;
    }
}
