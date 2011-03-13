<?php

/**
 * Directory for the testing environment Wordpress installations.
 *
 * All contents except .* will get removed when using the setup-test-env.php
 * script.
 *
 * Example value: /opt/local/username/www/wp-test-env
 */
define('HTDOCS_DIR', '');

/**
 * URL to access htdocs dir.
 */
define('HTDOCS_URL', '');

/** Password for the test users. */
define('WP_USER_PASS', '');

/**
 * Prefix used for the testing environment Wordpress installation direcotries.
 */
define('WP_INST_PREFIX', 'wp');

/** Timeout for asynchronous tests in milliseconds. */
define('TEST_TIMEOUT', 1000);

/** Test browser. */
define('TEST_BROWSER', '*firefox');

?>
