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
        $config = explode('.', $key, self::KEY_NEST_LIMIT);
        $inst = self::getInstance();
        $obj =& $inst->config;

        foreach($config as $value) {
            if( empty($obj[$value]) ) {
                throw new Exception('Invalid config key: ' . $key);
            }
            $obj = $obj[$value];
        }
        return $obj;
    }

    public function __clone() {
        throw new Exception('Clone is not allowed');
    }

    public function __wakeup() {
        throw new Exception('Unserializing is not allowed');
    }
}
