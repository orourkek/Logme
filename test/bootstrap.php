<?php

define('LOGME_SYSDIR', dirname(__DIR__));

spl_autoload_register(function($cls) {
    if (0 === strpos($cls, 'Logme\\')) {
        $cls = str_replace('\\', '/', $cls);        
        require  LOGME_SYSDIR . "/src/$cls.php";
    }
});


// Load an empty virtual file system at vfs://log/
require 'vfsStream/vfsStream.php';
vfsStreamWrapper::register();
vfsStream::setup('log');
