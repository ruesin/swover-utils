<?php

namespace Ruesin\Utils;

class Log
{
    private static $log_path = '/tmp/';
    private static $uniqueId = null;

    public static function init($config = [])
    {
        if (isset($config['log_path'])) {
            self::$log_path = rtrim($config['log_path'], '/').'/';
        }
        if (!file_exists(self::$log_path)) {
            !is_dir(self::$log_path) && mkdir(self::$log_path, 0777, true);
        }
        if (isset($config['unique_id'])) {
            self::$uniqueId = $config['unique_id'];
        }
    }

    public static function msg($msg, $file = 'default', $folder = '')
    {
        self::log($msg, $file, $folder);
    }

    public static function info($msg, $file = 'default', $folder = '')
    {
        $msg = '[' . date('Y-m-d H:i:s') . '] ' . $msg;
        self::log($msg, $file, $folder);
    }

    private static function log($msg, $file, $folder)
    {
        $path = self::$log_path . $folder;
        $file = $file ?: 'default';
        if (!file_exists($path)) {
            !is_dir($path) && mkdir($path, 0777, true);
        }
        file_put_contents($path . '/' . $file . '_' . self::getUniqueId() . '_' . date('Ymd') . ".log", $msg . PHP_EOL, FILE_APPEND);
    }

    public static function getUniqueId()
    {
        if (self::$uniqueId === null) {
            self::$uniqueId = posix_getpid();
        }
        return self::$uniqueId;
    }
}
