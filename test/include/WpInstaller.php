<?php

class WpInstaller {

    private $cacheDir;
    private $wpFetcher;
    private $fsDelegate;

    function __construct($cacheDir, WpFetcher $wpFetcher = null,
            RecursingFsDelegate $fsDelegate = null) {
        $this->cacheDir = $cacheDir;
        $this->wpFetcher = $wpFetcher or new WpFetcher();
        $this->fsDelegate = $fsDelegate or new RecursingFsDelegate();
    }

    public function createInstallation($target, $version, WpConfig $config) {
        $this->removePreviousInstallation($target);
        $this->wpFetcher->fetchVersion($version, $this->cacheDir);
        $this->fsDelegate->copy($this->cacheDir, $target);
        $config->write($target);
    }

    private function removePreviousInstallation($target) {
        if ($this->fsDelegate->fileExists($target)) {
            $this->fsDelegate->remove($target);
        }
    }
}

?>
