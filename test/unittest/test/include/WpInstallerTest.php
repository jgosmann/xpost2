<?php

require_once 'test/include/initTestClassLoader.php';

require_once 'include/helpers.php';

use \Mockery as m;

class WpInstallerTest extends PHPUnit_Framework_TestCase {

    const installDir = 'install';
    const cacheDir = 'install/.cache';

    private $wpFetcher;
    private $fsDelegate;
    private $wpInstaller;

    public function setUp() {
        $this->wpFetcher = m::mock('WpFetcher');
        $this->fsDelegate = m::mock('FsDelegate');
        $this->wpInstaller = new WpInstaller(self::cacheDir, $this->wpFetcher,
            $this->fsDelegate);
    }

    public function teardown()
    {
        m::close();
    }

    public function testCreatesInstallation() {
        $version = '3.1';
        $target = joinPaths(self::installDir, 'inst');
        $config = m::mock('WpConfig');

        $this->wpFetcher->shouldReceive('fetchVersion')
            ->with($version, self::cacheDir)->once()->ordered();
        $this->fsDelegate->shouldReceive('copy')->with(self::cacheDir, $target)
            ->once()->ordered();
        $config->shouldReceive('write')->with($target)->once()->ordered();

        $this->wpInstaller->createInstallation($target, $version, $config);
    }

    public function testRemovesOldInstallation() {
        // TODO
    }

}



?>
