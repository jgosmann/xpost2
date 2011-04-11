<?php

class PhpCodeGenerators {

    public static function asDefine($key, $value) {
        if (is_string($value)) {
            $value = PhpCodeGenerators::escapeString($value);
        }
        return "define('$key', $value);";
    }

    public static function asAssignment($key, $value) {
        if (is_string($value)) {
            $value = PhpCodeGenerators::escapeString($value);
        }
        return "$$key = $value;";
    }

    public static function escapeString($string) {
        return "'$string'";
    }
}

?>
