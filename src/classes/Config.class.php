<?php

class Config {
    private static $instance;
    private $config;

    const KEY_NEST_LIMIT = 3;

    private function __construct() {
        if (file_exists(PATH_CONFIG .'config.ini')) {
            $this->config = parse_ini_file(PATH_CONFIG .'config.ini', true);
        } else {
            $this->config = parse_ini_file(PATH .'/config/config.ini', true);
        }
    }

    protected static function getInstance()
    {
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }

    public static function read($key) {
        $key = explode('.', $key, self::KEY_NEST_LIMIT);
        $depth = count($key);

        switch($depth) {
            case 1:
                if ( !empty(Config::getInstance()->config[$key[0]]) ) {
                    return Config::getInstance()->config[$key[0]];
                } else {
                    throw new Exception('Missing configuration: ' . $key[0]);
                }
                break;

            case 2:
                if ( !empty(Config::getInstance()->config[$key[0]][$key[1]]) ) {
                    return Config::getInstance()->config[$key[0]][$key[1]];
                } else {
                    throw new Exception('Missing configuration: ' . $key[0] . '.' . $key[1]);
                }
                break;

            case 3:
                if ( !empty(Config::getInstance()->config[$key[0]][$key[1]][$key[2]]) ) {
                    return Config::getInstance()->config[$key[0]][$key[1]][$key[2]];
                } else {
                    throw new Exception('Missing configuration: ' . $key[0] . '.' . $key[1] . '.' . $key[2]);
                }
                break;
        }
    }

    public function __clone() {
        throw new Exception('Clone is not allowed');
    }

    public function __wakeup() {
        throw new Exception('Unserializing is not allowed');
    }
}