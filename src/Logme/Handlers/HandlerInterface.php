<?php

/**
 * Artax HandlerInterface File
 * 
 * PHP version 5.4
 * 
 * @category     Logme
 * @package      Handlers
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */

namespace Logme\Handlers;
use Logme\EmitterInterface,
    Logme\LogRecordInterface;

/**
 * Specifies an interface for log event handlers
 *
 * @category     Logme
 * @package      Handlers
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
interface HandlerInterface extends EmitterInterface
{
    /**
     * Handle a log event
     * 
     * @param LogRecordInterface $logRec A log record to emit
     */
    public function handle(LogRecordInterface $logRec);
}
