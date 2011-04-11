<?php

require_once 'config-test-env.php';

require_once 'wp-installer/wp-installer.php';

$fsDelegate = new RecursingFsDelegate(new DefaultPhpFsDelegate());

if (!$fsDelegate->isDir(TEST_ENV_DATADIR)) {
    $fsDelegate->createDir(TEST_ENV_DATADIR);
}

?>
