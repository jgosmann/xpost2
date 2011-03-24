<?php

class DbConfig {
    public $dbName;
    public $user;
    public $password;
    public $host;
    public $charset;
    public $collate;

    function __construct($dbName = '', $user = '', $password = '',
            $host = 'localhost', $charset = 'utf-8', $collate = '') {
        $this->dbName = $dbName;
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->charset = $charset;
        $this->collate = $collate;
    }
}

?>
