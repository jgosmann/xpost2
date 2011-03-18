<?php

require_once 'Mockery/Loader.php';
require_once 'Hamcrest/hamcrest.php';

$loader = new \Mockery\Loader;
$loader->register();

require_once 'include/ClassLoader.php';

ClassLoader::addClassDir('include');
ClassLoader::addClassDir('test/include');

?>
