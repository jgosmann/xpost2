<?php

require_once 'test/include/FsDelegate.php';
require_once 'test/include/DefaultPhpFsDelegate.php';

require_once 'test/include/FsException.php';

require_once 'include/helpers.php';

class RecursingFsDelegate implements FsDelegate {

    private $decorated;

    public function __construct(FsDelegate $toDecorate = null) {
        $this->decorated = $toDecorate or new DefaultPhpFsDelegate();
    }

    public function copy($src, $dest) {
        if ($this->isDir($dest)) {
            $dest = joinPaths($dest, $src);
        }

        if ($this->isDir($src)) {
            $this->copyDir($src, $dest);
        } else {
            $this->decorated->copy($src, $dest);
        }
    }

    public function createDir($path) {
        return $this->decorated->createDir($path);
    }

    public function fileExists($path) {
        return $this->decorated->fileExists($path);
    }

    public function isDir($path) {
        return $this->decorated->isDir($path);
    }

    public function readDir($path) {
        return $this->decorated->readDir($path);
    }

    public function removeDir($path) {
        foreach ($this->readDir($path) as $entry) {
            $this->unlink(joinPaths($path, $entry));
        }
        $this->decorated->removeDir($path);
    }

    public function unlink($path) {
        if ($this->isDir($path)) {
            $this->removeDir($path);
        } else {
            $this->decorated->unlink($path);
        }
    }

    private function copyDir($src, $dest) {
        $this->ensureDirExists($dest);
        foreach ($this->readDir($src) as $file) {
            $this->copy(joinPaths($src, $file), joinPaths($dest, $file));
        }
    }

    private function ensureDirExists($path) {
        if (!$this->fileExists($path)) {
            $this->createDir($path);
        }
    }

}

?>
