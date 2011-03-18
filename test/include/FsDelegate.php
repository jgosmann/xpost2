<?php

interface FsDelegate {
    public function copy($src, $dest);
    public function isDir($path);
}

?>
