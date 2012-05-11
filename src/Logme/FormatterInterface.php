<?php

/**
 * FormatterInterface File
 * 
 * PHP version 5.4
 * 
 * @category     Logme
 * @package      Base
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
 
namespace Logme;

/**
 * Functionality for LogRecordInterface formatting classes
 *
 * @category     Logme
 * @package      Base
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
interface FormatterInterface
{
    /**
     * Formats a log message
     * 
     * @param LogRecordInterface $logRec A LogRecordInterface instance
     */
    public function formatMsg(LogRecordInterface $logRec);
    
    /**
     * Formats a log timestamp
     * 
     * @param LogRecordInterface $logRec A LogRecordInterface instance
     */
    public function formatTime(LogRecordInterface $logRec);
}
