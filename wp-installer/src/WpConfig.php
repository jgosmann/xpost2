<?php

class WpConfig {

    private $dbConfig;
    private $tablePrefix;
    private $language;
    private $debugEnabled;

    function __construct(DbConfig $dbConfig,
            $tablePrefix = 'wp_', $language = '', $debugEnabled = false) {
        $this->dbConfig = $dbConfig;
        $this->tablePrefix = $tablePrefix;
        $this->language = $language;
        $this->debugEnabled = $debugEnabled;
    }

    public function getDbConfig() {
        return $this->dbConfig;
    }

    public function getTablePrefix() {
        return $this->tablePrefix;
    }

    public function getLanguage() {
        return $this->language;
    }

    public function isDebugEnabled() {
        return $this->debugEnabled;
    }
}

?>
