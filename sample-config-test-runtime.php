<?php

/**
 * Directory for the testing environment Wordpress installations.
 *
 * All contents except .* will get removed when using the setup-test-env.php
 * script.
 *
 * Example value: /opt/local/username/www/wp-test-env
 */
define('HTDOCS_DIR', '~/www/wp-test-env');

/**
 * URL to access htdocs dir. Make sure it ends with a slash!
 *
 * Example value: http://localhost/wp-test-env
 */
define('HTDOCS_URL', 'http://localhost/wp-test-env/');

/** Password for the test users. */
define('WP_USER_PASS', '');

/**
 * Prefix used for the testing environment Wordpress installation direcotries.
 */
define('WP_INST_PREFIX', 'wp');

/** Timeout for asynchronous tests in milliseconds. */
define('TEST_TIMEOUT', 5000);

/** Test browser. */
define('TEST_BROWSER', '*firefox');

?>
