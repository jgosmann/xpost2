<?php

require_once 'test/include/FsDelegate.php';

class DefaultPhpFsDelegate implements FsDelegate {

    public function isDir($path) {
        return is_dir($path);
    }

    public function copy($src, $dest) {
        return copy($src, $dest);
    }
}

?>
