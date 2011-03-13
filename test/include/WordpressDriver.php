<?php

require_once 'test-setup-config.php';

require_once 'PHPUnit/Extensions/SeleniumTestCase.php';
require_once 'Hamcrest/hamcrest.php';

require_once 'include/helpers.php';

class WordpressDriver extends PHPUnit_Extensions_SeleniumTestCase {

    public function setUp() {
        $this->setBrowser(TEST_BROWSER);
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
        $this->waitForPageToLoad(TEST_TIMEOUT);

        assertThat($this->isTextPresent("Success"));
    }

    public function installationsProvider() {
        $installations = array();
        for ($i = 0; $i < 3; ++$i) { // FIXME: remove magic number
            $installations[] = array($i);
        }
        return $installations;
    }
}

?>
