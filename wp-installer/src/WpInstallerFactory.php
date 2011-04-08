<?php

class WpInstallerFactory {

    private $svnDelegate;
    private $sqlExecutor;

    private $fsDelegate;
    private $recusiveFsDelegate;

    private $wpFetcher;

    function __construct($svnDelegate, $sqlExecutor, $wpSvnUrl) {
        $this->svnDelegate = $svnDelegate;
        $this->sqlExecutor = $sqlExecutor;

        $this->fsDelegate = new DefaultPhpFsDelegate();
        $this->recursiveFsDelegate = new RecursingFsDelegate($this->fsDelegate);

        $this->wpFetcher = new WpFetcher($this->svnDelegate, $this->fsDelegate,
            $wpSvnUrl);
    }

    public function createWpInstaller($cacheDir, $saltGenerator) {
        $wpConfigWriter = new WpConfigWriter($saltGenerator);
        return new WpInstaller($cacheDir, $this->sqlExecutor, $this->wpFetcher,
            $wpConfigWriter, $this->recursiveFsDelegate);
    }

    public function createWpMultiInstaller($cacheDir, $saltGenerator,
            $dirPrefix) {
        return new WpMultiInstaller(
            $this->createWpInstaller($cacheDir, $saltGenerator), $dirPrefix);
    }

}

?>
