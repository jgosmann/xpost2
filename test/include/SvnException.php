<?php

class SvnException extends Exception {

    private $args, $output;

    public function __construct($args, $output) {
        $this->args = $args;
        $this->output = $output;
        $argString = implode(' ', $args);
        parent::__construct("\"svn $argString\" failed:\n$output");
    }

    public function getArgs() {
        return $this->args;
    }

    public function getOutput() {
        return $this->output;
    }
}

?>
