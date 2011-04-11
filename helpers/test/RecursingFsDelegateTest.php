<?php

require_once 'wp-installer/test/initTestClassLoader.php';

require_once 'helpers/helpers.php';

use \Mockery as m;

class RecursingFsDelegateTest extends PHPUnit_Framework_TestCase {

    private $fsDelegate;
    private $recursingFsDelegate;

    public function setUp()
    {
        $this->fsDelegate = m::mock('FsDelegate');
        $this->fsDelegate->shouldReceive('isDir')->andReturn(false)
            ->byDefault();
        $this->fsDelegate->shouldReceive('fileExists')->andReturn(false)
            ->byDefault();
        $this->fsDelegate->shouldReceive('readDir')->andReturn(array())
            ->byDefault();

        $this->recursingFsDelegate = new RecursingFsDelegate($this->fsDelegate);
    }

    public function teardown()
    {
        m::close();
    }

    public function testCopiesSingleFile() {
        $src = 'src-file';
        $dest = 'dest-filt';

        $this->fsDelegate->shouldReceive('copy')->with($src, $dest)->once();

        $this->recursingFsDelegate->copy($src, $dest);
    }

    public function testCopiesDirRecursive() {
        $src = 'src-dir';
        $dest = 'dest-dir';
        $filesInDir = array('.', '..', '1', '2');

        $this->fsDelegate->shouldReceive('isDir')->with(m::not($src))
            ->andReturn(false);
        $this->fsDelegate->shouldReceive('isDir')->with($src)->andReturn(true);
        $this->fsDelegate->shouldReceive('fileExists')->with($dest)
            ->andReturn(false);
        $this->fsDelegate->shouldReceive('createDir')->with($dest)->once();
        $this->fsDelegate->shouldReceive('readDir')->with($src)
            ->andReturn($filesInDir);
        for ($i = 0; $i < count($filesInDir); ++$i) {
            $call = $this->fsDelegate->shouldReceive('copy')->with(
                    joinPaths($src, $filesInDir[$i]),
                    joinPaths($dest, $filesInDir[$i]));
            if ($filesInDir[$i] == '.' || $filesInDir[$i] == '..') {
                $call->never();
            } else {
                $call->once();
            }
        }

        $this->recursingFsDelegate->copy($src, $dest);
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

        $this->recursingFsDelegate->copy($src, $dest);
    }

    public function testCopiesDirIntoDir() {
        $src = 'somewhere/src-dir';
        $dest = 'dest-dir';
        $resultingSubpath = joinPaths($dest, 'src-dir');

        $this->fsDelegate->shouldReceive('isDir')->with($src)->andReturn(true);
        $this->fsDelegate->shouldReceive('isDir')->with($dest)->andReturn(true);
        $this->fsDelegate->shouldReceive('fileExists')->with(m::not($dest))
            ->andReturn(false);
        $this->fsDelegate->shouldReceive('fileExists')->with($dest)
            ->andReturn(true);
        $this->fsDelegate->shouldReceive('createDir')->with($resultingSubpath)
            ->once();

        $this->recursingFsDelegate->copy($src, $dest);
    }


    public function testUnlinkRemovesFile() {
        $file = 'file';

        $this->fsDelegate->shouldReceive('unlink')->with($file)->once();

        $this->recursingFsDelegate->unlink($file);
    }

    public function testUnlinkRemovesDirRecursive() {
        $dir = 'dir';
        $this->expectsRecursiveDeletionOfDir($dir);
        $this->recursingFsDelegate->unlink($dir);
    }

    public function testRemoveDirRemovesDirRecursive() {
        $dir = 'dir';
        $this->expectsRecursiveDeletionOfDir($dir);
        $this->recursingFsDelegate->removeDir($dir);
    }

    public function testRemoveDirDoesNotRemoveCurrentDirectory() {
        $dir = '.';
        $this->expectsDirNotToBeDeleted($dir);
        $this->recursingFsDelegate->removeDir($dir);
    }

    public function testRemoveDirDoesNotRemoveParentDirectory() {
        $dir = '..';
        $this->expectsDirNotToBeDeleted($dir);
        $this->recursingFsDelegate->removeDir($dir);
    }

    public function testRemoveDirDoesNotRemoveDotDirectory() {
        $dir = 'path/.';
        $this->expectsDirNotToBeDeleted($dir);
        $this->recursingFsDelegate->removeDir($dir);
    }

    public function testRemoveDirDoesNotRemoveDoubleDotDirectory() {
        $dir = 'path/path/..';
        $this->expectsDirNotToBeDeleted($dir);
        $this->recursingFsDelegate->removeDir($dir);
    }

    public function testRemoveDirDoesRemoveDirEndingWithDots() {
        $dir = 'path..';
        $this->expectsRecursiveDeletionOfDir($dir);
        $this->recursingFsDelegate->removeDir($dir);
    }

    public function testOtherFunctionsBeingForwarded() {
        $arg = 'dir';
        $ret = true;
        $this->genTestForwardingFunction('createDir', $arg);
        $this->genTestForwardingFunctionWithReturnValue('fileExists', $arg,
            $ret);
        $this->genTestForwardingFunctionWithReturnValue('isDir', $arg, $ret);
        $this->genTestForwardingFunctionWithReturnValue('readDir', $arg, $ret);
    }

    private function expectsRecursiveDeletionOfDir($dir) {
        $file = 'file';
        $pathToFile = joinPaths($dir, $file);

        $this->fsDelegate->shouldReceive('isDir')->with($dir)->andReturn(true);
        $this->fsDelegate->shouldReceive('isDir')->with($pathToFile)
            ->andReturn(false);
        $this->fsDelegate->shouldReceive('readDir')->with($dir)
            ->andReturn(array($file));
        $this->fsDelegate->shouldReceive('unlink')->with($pathToFile)->once();
        $this->fsDelegate->shouldReceive('removeDir')->with($dir)->once();
    }

    public function expectsDirNotToBeDeleted($dir) {
        $this->fsDelegate->shouldReceive('isDir')->with($dir)->andReturn(true);
        $this->fsDelegate->shouldReceive('removeDir')->with($dir)->never();
    }

    private function genTestForwardingFunction($function, $arg) {
        $this->fsDelegate->shouldReceive($function)->with($arg)->once();
        call_user_func(array($this->recursingFsDelegate, $function), $arg);
    }

    private function genTestForwardingFunctionWithReturnValue($function, $arg,
             $ret) {
         $this->fsDelegate->shouldReceive($function)->with($arg)->once()
            ->andReturn($ret);
         assertThat($ret, equalTo(call_user_func(
             array($this->recursingFsDelegate, $function), $arg)));
    }
}

?>
