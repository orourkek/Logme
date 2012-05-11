<?php

/**
 * ### Basic Logme Usage
 * 
 * The most basic Logme functionality requires only that you instantiate a
 * `Logger` object and attach a log event handler. You may attach as many
 * handlers as you like to the logger. The `Logger` then passes log events
 * to the attached handlers who determine whether they should emit or ignore
 * the event.
 * 
 * ###### Logging Thresholds
 * 
 * All `Logger` objects and handlers default to a minimum threshold level
 * of `Logger::DEBUG`. This means that any log event of "debug" or higher
 * severity will be handled. Both loggers and handlers expose the `setThreshold()`
 * method for adjusting this minimum severity level.
 * 
 * In the below example, see that because we've used `StreamHandler::setThreshold`
 * to elevate the threshold that handler only emits for log events of 
 * `Logger::WARNING` and above.
 * 
 * Note that if you set the threshold level for the master `Logger` instance
 * it prevents *any* handlers from handling log events below the specified
 * `Logger` threshold.
 * 
 * For more complex handling using level masks and filters, see the example
 * file `masks_and_filters.php`.
 * 
 * @category   Logme
 * @author     Daniel Lowrey <rdlowrey@gmail.com>
 */
 
use Logme\Logger,
    Logme\Handlers\ConsoleHandler,
    Logme\Handlers\StreamHandler;

// Require the Logme bootstrap file
require dirname(__DIR__) . '/Logme.php';

$logger         = new Logger;
$consoleHandler = new ConsoleHandler;
$logger->addHandler($consoleHandler);

$tmpStream      = fopen('php://temp', 'r+');
$streamHandler  = new StreamHandler($tmpStream);
$streamHandler->setThreshold(Logger::WARNING);
$logger->addHandler($streamHandler);

// Let us know what's happening
echo PHP_EOL . '--- ConsoleHandler Logging Output ---' . PHP_EOL;

$logger->debug('Pan-galactic Gargle Blaster!');
$logger->warning('I wish I had my towel.');

// At this point you will have seen both log events written to the console.
// Just to demonstrate that we did, in fact, write to the stream handler:
echo PHP_EOL . '--- StreamHandler Logging Output ---' . PHP_EOL;
rewind($tmpStream);
echo stream_get_contents($tmpStream) . PHP_EOL;
