<?php

/**
 * Null Log Handler Class File
 * 
 * PHP version 5.4
 * 
 * @category     Logme
 * @package      Handlers
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */

namespace Logme\Handlers;

/**
 * A Null logging event handler that simply ignores log events
 *
 * @category     Logme
 * @package      Handlers
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
class NullHandler extends HandlerAbstract
{
    /**
     * Ignore log events
     * 
     * @param array $vals A key-value array of formatted log record values
     * 
     * @return void
     */
    protected function emit(array $vals)
    {
    }
}
