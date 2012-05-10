<?php

/**
 * FilterInterface File
 * 
 * PHP version 5.4
 * 
 * @category     Logme
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
 
namespace Logme;

/**
 * FilterInterface
 * 
 * @category     Logme
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
interface FilterInterface
{
    /**
     * Provides advanced filtering of unwanted log record emission
     * 
     * @param LogRecordInterface $logRecord A log record to validate
     */
    public function shouldMask(LogRecordInterface $logRecord);
}
