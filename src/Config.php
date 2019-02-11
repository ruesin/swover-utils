<?php

namespace Ruesin\Utils;


/**
 * Config set & get like Laravel
 */
class Config
{

    private $config = [];

    /**
     * @var Config
     */
    private static $instance = null;

    private function __construct()
    {
    }

    /**
     * Load the configuration and return instance
     *
     * @param string $name
     * @return Config
     */
    public static function load(string $name = '')
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        if (!$name) return self::$instance;

        if (is_file($name)) {
            self::$instance::loadFile($name);
        } elseif (is_dir($name)) {
            self::$instance::loadPath($name);
        }

        return self::$instance;
    }

    /**
     * Loads the configuration file under the path into the application
     *
     * @param  string $path
     * @return bool
     */
    public static function loadPath($path)
    {
        $path = rtrim($path, '/') . '/';
        $files = scandir($path);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                self::loadFile($path . $file);
            }
        }
        return true;
    }

    /**
     * Load a configuration file into the application.
     *
     * @param  string $file_name
     * @return bool | Config
     */
    public static function loadFile($file_name)
    {
        if (!is_file($file_name)) return false;
        if (strrchr($file_name, '.') !== '.php') return false;
        if (!self::$instance instanceof Config) {
            return self::load($file_name);
        }
        self::$instance::set(basename($file_name, '.php'), require $file_name);
        return true;
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        if (self::$instance instanceof Config) {
            return self::$instance->getConfig($key, $default);
        }

        return false;
    }

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * @param  string $key
     * @param  mixed $value
     * @return array
     */
    public static function set($key, $value)
    {
        if (self::$instance instanceof Config) {
            return self::$instance->setConfig($key, $value);
        }

        return false;
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    private function getConfig($key, $default = null)
    {
        if (empty($this->config)) {
            return $default;
        }

        $array = $this->config;

        if (is_null($key)) {
            return $array;
        }

        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }
        return $array;
    }

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * @param  string $key
     * @param  mixed $value
     * @return array
     */
    private function setConfig($key, $value)
    {
        $array = &$this->config;

        //If no key is given to the method, the entire array will be replaced.
        //if (is_null($key)) {
        //    return $array = $value;
        //}

        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }
}
