<?php

/**
 * ### CUSTOM LOG LEVELS
 * 
 * Logme exposes the following built-in log event severity levels:
 * 
 * debug        Logger::DEBUG       128
 * info         Logger::INFO        64
 * warning      Logger::WARNING     32
 * error        Logger::ERROR       16
 * critical     Logger::CRITICAL    1
 * 
 * Each of these levels corresponds to a magic method that may be used to
 * trigger the respective log event.
 * 
 * Logme also allows you to specify custom log severity levels using the
 * `Logger::addLevel` method. Custom log levels may not override either the
 * integer severity level or the name of a built-in level. Also note that
 * when you specify a custom level, the level name is converted to all
 * lowercase letters for the purpose of exposing the relevant magic method.
 */
 
use Logme\Logger,
    Logme\Formatter,
    Logme\Handlers\ConsoleHandler;

// Require the Logme bootstrap file
require dirname(__DIR__) . '/Logme.php';


$logger = new Logger;

$fmt = new Formatter('Level: %(level) (%(levelName)) - %(msg)');
$consoleHandler = new ConsoleHandler($fmt);
$logger->addHandler($consoleHandler);

$logger->addLevel(96, 'custom1');
$logger->addLevel(97, 'custom2');

$logger->debug('When in the chronicle of wasted time');
$logger->custom1('I see descriptions of the fairest wights');
$logger->custom2('And Beauty making beautiful old rhyme');
