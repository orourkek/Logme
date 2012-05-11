<?php

/**
 * ### OUTPUT FORMATTING
 * 
 * By injecting a `FormatterInterface` into your handlers on instantiation
 * you can customize the format of your logging output. This can be vary 
 * helpful when you're logging to multiple destinations; you might not want
 * the same output format when logging to a file as you would when logging
 * messages to the console. Each individual handler can be configured with
 * its own formatting object (or none at all).
 * 
 * ###### Messages
 * 
 * `Formatter` objects take two constructor parameters: the first is for
 * formatting the "message" portion of your log output. Built-in message
 * formatting placeholders include:
 * 
 * * %(msg)         The original message passed to the log method
 * * %(time)        The unix timestamp when the log method was called
 * * %(fmtTime)     The formatted version of the timestamp
 * * %(level)       The integer log severity level
 * * %(levelName)   The textual name of the log level (debug, info, etc.)
 * * %(file)        The file where the log call originated
 * * %(line)        The line number where the log call originated
 * * %(class)       The class (if applicable) where the log call originated
 * * %(function)    The function/method (if applicable) where the log call originated
 * 
 * Custom placeholders may also be specified. Such values are expected to
 * match an array key in the additional `$custom` array parameter passed to
 * log methods. The example code below demonstrates the use of a custom formatting
 * placeholder.
 * 
 * ###### Time
 * 
 * The second parameter available in `Formatter::__construct` deals with 
 * formatting the timestamp of log event. This value accepts the same 
 * arguments as the php `date()` function specified here:
 * 
 * http://php.net/manual/en/function.date.php
 * 
 * @category   Logme
 * @author     Daniel Lowrey <rdlowrey@gmail.com>
 */
 
use Logme\Logger,
    Logme\Formatter,
    Logme\Handlers\ConsoleHandler;

// Require the Logme bootstrap file
require dirname(__DIR__) . '/Logme.php';

$fmt = new Formatter('%(fmtTime) - %(msg) My friend is %(friend).', 'Y-m-d H:i:s');
$consoleHandler = new ConsoleHandler($fmt); // Instantiate with a formatter
$logger = new Logger;
$logger->addHandler($consoleHandler);

$logger->info('Hello. My name is Inigo Montoya.', ['friend' => 'Fezzik']);
