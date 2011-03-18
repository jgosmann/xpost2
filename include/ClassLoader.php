<?php

require_once 'include/helpers.php';

class ClassLoader {

    private static $classDirs = array();
    private static $hasBeenRegistered = false;

    public static function addClassDir($path) {
        if (!self::$hasBeenRegistered) {
            self::register();
        }
        self::$classDirs[] = $path;
    }

    public static function getClassDirs() {
        return self::$classDirs;
    }

    protected static function autoload($className) {
        foreach (self::$classDirs as $dir) {
            $classFile = joinPaths($dir, $className) . '.php';
            if (file_exists($classFile)) {
                include_once $classFile;
                return true;
            }
        }
        return false;
    }

    private static function register() {
        spl_autoload_register(array(get_called_class(), 'autoload'));
    }
}

