<?php

require_once 'test/include/initTestClassLoader.php';

require_once 'include/helpers.php';

use \Mockery as m;

class WpFetcherTest extends PHPUnit_Framework_TestCase {

    private $svnDelegate;
    private $fsDelegate;
    private $wpFetcher;

    const svnUrl = 'url';
    private $tagsUrl;

    public function __construct() {
        $this->tagsUrl = joinPaths(self::svnUrl, 'tags');
    }

    public function setUp() {
        $this->svnDelegate = m::mock('SvnDelegate');

        $this->fsDelegate = m::mock('FsDelegate');
        $this->fsDelegate->shouldReceive('fileExists')->with(m::any())
            ->andReturn(false)->byDefault();

        $this->wpFetcher = new WpFetcher(self::svnUrl, $this->svnDelegate,
            $this->fsDelegate);
    }

    public function teardown()
    {
        m::close();
    }

    public function testGetsLatestVersion() {
        $latestVersion = '3.1';

        $this->svnDelegate->shouldReceive('listRepo')->with($this->tagsUrl)
            ->once()->andReturn(array('1.0', '1.1', $latestVersion));

        $result = $this->wpFetcher->getLatestVersion();
        assertThat($result, equalTo($latestVersion));
    }

    public function testCheckoutAndUpdatesIfNoTargetIsNotSvnDir() {
        $version = '3.1';
        $target = 'dir';

        $this->svnDelegate->shouldReceive('checkout')->with(
            joinPaths($this->tagsUrl, $version), $target)->once()->ordered();
        $this->svnDelegate->shouldReceive('update')->with($target)->ordered();

        $this->wpFetcher->fetchVersion($version, $target);
    }

    public function testSwitchesAndUpdatesIfTargetIsSvnDir() {
        $version = '3.1';
        $target = 'dir';

        $this->fsDelegate->shouldReceive('fileExists')
            ->with(joinPaths($target, '.svn'))->andReturn(true);
        $this->svnDelegate->shouldReceive('switchRepo')->with(
            joinPaths($this->tagsUrl, $version), $target)->once()->ordered();
        $this->svnDelegate->shouldReceive('update')->with($target)->ordered();

        $this->wpFetcher->fetchVersion($version, $target);
    }

}


?>
