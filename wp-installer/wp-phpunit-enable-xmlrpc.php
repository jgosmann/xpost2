<?php

/* This script activates XML-RPC posting after creating Wordpress
 * installations with wp-installer/wp-cli-multi-install.php and 
 * wp-installer/wp-phpunit-db-install.php. Execute this
 * script with phpunit.
 *
 * This script needs the following to be installed:
 * - PHP Unit Testing Framework <https://github.com/sebastianbergmann/phpunit/>
 * - MySQL <http://mysql.com/>
 * - A webserver, e.g. Apache HTTP Server <http://httpd.apache.org/>
 * - Selenium Server <http://seleniumhq.org/>
 * - A browser compatible with Selenium, e.g. Firefox <http://www.mozilla.org/>
 *
 * Make sure ...
 * - that phpunit is in the search path.
 * - that you copied wp-installer/sample-config-wp-installer.php to
 *   config-wp-installer.php and filled in the correct settings.
 * - that you have your webserver, MySQL and Selenium running.
 */

require_once 'config-wp-installer.php';

require_once 'PHPUnit/Extensions/SeleniumTestCase.php';
require_once 'Hamcrest/hamcrest.php';

require_once 'helpers/helpers.php';

class WpPhpunitDbInstaller extends PHPUnit_Extensions_SeleniumTestCase {

    public function setUp() {
        $this->setBrowser(SELENIUM_BROWSER);
        $this->setBrowserUrl(HTDOCS_URL);
    }

    /**
     * @dataProvider installationsProvider
     */
    public function testEnableXmlRpc($index) {
        $instName = WP_INST_PREFIX . $index;
        $this->loginToInstallation($instName);

        $this->enableXmlRpcInInstallation($instName);

        $this->assertEquals("on", $this->getValue("enable_xmlrpc"));
    }

    public function installationsProvider() {
        $installations = array();
        for ($i = 0; $i < WP_INST_COUNT; ++$i) {
            $installations[] = array($i);
        }
        return $installations;
    }

    private function loginToInstallation($instName) {
        $this->open(joinPaths($instName, '/wp-login.php'));
        $this->type("user_login", "admin");
        $this->type("user_pass", WP_USER_PASS);
        $this->click("wp-submit");
        $this->waitForPageToLoad(SELENIUM_TIMEOUT);
    }

    private function enableXmlRpcInInstallation($instName) {
        $this->open(joinPaths($instName, '/wp-admin/options-writing.php'));
        $this->check("enable_xmlrpc");
        $this->click("submit");
        $this->waitForPageToLoad(SELENIUM_TIMEOUT);
    }
}

?>
