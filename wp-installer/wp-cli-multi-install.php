<?php

/* This script setups multiple Wordpress installations, but does not execute
 * the final database installation.
 * Use 'phpunit wp-installer/wp-phpunit-db-install.php' to do the final
 * database installation.
 *
 * This script needs the following to be installed:
 * - PHP <http://www.php.net/>
 *     - including Gettext support
 *       <http://www.php.net/manual/de/book.gettext.php>
 *     - including OpenSSL support <http://php.net/manual/en/book.openssl.php>
 *     - including Readline support <http://php.net/manual/de/book.readline.php>
 * - Subversion/SVN <http://subversion.tigris.org/>
 * - MySQL <http://mysql.com/>
 *
 * Make sure that you copied wp-installer/sample-config-wp-installer.php to
 * config-wp-installer.php and filled in the correct settings.
 */

require_once 'helpers/helpers.php';
require_once 'wp-installer/wp-installer.php';

require_once 'config-wp-installer.php';

confirmOverride();

$version = getWpVersionToInstall();
$mysqlUser = getDbCreationUser();

$cacheDir = joinPaths(HTDOCS_DIR, '.wp-svn');

$config = new WpConfig(new DbConfig(DB_NAME_PREFIX, DB_USER, DB_PASSWORD,
    DB_HOST, DB_CHARSET, DB_COLLATE), 'wp_', '', true);

$wpInstallerFactory = new WpInstallerFactory(
    new CliSvnDelegate(SVN_BIN),
    new BufferingSqlExecutor(new CliSqlExecutor($mysqlUser, MYSQL_BIN)),
    WP_SVN);
$wpMultiInstaller = $wpInstallerFactory->createWpMultiInstaller($cacheDir,
    new FromWpApiSaltFetcher(), WP_INST_PREFIX);

$wpMultiInstaller->createInstallations(HTDOCS_DIR, $version, $config,
    WP_INST_COUNT);



function confirmOverride() {
    $confirmCode = gettext('yes');

    printf(gettext(<<<EOT
HTDOCS_DIR is set to %s.
ALL contents (except .*) in that directory will be DELETED!
Enter "$confirmCode" if you are sure!

EOT
    ), HTDOCS_DIR);

    if (readline('> ') != $confirmCode) {
        exit(1);
    }
}

function getWpVersionToInstall() {
    $version = readline(gettext(
        'Wordpress version to fetch [enter for latest]: '));
    if ($version == '') {
        $version = system(escapeshellarg(SVN_BIN) . ' list '
            . escapeshellarg(joinPaths(WP_SVN, 'tags')) . ' | tail -1');
        $version = trim($version, '/');
    }
    return $version;
}

function getDbCreationUser() {
    return readline(gettext('MySQL user for db creation: '));
}

?>
