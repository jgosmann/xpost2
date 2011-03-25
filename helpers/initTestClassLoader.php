<?php

/*
 * Include this file in test cases to have access to phpmock, hamcrest and 
 * helpers with only one include.
 */

require_once 'Mockery/Loader.php';
require_once 'Hamcrest/hamcrest.php';

$loader = new \Mockery\Loader;
$loader->register();

require_once 'helpers/src/helpers.php';

?>
