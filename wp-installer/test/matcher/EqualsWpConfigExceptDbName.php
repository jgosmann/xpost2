<?php

class EqualsWpConfigExceptDbName extends \Mockery\Matcher\MatcherAbstract {

    public function match($config) {
        return $this->isConfigExceptDbConfigEqual($config)
            && $this->isDbConfigExceptDbNameEqual($config);
    }

    public function isConfigExceptDbConfigEqual($config) {
        return $this->_expected->getTablePrefix() == $config->getTablePrefix()
            && $this->_expected->getLanguage() == $config->getLanguage()
            && $this->_expected->isDebugEnabled() == $config->isDebugEnabled();
    }

    public function isDbConfigExceptDbNameEqual($config) {
        $expected = $this->_expected->getDbConfig();
        $actual = $config->getDbConfig();
        return $expected->user == $actual->user
            && $expected->password == $actual->password
            && $expected->host == $actual->host
            && $expected->charset == $actual->charset
            && $expected->collate == $actual->collate;
    }

    public function __toString() {
        return '<EqualsWpConfigExceptDbName['
            . (string) $this->_expected . ']>';
    }
}

?>
