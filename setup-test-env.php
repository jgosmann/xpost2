<?php

// Depends: gettext, openssl, readline, svn

require_once 'include/helpers.php';

function printUsage() {
    echo gettext(<<<EOT
Usage: php setup-test-env.php [wordpress version]
If you do not specifiy wordpress version, the newest will be fetched.

EOT
);
}

$showUsage = $argc > 1 && ($argv[1] == '-h' || $argv[1] == '--help');
if ($showUsage) {
    printUsage();
    exit(0);
}

if ($argc > 2) {
    echo $argv[0] . ': ' . gettext('Too many arguments.\n');
    printUsage();
    exit(-1);
}

require_once 'test-config.php';




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

function getWpVersionToInstall($argc, $argv) {
    if ($argc > 1) {
        return $argv[1];
    } else {
        $version = system('svn list '
            . escapeshellarg(joinPaths(WP_SVN, 'tags')) . '" | tail -1');
        return trim($version, '/');
    }
}

function getSvnCheckoutDir() {
    return joinPaths(HTDOCS_DIR, '.svn-checkout');
}

function prepareHtdocsDir() {
    if (!file_exists(HTDOCS_DIR)) {
        mkdir(HTDOCS_DIR, 0777, true);
    }

    system('rm -rf ' . escapeshellarg(HTDOCS_DIR) . '/*');
}

function fetchWpVersion($version) {
    $repo = escapeshellarg(joinPaths(WP_SVN, 'tags', $version));
    $dest = escapeshellarg(getSvnCheckoutDir());
    if (file_exists($dest)) {
        $action = 'switch';
    } else {
        $action = 'co';
    }

    system("svn $action $repo $dest");
}

function createWpInstallations($count) {
    $dbScript = '';
    for ($i = 0; $i < $count; ++$i) {
        $dest = joinPaths(HTDOCS_DIR, WP_INST_PREFIX . $i);
        copyWpFiles($dest);
        createConfig($i, $dest);
        $dbScript .= getDatabaseScript($i);
    }

    $dbScript = escapeshellarg($dbScript);
    echo gettext("Creating databases, please enter mysql login information:\n");
    $user = escapeshellarg(readline('User: '));
    // Do not use built in mysql support as we want a hidden password input.
    system("echo $dbScript | " . MYSQL_BIN . " -u $user -p");
}

function copyWpFiles($dest) {
    $src = escapeshellarg(getSvnCheckoutDir());
    $dest = escapeshellarg($dest);
    system("cp -r $src $dest");
}

function createConfig($index, $dest) {
    $config = file_get_contents('test/setup/wp-config-template.php', true);
    $keys = array(
        '%DB_NAME%',
        '%DB_USER%',
        '%DB_PASSWORD%',
        '%DB_HOST%',
        '%DB_CHARSET%',
        '%DB_COLLATE%',
        '%SALT_SECTION%');
    $replacements = array(
        DB_NAME_PREFIX . $index,
        DB_USER,
        DB_PASSWORD,
        DB_HOST,
        DB_CHARSET,
        DB_COLLATE,
        file_get_contents('https://api.wordpress.org/secret-key/1.1/salt/'));
    $config = str_replace($keys, $replacements, $config);
    file_put_contents(joinPaths($dest, 'wp-config.php'), $config);
}

function getDatabaseScript($index) {
    $script = file_get_contents('test/setup/create-db.sql', true);
    $keys = array(
        '%DB_NAME%',
        '%DB_USER%',
        '%DB_HOST%');
    $replacements = array(
        DB_NAME_PREFIX . $index,
        DB_USER,
        DB_HOST);
    return str_replace($keys, $replacements, $script);
}

confirmOverride();
$version = getWpVersionToInstall($argc, $argv);
prepareHtdocsDir();
printf(gettext("Fetching Wordpress version %s ...\n"), $version);
fetchWpVersion($version);
printf(gettext("Creating %d Wordpress installations ...\n"), 3);
createWpInstallations(3); // FIXME: Hide magic number.
echo gettext("done\n");

?>
