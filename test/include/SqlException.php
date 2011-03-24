<?php

class SqlException extends Exception {

    private $statement, $output;

    public function __construct($statement, $output) {
        $this->statement = $statement;
        $this->output = $output;
        parent::__construct("mysql: \"$statement\" failed:\n$output");
    }

    public function getStatement() {
        return $this->statement;
    }

    public function getOutput() {
        return $this->output;
    }
}

?>
