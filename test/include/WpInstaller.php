<?php

class WpInstaller {

    private $cacheDir;
    private $sqlExecutor;
    private $wpFetcher;
    private $fsDelegate;

    function __construct($cacheDir, SqlExecutor $sqlExecutor,
            WpFetcher $wpFetcher = null,
            RecursingFsDelegate $fsDelegate = null) {
        $this->cacheDir = $cacheDir;
        $this->sqlExecutor = $sqlExecutor;
        $this->wpFetcher = $wpFetcher or new WpFetcher();
        $this->fsDelegate = $fsDelegate or new RecursingFsDelegate();
    }

    public function createInstallation($target, $version, WpConfig $config) {
        $this->removePreviousInstallation($target);
        $this->wpFetcher->fetchVersion($version, $this->cacheDir);
        $this->fsDelegate->copy($this->cacheDir, $target);
        $config->write($target);
        $this->createDatabaseAndGrantPermissions($config->getDbConfig());
    }

    private function removePreviousInstallation($target) {
        if ($this->fsDelegate->fileExists($target)) {
            $this->fsDelegate->remove($target);
        }
    }

    private function createDatabaseAndGrantPermissions($dbConfig) {
        $this->sqlExecutor->exec(<<<EOT
            DROP DATABASE IF EXISTS `$dbConfig->dbName`;
            CREATE DATABASE `$dbConfig->dbName`;
            GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER,
                CREATE TEMPORARY TABLES, CREATE VIEW, EVENT, TRIGGER, SHOW VIEW,
                CREATE ROUTINE, ALTER ROUTINE, EXECUTE
                ON `$dbConfig->dbName` . *
                TO '$dbConfig->user'@'$dbConfig->host';
EOT
        );
    }
}

?>
