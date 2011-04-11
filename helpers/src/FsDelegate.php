<?php

interface FsDelegate {
    public function copy($src, $dest);
    public function createDir($path);
    public function fileExists($path);
    public function isDir($path);
    public function readDir($path);
    public function removeDir($path);
    public function unlink($path);
}

?>
