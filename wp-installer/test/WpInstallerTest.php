<?php

require_once 'wp-installer/test/initTestClassLoader.php';

require_once 'helpers/helpers.php';

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
    private $wpConfigWriter;
    private $bufferedSqlExecutor;
    private $sqlExecutor;

    public function setUp() {
        $this->wpFetcher = m::mock('WpFetcher');
        $this->fsDelegate = m::mock('RecursingFsDelegate');
        $this->wpConfig = new WpConfig(new DbConfig());
        $this->wpConfigWriter = m::mock('WpConfigWriter');
        $this->sqlExecutor = m::mock('SqlExecutor');
        $this->bufferedSqlExecutor =
            new BufferingSqlExecutor($this->sqlExecutor);

        $this->wpInstaller = new WpInstaller(self::cacheDir, 
            $this->bufferedSqlExecutor, $this->wpFetcher, $this->wpConfigWriter,
            $this->fsDelegate);
    }

    public function teardown()
    {
        $this->bufferedSqlExecutor->flush();
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
        $this->fsDelegate->shouldReceive('unlink')->with(self::target)
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
        $this->wpConfigWriter->shouldReceive('write')->with(
                m::type('WpConfig'),
                joinPaths(self::target, WpConfigWriter::DEFAULT_CONFIGFILE))
            ->once()->ordered('postCopy');
        $this->sqlExecutor->shouldReceive('exec')->with(
            '/DROP DATABASE IF EXISTS [^;]*;' .
            '\s*CREATE DATABASE [^;]*;' . '\s*GRANT [^;]*;/')
            ->once()->ordered('postCopy');
    }

}



?>
