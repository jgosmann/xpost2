<?php

require_once 'helpers/helpers.php';
require_once 'wp-installer/wp-installer.php';

require_once 'config-testenv-setup.php';

confirmOverride();

$version = getWpVersionToInstall();
$mysqlUser = getDbCreationUser();

$cacheDir = joinPaths(HTDOCS_DIR, '.wp-svn');

$config = new WpConfig(new DbConfig(DB_NAME_PREFIX, DB_USER, DB_PASSWORD,
    DB_HOST, DB_CHARSET, DB_COLLATE), 'wp_', '', true);

$wpInstallerFactory = new WpInstallerFactory(new CliSvnDelegate(SVN_BIN),
    new CliSqlExecutor($mysqlUser, MYSQL_BIN), WP_SVN);
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
