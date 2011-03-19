<?php

require_once 'test/include/initTestClassLoader.php';

require_once 'include/helpers.php';

use \Mockery as m;

class FsManipulatorTest extends PHPUnit_Framework_TestCase {

    private $fsDelegate;
    private $fsManipulator;

    public function setUp()
    {
        $this->fsDelegate = m::mock('FsDelegate');
        $this->fsDelegate->shouldReceive('isDir')->andReturn(false)
            ->byDefault();
        $this->fsDelegate->shouldReceive('fileExists')->andReturn(false)
            ->byDefault();
        $this->fsDelegate->shouldReceive('readDir')->andReturn(array())
            ->byDefault();

        $this->fsManipulator = new FsManipulator($this->fsDelegate);
    }

    public function teardown()
    {
        m::close();
    }

    public function testCopiesSingleFile() {
        $src = 'src-file';
        $dest = 'dest-filt';

        $this->fsDelegate->shouldReceive('copy')->with($src, $dest)->once();

        $this->fsManipulator->copy($src, $dest);
    }

    public function testCopiesDirRecursive() {
        $src = 'src-dir';
        $dest = 'dest-dir';
        $filesInDir = array('1', '2');

        $this->fsDelegate->shouldReceive('isDir')->with(m::not($src))
            ->andReturn(false);
        $this->fsDelegate->shouldReceive('isDir')->with($src)->andReturn(true);
        $this->fsDelegate->shouldReceive('fileExists')->with($dest)
            ->andReturn(false);
        $this->fsDelegate->shouldReceive('createDir')->with($dest)->once();
        $this->fsDelegate->shouldReceive('readDir')->with($src)
            ->andReturn($filesInDir);
        for ($i = 0; $i < count($filesInDir); ++$i) {
            $this->fsDelegate->shouldReceive('copy')->with(
                    joinPaths($src, $filesInDir[$i]),
                    joinPaths($dest, $filesInDir[$i]))
                ->once();
        }

        $this->fsManipulator->copy($src, $dest);
    }

    public function testCopiesFileIntoDir() {
        $src = 'src-file';
        $dest = 'dest-dir';

        $this->fsDelegate->shouldReceive('isDir')->with($src)->andReturn(false);
        $this->fsDelegate->shouldReceive('isDir')->with($dest)->andReturn(true);
        $this->fsDelegate->shouldReceive('fileExists')->with($dest)
            ->andReturn(true);
        $this->fsDelegate->shouldReceive('copy')->with(
            $src, joinPaths($dest, $src))->once();

        $this->fsManipulator->copy($src, $dest);
    }

    public function testCopiesDirIntoDir() {
        $src = 'src-dir';
        $dest = 'dest-dir';
        $resultingSubpath = joinPaths($dest, $src);

        $this->fsDelegate->shouldReceive('isDir')->with($src)->andReturn(true);
        $this->fsDelegate->shouldReceive('isDir')->with($dest)->andReturn(true);
        $this->fsDelegate->shouldReceive('fileExists')->with(m::not($dest))
            ->andReturn(false);
        $this->fsDelegate->shouldReceive('fileExists')->with($dest)
            ->andReturn(true);
        $this->fsDelegate->shouldReceive('createDir')->with($resultingSubpath)
            ->once();

        $this->fsManipulator->copy($src, $dest);
    }



    public function testDoesNothingIfEnsuresExistentDirExists() {
        $dir = 'dir';

        $this->fsDelegate->shouldReceive('fileExists')->with($dir)
            ->andReturn(true);

        $this->fsManipulator->ensureDirExists($dir);
    }

    public function testCreatesDirIfEnsuresNonexistentDirExists() {
        $dir = 'dir';

        $this->fsDelegate->shouldReceive('fileExists')->with($dir)
            ->andReturn(false);
        $this->fsDelegate->shouldReceive('createDir')->with($dir)->once();

        $this->fsManipulator->ensureDirExists($dir);
    }



    public function testRemovesFile() {
        $file = 'file';

        $this->fsDelegate->shouldReceive('unlink')->with($file)->once();

        $this->fsManipulator->remove($file);
    }

    public function testRemovesDirRecursive() {
        $dir = 'dir';
        $file = 'file';
        $pathToFile = joinPaths($dir, $file);

        $this->fsDelegate->shouldReceive('isDir')->with($dir)->andReturn(true);
        $this->fsDelegate->shouldReceive('isDir')->with($pathToFile)
            ->andReturn(false);
        $this->fsDelegate->shouldReceive('readDir')->with($dir)
            ->andReturn(array($file));
        $this->fsDelegate->shouldReceive('unlink')->with($pathToFile)->once();
        $this->fsDelegate->shouldReceive('removeDir')->with($dir)->once();

        $this->fsManipulator->remove($dir);
    }

}

?>
