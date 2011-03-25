<?php

require_once 'test/include/FsDelegate.php';

class DefaultPhpFsDelegate implements FsDelegate {

    public function copy($src, $dest) {
        if (!copy($src, $dest)) {
            throw new FsException("Copying \"$src\" to \"$dest\" failed.");
        }
    }

    public function createDir($path) {
        if (mkdir($path)) {
            throw new FsException("Creating directory \"$path\" failed.");
        }
    }

    public function fileExists($path) {
        return file_exists($path);
    }

    public function isDir($path) {
        return is_dir($path);
    }

    public function readDir($path) {
        $dirHandle = opendir($path);
        if (!$dirHandle) {
            throw new FsException("Could not open directory \"$path\".");
        }

        $entries = array();
        while ($entry = readdir($dirHandle)) {
            $entries[] = $entry;
        }

        closedir($dirHandle);

        return $entries;
    }

    public function removeDir($path) {
        if (!rmdir($path)) {
            throw new FsException("Could not remove directory \"$path\".");
        }
    }

    public function unlink($path) {
        if (!unlink($path)) {
            throw new FsException("Could not unlink file \"$path\".");
        }
    }
}

?>
