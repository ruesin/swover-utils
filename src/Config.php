<?php

namespace Ruesin\Utils;


/**
 * Config set & get like Laravel
 * @method static get($key, $default = null)
 * @method static set($key, $value)
 */
class Config
{

    private $config = [];

    private static $instance = null;

    private function __construct(string $name)
    {
        if (is_file($name)) {
            return $this->loadFile($name);
        }

        return $this->loadPath($name);
    }

    /**
     * Load the configuration and return instance
     *
     * @param string $name
     * @return Config
     */
    public static function load(string $name)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($name);
        }
        return self::$instance;
    }

    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * Loads the configuration file under the path into the application
     *
     * @param  string $path
     * @return bool
     */
    private function loadPath($path)
    {
        $path = rtrim($path, '/') . '/';
        $files = scandir($path);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                $this->loadFile($path . $file);
            }
        }
        return true;
    }

    /**
     * Load a configuration file into the application.
     *
     * @param  string $file_name
     * @return bool
     */
    private function loadFile($file_name)
    {
        if (!is_file($file_name)) return false;
        if (strrchr($file_name, '.') !== '.php') return false;
        $this->set(basename($file_name, '.php'), require $file_name);
        return true;
    }

    public function __call($name, $arguments)
    {
        $name .= 'Config';

        if (!method_exists($this, $name)) {
            throw new \Exception('Not Found Config Method!');
        }

        return call_user_func_array([$this, $name], $arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        if (!self::$instance instanceof self) {
            throw new \Exception('Not Found Config Instance!');
        }

        $name .= 'Config';

        if (!method_exists(self::$instance, $name)) {
            throw new \Exception('Not Found Config Method!');
        }

        return call_user_func_array([self::$instance, $name], $arguments);
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
