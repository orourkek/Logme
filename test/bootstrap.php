<?php

/**
 * Unit Testing Bootstrap File
 * 
 * Registers an autoloader for Logme and vfsStream classes and initializes
 * an in-memory virtual file system.
 * 
 * @category   Logme
 * @package    Test
 * @author     Daniel Lowrey <rdlowrey@gmail.com>
 */

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;

define('LOGME_SYSDIR', dirname(__DIR__));
define('VFS_SYSDIR', LOGME_SYSDIR .'/vendor/vfsStream');

/*
 * --------------------------------------------------------------------
 * Register Logme & vfsStream autoloader
 * --------------------------------------------------------------------
 */

spl_autoload_register(function($cls) {
    if (0 === strpos($cls, 'Logme\\')) {
        $cls = str_replace('\\', '/', $cls);        
        require  LOGME_SYSDIR . "/src/$cls.php";
    } elseif (0 === strpos($cls, 'org\\bovigo\\vfs\\')) {
        $cls = str_replace('\\', '/', $cls);        
        require VFS_SYSDIR . "/src/main/php/$cls.php";
    }
});

/*
 * --------------------------------------------------------------------
 * Load virtual file system (vfsStream)
 * --------------------------------------------------------------------
 */

vfsStreamWrapper::register();
vfsStream::setup('log');
