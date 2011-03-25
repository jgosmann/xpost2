<?php

require_once 'wp-installer/test/matcher/EqualsWpConfigExceptDbName.php';
require_once 'wp-installer/test/matcher/WpConfigWithDbName.php';

function equalsWpConfigExceptDbName($config) {
    return new EqualsWpConfigExceptDbName($config);
}

function wpConfigWithDbName($dbName) {
    return new WpConfigWithDbName($dbName);
}

?>
