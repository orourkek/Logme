<?php

/**
 * Unit Testing Bootstrap File
 * 
 * Logme requires the vfsStream library for its unit tests. You can specify
 * the hard path to the vfsStream.php bootstrap file using the `VFS_PATH`
 * constant. If the constant is not specified the file is expected in the
 * PEAR include path. If you've installed vfsStream via PEAR you likely
 * won't need to specify the `VFS_PATH` constant.
 * 
 * vfsStream can be found at: https://github.com/mikey179/vfsStream
 * 
 * @category   Logme
 * @author     Daniel Lowrey <rdlowrey@gmail.com>
 */

// define('VFS_PATH', '/hard/path/to/vfsStream.php');
define('LOGME_SYSDIR', dirname(__DIR__));

spl_autoload_register(function($cls) {
    if (0 === strpos($cls, 'Logme\\')) {
        $cls = str_replace('\\', '/', $cls);        
        require  LOGME_SYSDIR . "/src/$cls.php";
    }
});


// Require vfsStream libs
$vfsPath = defined('VFS_PATH') ? VFS_PATH : 'vfsStream/vfsStream.php';
require $vfsPath;

// Load an empty virtual file system at vfs://log/
vfsStreamWrapper::register();
vfsStream::setup('log');
