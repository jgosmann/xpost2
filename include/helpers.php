<?php

// This dir separator also works with windows. See:
// http://us2.php.net/manual/en/ref.filesystem.php#73954
define('DIR_SEPARATOR', '/');

function joinPaths() {
    $parts = func_get_args();
    $isUnixAbsolute = $parts[0][0] == DIR_SEPARATOR;

    foreach ($parts as &$part) {
        $part = trim($part, DIR_SEPARATOR);
    }

    $completePath = implode(DIR_SEPARATOR, $parts);

    return ($isUnixAbsolute ? DIR_SEPARATOR : '') . $completePath;
}

?>
