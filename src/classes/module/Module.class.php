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
        $class = Config::read('module.class') . 'Module';

        if( !class_exists($class) ) {
            throw new Exception('Unable to instantiate module class: ' . $class);
        }
        return new $class($module_id);
    }

    protected function isValidRenderMode($mode) {
        return in_array($mode, $this->valid_render_modes);
    }

    protected function getModulePath($uuid) {
        if( !is_dir(Config::read('module.repo_path') . $uuid) ) {
            throw new Exception('Module not found: ' . Config::read('module.repo_path') . $uuid);
        }
        return Config::read('module.repo_path') . $uuid;
    }
}

?>