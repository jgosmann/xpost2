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
        if ($this->fsDelegate->isDir($src)) {
            foreach ($this->fsDelegate->readDir($src) as $file) {
                $this->copy(joinPaths($src, $file), joinPaths($dest, $file));
            }
        }
        else {
            if (!$this->fsDelegate->copy($src, $dest)) {
                throw new FsException("Copying \"$src\" to \"$dest\" failed.");
            }
        }
    }

}

