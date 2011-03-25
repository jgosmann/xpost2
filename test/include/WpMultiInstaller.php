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
            $instanceConfig = $this->copyConfigReplacingDbName($config,
                $config->getDbConfig()->dbName . $i);
            $this->wpInstaller->createInstallation(
                joinPaths($target, $this->dirPrefix . $i), $version,
                $instanceConfig);
        }
    }

    private function copyConfigReplacingDbName(WpConfig $config, $dbName) {
        return new WpConfig(
            new DbConfig(
                $dbName,
                $config->getDbConfig()->user,
                $config->getDbConfig()->password,
                $config->getDbConfig()->host,
                $config->getDbConfig()->charset,
                $config->getDbConfig()->collate),
            $config->getTablePrefix(),
            $config->getLanguage(),
            $config->isDebugEnabled());
    }
}

?>
