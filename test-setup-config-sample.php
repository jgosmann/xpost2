<?php

require_once 'test-runtime-config.php'

/** EMail address used for wordpress users. */
define('WP_USER_MAIL', '');

/** MySQL database username. */
define('DB_USER', '');

/** MySQL database password */
define('DB_PASSWORD', '');


/* The following settings should be ok in most cases. */

/** Call to mysql binary. */
define('MYSQL_BIN', 'mysql');

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

/** SVN address for fetching Wordpress versions. */
define('WP_SVN', 'https://core.svn.wordpress.org/');

?>
