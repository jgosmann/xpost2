<?php

class CliSqlExecutor implements SqlExecutor {

    private $userEscaped;
    private $mysqlPathEscaped;

    function __construct($user, $mysqlPath = 'mysql') {
        $this->userEscaped = escapeshellarg($user);
        $this->mysqlPathEscaped = escapeshellarg($mysqlPath);
    }

    public function getUser() {
        return $this->user;
    }

    public function exec($statement) {
        $retVal = 0;
        $output = system("$this->mysqlPathEscaped -u $this->userEscaped -p <<<"
            . escapeshellarg($statement), $retVal);
        if ($retVal != 0) {
            throw new SqlException($statement, $output);
        }
    }
}

?>
