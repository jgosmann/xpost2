<?php

class WpMultiInstaller {

    private $wpInstaller;
    private $dirPrefix;

    function __construct(WpInstaller $wpInstaller, $dirPrefix = 'wp') {
        $this->wpInstaller = $wpInstaller;
        $this->dirPrefix = $dirPrefix;
    }

    public function createInstallations($target, $version, $config, $count) {
        for ($i = 0; $i < $count; ++$i) {
            $this->wpInstaller->createInstallation(
                joinPaths($target, $this->dirPrefix . $i), $version, $config);
        }
    }
}

?>
