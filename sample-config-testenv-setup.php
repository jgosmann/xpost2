<?php

require_once 'config-test-runtime.php';

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

/** SVN address for fetching Wordpress versions. */
define('WP_SVN', 'https://core.svn.wordpress.org/');

/**
 * Number of Wordpress installations to create. Should not need any change if
 * you do not add any tests which need more installations.
 */
define('WP_INST_COUNT', 3);

?>
