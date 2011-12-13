<?php

require('PHPModule.class.php');
require('JSModule.class.php');

abstract class Module {

    const NODE_BINARY = '/usr/bin/node';

    private $valid_render_modes = array(
        'view',
        'admin'
    );

    abstract public function render($mode);

    public static function factory($module_id) {
        if (file_exists(PATH_CONFIG .'config.ini')) {
            $ini = parse_ini_file(PATH_CONFIG .'config.ini', true);
        } else {
            $ini = parse_ini_file(PATH .'/config/config.ini', true);
        }
        if ( empty($ini['module']['class']) ) {
            throw new Exception('Configuration is missing module class');
        }
        $class = $ini['module']['class'] . 'Module';

        if( !class_exists($class) ) {
            throw new Exception('Unable to instantiate module class: ' . $class);
        }
        return new $class($module_id);
    }

    protected function isValidRenderMode($mode) {
        return in_array($mode, $this->valid_render_modes);
    }
}

?>