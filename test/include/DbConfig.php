<?php

class DbConfig {
    public $dbName;
    public $user;
    public $host;

    function __construct($dbName = '', $user = '', $host = 'localhost') {
        $this->dbName = $dbName;
        $this->user = $user;
        $this->host = $host;
    }
}

?>
