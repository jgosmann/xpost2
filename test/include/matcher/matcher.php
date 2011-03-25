<?php

require_once 'test/include/matcher/EqualsWpConfigExceptDbName.php';
require_once 'test/include/matcher/WpConfigWithDbName.php';

function equalsWpConfigExceptDbName($config) {
    return new EqualsWpConfigExceptDbName($config);
}

function wpConfigWithDbName($dbName) {
    return new WpConfigWithDbName($dbName);
}

?>
