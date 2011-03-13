<?php

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
    echo $argv[0] . ': ' . gettext('Too many arguments.');
    printUsage();
    exit(-1);
}

require_once 'test-config.php';




function confirmOverride() {
    $confirmCode = gettext('yes');

    printf(gettext(<<<EOT
HTDOCS_DIR is set to %s.
ALL contents (ecept .*) in that directory will be DELETED!
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
        $version = system('svn list "' . joinPaths(array(WP_SVN, 'tags'))
            . '" | tail -1');
        return trim($version, '/');
    }
}

function getSvnCheckoutDir() {
    return joinPaths(array(HTDOCS_DIR, '.svn-checkout'));
}

function prepareHtdocsDir() {
    if (!file_exists(HTDOCS_DIR)) {
        mkdir(HTDOCS_DIR, 0777, true);
    }

    system('rm -rf "' . HTDOCS_DIR . '"/*');
}

function fetchWpVersion($version) {
    $repo = joinPaths(array(WP_SVN, 'tags', $version));
    $dest = getSvnCheckoutDir();
    if (file_exists($dest)) {
        $action = 'switch';
    } else {
        $action = 'co';
    }

    system("svn $action \"$repo\" \"$dest\"");
}

function copyWpFiles($dest) {
    $src = getSvnCheckoutDir();
    system("cp -r \"$src\" \"$dest\"");
}

function createWpInstallations($count) {
    for ($i = 0; $i < $count; ++$i) {
        $dest = joinPaths(array(HTDOCS_DIR, WP_INST_PREFIX . $i));
        copyWpFiles($dest);
    }
}


confirmOverride();
$version = getWpVersionToInstall($argc, $argv);
prepareHtdocsDir();
printf(gettext("Fetching Wordpress version %s ...\n"), $version);
fetchWpVersion($version);
printf(gettext("Creating %d Wordpress installations ...\n"), 3);
createWpInstallations(3); // FIXME: Hide magic number.
echo gettext("done");

?>
