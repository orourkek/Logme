<?php

use Logme\Logger,
    Logme\Formatter,
    Logme\LogRecord,
    Logme\LogRecordInterface,
    Logme\Handlers\NullHandler,
    Logme\Handlers\FileHandler,
    Logme\Handlers\PdoHandler,
    Logme\Handlers\ConsoleHandler;

spl_autoload_register(function($cls) {
    if (0 === strpos($cls, 'Logme\\')) {
        $cls = str_replace('\\', '/', $cls);        
        require  __DIR__ . "/src/$cls.php";
    }
});





// Load an empty virtual file system at vfs://log/
require 'vfsStream/vfsStream.php';
vfsStreamWrapper::register();
vfsStream::setup('log');

class Filter implements Logme\FilterInterface
{
    public function shouldMask(LogRecordInterface $logRecord)
    {
        return strstr($logRecord->fetch('msg'), 'info');
    }
}

// %(time) %(fmtTime) %(msg) %(fmtMsg) %(level) %(levelName) %(file) %(line) %(class) %(function)

$log      = new Logger;

$fmt      = new Formatter('[%(fmtTime)] %(msg) :: %(field)', 'Y-m-d H:i:s');
$console  = new ConsoleHandler($fmt, new Filter);
$log->addHandler($console);

$file     = 'vfs://log/log_extra_file.txt';
$fmt      = new Formatter('%(msg) on line %(line) in %(file)');
$fHandler = new FileHandler($file, 'w+', FALSE, $fmt);
$fHandler->setThreshold(Logger::INFO);
//$log->addHandler($fHandler);

$fixture  = __DIR__ . '/test/fixture/PdoHandler.sqlite';
$pdo      = new PDO("sqlite:$fixture");
$table    = 'log_table';
$fields   = [['fmtTime'=>'time'], ['fmtMsg'=>'msg'], 'level'];
$fmt      = new Formatter(NULL, 'Y-m-d H:i:s');
$pdoHndlr = new PdoHandler($pdo, $table, $fields, $fmt);
$pdoHndlr->setThreshold(Logger::WARNING);
$log->addHandler($pdoHndlr);


// log events
$log->debug('debug message', ['field'=>42]);
$log->info('info message', ['field'=>42]);
$log->warning('warning message', ['field'=>42]);

//echo file_get_contents($file);
