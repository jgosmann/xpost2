<?php

require_once 'config-wp-installer.php';

require_once 'PHPUnit/Extensions/SeleniumTestCase.php';
require_once 'Hamcrest/hamcrest.php';

require_once 'helpers/helpers.php';

class WordpressDbInstaller extends PHPUnit_Extensions_SeleniumTestCase {

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
