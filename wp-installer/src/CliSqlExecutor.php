<?php

class CliSqlExecutor implements SqlExecutor {

    private $userEscaped;
    private $mysqlPathEscaped;

    function __construct($userEscaped, $mysqlPathEscaped = 'mysql') {
        $this->userEscaped = escapeshellarg($userEscaped);
        $this->mysqlPathEscaped = escapeshellarg($mysqlPathEscaped);
    }

    public function getUser() {
        return $this->user;
    }

    public function exec($statement) {
        $retVal = 0;
        $output = system("$this->mysqlPath -u $this->user -p <<<"
            . escapeshellarg($statement), $retVal);
        if ($retVal != 0) {
            throw new SqlException($statement, $output);
        }
    }
}

?>
