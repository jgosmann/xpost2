<?php

class BufferingSqlExecutor implements SqlExecutor {

    private $decorated;
    private $statements = array();

    function __construct(SqlExecutor $toDecorate) {
        $this->decorated = $toDecorate;
    }

    public function __destruct() {
        try {
            if (count($this->statements) > 0) {
                $this->flush();
            }
        } catch(Exception $e) {
            trigger_error(
                "Exception during flush in SqlExecutor->__destruct():\n" .
                $e->getMessage() . "\n" . $e->getTraceAsString(),
                E_USER_WARNING);
        }
    }

    public function exec($statement) {
        $this->statements[] = $statement;
    }

    public function flush() {
        $this->decorated->exec(implode(';', $this->statements));
        $this->statements = array();
    }
}

?>
