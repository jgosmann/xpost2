
<?php

/**
 * Directory for the testing environment Wordpress installations.
 *
 * All contents except .* will get removed when using the setup-test-env.php
 * script.
 *
 * Example value: /opt/local/username/www/wp-test-env
 */
define('HTDOCS_DIR', '/opt/local/username/www/wp-test-env');

/**
 * URL to access htdocs dir. Make sure it ends with a slash!
 *
 * Example value: http://localhost/wp-test-env
 */
define('HTDOCS_URL', 'http://localhost/wp-test-env/');

/**
 * Prefix used for the testing environment Wordpress installation direcotries.
 */
define('WP_INST_PREFIX', 'wp');

/** Password for the test users. */
define('WP_USER_PASS', '');

/** EMail address used for wordpress users. */
define('WP_USER_MAIL', '');

/** MySQL database username. */
define('DB_USER', 'wordpress');

/** MySQL database password */
define('DB_PASSWORD', '');


/* The following settings should be ok in most cases. */

/** MySQL hostname */
define('DB_HOST', 'localhost');

/**
 * Prefix for the databases generated for the wordpress test environment
 * installations.
 */
define('DB_NAME_PREFIX', 'wp');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/** Call to mysql binary. */
define('MYSQL_BIN', 'mysql5');

/** Call to svn binary. */
define('SVN_BIN', 'svn');

/**
 * Browser to use to execute database installation. Specifiy this as you would
 * in an Selenium setBrowser(...) call.
 *
 * http://seleniumhq.org/
 */
define('SELENIUM_BROWSER', '*firefox');

/**
 * Timeout in milliseconds for Selenium actions which are used for the final 
 * database installation.
 */
define('SELENIUM_TIMEOUT', 30000);

/** SVN address for fetching Wordpress versions. */
define('WP_SVN', 'https://core.svn.wordpress.org/');

/**
 * Number of Wordpress installations to create. Should not need any change if
 * you do not add any tests which need more installations.
 */
define('WP_INST_COUNT', 3);

?>
