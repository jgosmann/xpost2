<?php

class WpInstaller {

    private $cacheDir;
    private $sqlExecutor;
    private $wpFetcher;
    private $wpConfigWriter;
    private $fsDelegate;

    function __construct($cacheDir, SqlExecutor $sqlExecutor,
            WpFetcher $wpFetcher, WpConfigWriter $wpConfigWriter,
            RecursingFsDelegate $fsDelegate) {
        $this->cacheDir = $cacheDir;
        $this->sqlExecutor = $sqlExecutor;
        $this->wpFetcher = $wpFetcher;
        $this->wpConfigWriter = $wpConfigWriter;
        $this->fsDelegate = $fsDelegate;
    }

    public function createInstallation($target, $version, WpConfig $config) {
        $this->removePreviousInstallation($target);
        $this->wpFetcher->fetchVersion($version, $this->cacheDir);
        $this->fsDelegate->copy($this->cacheDir, $target);
        $this->wpConfigWriter->write($config, joinPaths($target,
            WpConfigWriter::DEFAULT_CONFIGFILE));
        $this->createDatabaseAndGrantPermissions($config->getDbConfig());
    }

    private function removePreviousInstallation($target) {
        if ($this->fsDelegate->fileExists($target)) {
            $this->fsDelegate->unlink($target);
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
