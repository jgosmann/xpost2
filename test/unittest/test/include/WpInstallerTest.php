<?php

require_once 'test/include/initTestClassLoader.php';

require_once 'include/helpers.php';

use \Mockery as m;

class WpInstallerTest extends PHPUnit_Framework_TestCase {

    const installDir = 'install';
    const cacheDir = 'install/.cache';
    const target = 'install/instance';
    const version = '3.1';

    private $wpFetcher;
    private $fsDelegate;
    private $wpInstaller;
    private $wpConfig;

    public function setUp() {
        $this->wpFetcher = m::mock('WpFetcher');
        $this->fsDelegate = m::mock('RecursingFsDelegate');
        $this->wpConfig = m::mock('WpConfig');
        $this->wpInstaller = new WpInstaller(self::cacheDir, $this->wpFetcher,
            $this->fsDelegate);
    }

    public function teardown()
    {
        m::close();
    }

    public function testCreatesInstallation() {
        $this->expectInstallation();
        $this->fsDelegate->shouldReceive('fileExists')->with(self::target)
            ->andReturn(false);
        $this->wpInstaller->createInstallation(
            self::target, self::version, $this->wpConfig);
    }

    public function testRemovesOldInstallation() {
        $this->fsDelegate->shouldReceive('fileExists')->with(self::target)
            ->andReturn(true);
        $this->fsDelegate->shouldReceive('remove')->with(self::target)
            ->once()->ordered();
        $this->expectInstallation();

        $this->wpInstaller->createInstallation(
            self::target, self::version, $this->wpConfig);
    }

    private function expectInstallation() {
        $this->wpFetcher->shouldReceive('fetchVersion')
            ->with(self::version, self::cacheDir)->once()->ordered();
        $this->fsDelegate->shouldReceive('copy')
            ->with(self::cacheDir, self::target)->once()->ordered();
        $this->wpConfig->shouldReceive('write')
            ->with(self::target)->once()->ordered();
    }

}



?>
