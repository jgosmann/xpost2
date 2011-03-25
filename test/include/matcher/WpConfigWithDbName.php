<?php

class WpConfigWithDbName extends \Mockery\Matcher\MatcherAbstract {

    public function match($config) {
        return $this->_expected == $config->getDbConfig()->dbName;
    }

    public function __toString() {
        return '<WpConfigWithDbName[' . (string) $this->_expected . ']>';
    }

}

?>
