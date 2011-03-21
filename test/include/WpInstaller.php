<?php

class WpInstaller {

    private $cacheDir;
    private $wpFetcher;
    private $fsDelegate;

    function __construct($cacheDir, WpFetcher $wpFetcher = null,
            FsDelegate $fsDelegate = null) {
        $this->cacheDir = $cacheDir;
        $this->wpFetcher = $wpFetcher or new WpFetcher();
        $this->fsDelegate = $fsDelegate or new DefaultPhpFsDelegate();
    }

    public function createInstallation($target, $version, WpConfig $config) {
        $this->wpFetcher->fetchVersion($version, $this->cacheDir);
        $this->fsDelegate->copy($this->cacheDir, $target);
        $config->write($target);
    }
}

?>
