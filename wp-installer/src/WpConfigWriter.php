<?php

use \PhpCodeGenerators as pcg;

class WpConfigWriter {

    const WP_VERSION = '3.1';

    const DEFAULT_CONFIGFILE = 'wp-config.php';

    private $saltGenerator;

    public function __construct(WpSaltGenerator $saltGenerator) {
        $this->saltGenerator = $saltGenerator;
    }

    public function getStringRepresentation(WpConfig $config) {
        return implode("\n", $this->getStatements($config));
    }

    public function write($config, $file) {
        file_put_contents($file, $this->getStringRepresentation($config));
    }

    protected function getStatements(WpConfig $config) {
        return array(
            $this->getHeader(),

            pcg::asDefine('DB_NAME', $config->getDbConfig()->dbName),
            pcg::asDefine('DB_USER', $config->getDbConfig()->user),
            pcg::asDefine('DB_PASSWORD', $config->getDbConfig()->password),
            pcg::asDefine('DB_HOST', $config->getDbConfig()->host),
            pcg::asDefine('DB_CHARSET', $config->getDbConfig()->charset),
            pcg::asDefine('DB_COLLATE', $config->getDbConfig()->collate),

            $this->saltGenerator->getSaltDefines(),

            pcg::asAssignment('table_prefix', $config->getTablePrefix()),
            pcg::asDefine('WPLANG', $config->getLanguage()),
            pcg::asDefine('WP_DEBUG', $config->isDebugEnabled()),

            $this->getFooter());
    }

    protected function getHeader() {
        return '<?php';
    }

    protected function getFooter() {
        return <<<EOT
if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
EOT;
    }
}

?>
