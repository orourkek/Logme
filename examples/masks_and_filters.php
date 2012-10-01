<?php

/**
 * ### Masks and Filters
 * 
 * Loggers and handlers allow you to mask individual log severity levels in
 * addition to the minimum threshold setting. You can also specify custom
 * filters at the handler or logger level.
 * 
 * ###### Masks
 * 
 * Use the `mask` method to prevent a specific log severity level from
 * resulting in an emission by a handler (or the logger). Once a mask is set
 * it can be removed by making a congrous call to the `unmask` method on the
 * same object.
 * 
 * ###### Filters
 * 
 * Custom filters can be applied by specifying an object implementing the
 * `FilterInterface`. If the `FilterInterface::shouldMask` returns a truthy
 * value the handler (or logger) will ignore the log event. A check of any
 * injected filters is performed in addition to mask and threshold checking
 * before a log event is emitted by a handler.
 */
 
use Logme\Logger,
    Logme\FilterInterface,
    Logme\LogRecordInterface,
    Logme\Handlers\ConsoleHandler;

// Require the Logme bootstrap file
require dirname(__DIR__) . '/Logme.php';

// An example filter class
class MyFilter implements FilterInterface
{
    public function shouldMask(LogRecordInterface $logRec)
    {
        $msg = $logRec->fetch('msg');
        return strstr($msg, 'wind');
    }
}

$consoleHandler = new ConsoleHandler(NULL, new MyFilter); // Instantiate with a filter
$consoleHandler->mask(Logger::ERROR);

$logger = new Logger;
$logger->setThreshold(Logger::INFO);
$logger->addHandler($consoleHandler);


$logger->debug('A Lannister always pays his debts.'); // Below threshold
$logger->info('Words are wind.');                     // Filtered
$logger->warning('Winter is coming.');                // <--- will emit
$logger->error('You know nothing, John Snow.');       // Masked
$logger->critical('The North remembers.');            // <--- will emit

