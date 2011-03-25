<?php

require_once 'test/include/initTestClassLoader.php';

require_once 'include/helpers.php';

use \Mockery as m;

class WpMultiInstallerTest extends PHPUnit_Framework_TestCase {

    const dirPrefix = 'dirPrefix';

    private $wpInstaller;
    private $wpMultiInstaller;

    public function setUp() {
        $this->wpInstaller = m::mock('WpInstaller');
        $this->wpMultiInstaller = new WpMultiInstaller($this->wpInstaller,
            self::dirPrefix);
    }

    public function teardown()
    {
        m::close();
    }

    public function testCreatesRightAmountOfInstallations() {
        $count = 3;
        $this->wpInstaller->shouldReceive('createInstallation')
            ->with(m::any(), m::any(), m::any())->times($count);
        $this->wpMultiInstaller->createInstallations('target', 'version',
            new WpConfig(new DbConfig()), $count);
    }

    public function testFetchesRightWpVersion() {
        $version = '3.1';
        $this->wpInstaller->shouldReceive('createInstallation')
            ->with(m::any(), $version, m::any())->atLeast()->once();
        $this->wpMultiInstaller->createInstallations('target', $version,
            new WpConfig(new DbConfig()), 2);
    }

    public function testUsesMultipleTargetDirs() {
        $count = 3;
        $target = 't';
        for ($i = 0; $i < $count; ++$i) {
            $this->wpInstaller->shouldReceive('createInstallation')
                ->with(joinPaths($target, self::dirPrefix . $i),
                    m::any(), m::any())
                ->once();
        }
        $this->wpMultiInstaller->createInstallations($target, 'version',
            new WpConfig(new DbConfig()), $count);
    }

    public function testSuffixesDbNames() {
        $count = 3;
        $dbNamePrefix = 'wp';
        for ($i = 0; $i < $count; ++$i) {
            $this->wpInstaller->shouldReceive('createInstallation')
                ->with(m::any(), m::any(),
                    wpConfigWithDbName($dbNamePrefix . $i))
                ->once();
        }
        $this->wpMultiInstaller->createInstallations('target', 'version',
            new WpConfig(new DbConfig($dbNamePrefix)), $count);
    }

    public function testLeavesConfigUnalteredExceptDbName() {
        $config = new WpConfig(new DbConfig('dbName', 'user', 'password',
            'host', 'charset', 'collate'), 'tblPrefix', 'lang', true);
        $this->wpInstaller->shouldReceive('createInstallation')
            ->with(m::any(), m::any(), equalsWpConfigExceptDbName($config));
        $this->wpMultiInstaller->createInstallations('target', 'version',
            $config, 1);
    }

}



?>
