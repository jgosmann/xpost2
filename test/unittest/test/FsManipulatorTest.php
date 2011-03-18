<?php

require_once 'test/include/initTestClassLoader.php';

require_once 'include/helpers.php';

use \Mockery as m;

class FsManipulatorCopyTest extends PHPUnit_Framework_TestCase {

    private $fsDelegate;
    private $fsManipulator;

    public function setUp()
    {
        $this->fsDelegate = m::mock('FsDelegate');
        $this->fsDelegate->shouldReceive('isDir')->andReturn(false)
            ->byDefault();

        $this->fsManipulator = new FsManipulator($this->fsDelegate);
    }

    public function teardown()
    {
        m::close();
    }

    public function testCopiesSingleFile() {
        $src = 'src-file.txt';
        $dest = 'dest-filt.txt';

        $this->fsDelegate->shouldReceive('copy')->with($src, $dest)->once()
            ->andReturn(true);

        $this->fsManipulator->copy($src, $dest);
    }

    /**
     * @expectedException FsException
     */
    public function testThrowsExceptionIfCopyFails() {
        $src = 'src-file.txt';
        $dest = 'dest-file.txt';

        $this->fsDelegate->shouldReceive('copy')->with($src, $dest)->once()
            ->andReturn(false);

        $this->fsManipulator->copy($src, $dest);
    }

    public function testCopiesDirRecursive() {
        $src = 'src-dir';
        $dest = 'dest-dir';
        $filesInDir = array('1.txt', '2.txt');

        $this->fsDelegate->shouldReceive('isDir')
            ->with(m::notAnyOf($src, $dest))->andReturn(false);
        $this->fsDelegate->shouldReceive('isDir')->with(m::anyOf($src, $dest))
            ->andReturn(true);
        $this->fsDelegate->shouldReceive('readDir')->with($src)
            ->andReturn($filesInDir);
        for ($i = 0; $i < count($filesInDir); ++$i) {
            $this->fsDelegate->shouldReceive('copy')->with(
                    joinPaths($src, $filesInDir[$i]),
                    joinPaths($dest, $filesInDir[$i]))
                ->once()->andReturn(true);
        }

        $this->fsManipulator->copy($src, $dest);
    }

    public function testThrowsExceptionIfCopyingDirToFile() {
        // TODO
    }

    public function testCreatesDirIfCopyingDirToNonExistentDir() {
        // TODO
    }

}

?>
