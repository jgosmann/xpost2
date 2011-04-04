<?php

require_once 'wp-installer/src/wp-installer.php';
//require_once 'helpers/helpers.php';
//ClassLoader::addClassDir('wp-installer/src');

//require_once 'config-testenv-setup.php';

//function confirmOverride() {
    //$confirmCode = gettext('yes');

    //printf(gettext(<<<EOT
//HTDOCS_DIR is set to %s.
//ALL contents (except .*) in that directory will be DELETED!
//Enter "$confirmCode" if you are sure!

//EOT
    //), HTDOCS_DIR);

    //if (readline('> ') != $confirmCode) {
        //exit(1);
    //}
//}

//function getWpVersionToInstall() {
    //$version = readline('Wordpress version to fetch [enter for latest]: ');
    //if ($version == '') {
        //$version = system('svn list '
            //. escapeshellarg(joinPaths(WP_SVN, 'tags')) . '" | tail -1');
        //return trim($version, '/');
    //}
//}


//confirmOverride();

//$installationsCount = 3;
//$version = getWpVersionToInstall();
//$mysqlUser = escapeshellarg(readline(gettext('MySQL user for db creation: ')));

//$config = new WpConfig(new DbConfig(DB_NAME_PREFIX, DB_USER, DB_PASSWORD,
    //DB_HOST, DB_CHARSET, DB_COLLATE), 'wp_', '', true);

//$wpInstaller = new WpInstaller(joinPaths(HTDOCS_DIR, '.wp-svn'),
    //new CliSqlExecutor($mysqlUser, MYSQL_BIN));
//$wpMultiInstaller = new WpMultiInstaller($wpInstaller, WP_INST_PREFIX);
//$wpMultiInstaller->createInstallations(HTDOCS_DIR, $version, $config,
    //$installationsCount);

?>
