<?php

/* This script does the final database installation after creating Wordpress
 * installations with wp-installer/wp-cli-multi-install.php. Execute this
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
    public function testInstallWordpress($index) {
        $instName = WP_INST_PREFIX . $index;
        $this->open(joinPaths($instName, '/wp-admin/install.php'));

        $this->type('weblog_title', $instName);
        $this->type('pass1', WP_USER_PASS);
        $this->type('pass2', WP_USER_PASS);
        $this->type('admin_email', WP_USER_MAIL);
        $this->click('blog_public');
        $this->click('Submit');
        $this->waitForPageToLoad(SELENIUM_TIMEOUT);

        assertThat($this->isTextPresent("Success"));
    }

    public function installationsProvider() {
        $installations = array();
        for ($i = 0; $i < WP_INST_COUNT; ++$i) {
            $installations[] = array($i);
        }
        return $installations;
    }
}

?>
