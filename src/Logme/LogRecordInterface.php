<?php

/**
 * LogRecordInterface File
 * 
 * PHP version 5.4
 * 
 * @category     Logme
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
 
namespace Logme;
use Serializable;

/**
 * Provides an interface for log records
 * 
 * @category     Logme
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
interface LogRecordInterface extends Serializable
{
    /**
     * Access log record properties
     * 
     * @param string $prop A log record property name
     */
    public function fetch($prop);
    
    /**
     * Retrieve a key-value array of log record properties
     */
    public function getValsArr();
}
