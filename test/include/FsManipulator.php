<?php

require_once 'test/include/FsDelegate.php';
require_once 'test/include/DefaultPhpFsDelegate.php';

require_once 'test/include/FsException.php';

require_once 'include/helpers.php';

class FsManipulator {

    private $fsDelegate;

    public function __construct(FsDelegate $fsDelegate) {
        $this->fsDelegate = $fsDelegate;
    }

    public function copy($src, $dest) {
        if ($this->fsDelegate->isDir($dest)) {
            $dest = joinPaths($dest, $src);
        }

        if ($this->fsDelegate->isDir($src)) {
            $this->ensureDirExists($dest);
            foreach ($this->fsDelegate->readDir($src) as $file) {
                $this->copy(joinPaths($src, $file), joinPaths($dest, $file));
            }
        }
        else {
            $this->fsDelegate->copy($src, $dest);
        }
    }

    public function ensureDirExists($path) {
        if (!$this->fsDelegate->fileExists($path)) {
            $this->fsDelegate->createDir($path);
        }
    }

}

