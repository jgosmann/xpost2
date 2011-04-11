<?php

require_once 'config-test-env.php';

require_once 'helpers/helpers.php';

for ($i = 0; $i < WP_INST_COUNT; $i++) {
    $file = joinPaths(TEST_ENV_DATADIR, WP_INST_PREFIX . $i . '.sql');
    dumpDbToFile(DB_NAME_PREFIX . $i, $file);
}


function dumpDbToFile($db, $file) {
    $mysqldump = escapeshellarg(MYSQL_DUMP_BIN);
    $user = escapeshellarg(DB_USER);
    $password = escapeshellarg('--password=' . DB_PASSWORD);
    $db = escapeshellarg($db);
    $file = escapeshellarg($file);

    $retVal = 0;
    system("$mysqldump -u $user $password $db > $file", $retVal);
    if ($retVal != 0) {
        echo gettext(<<<EOT
Failed to create dump from database $db to file $file with user $user.

EOT
        );
    }
}

?>
