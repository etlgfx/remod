<?php

class PHPModule extends Module {
    private $module_path;
    private $javascript_code;
    private $inline;

    const LIB_PATH = '/lib';
    const JS_PATH = '/js';

    function __construct($module_uuid) {
        $this->module_path = $this->getModulePath($module_uuid);

        $this->javascript_code = '';
        $this->javascript_header = '';

        $this->loadModule();
    }

    public function render($mode, $data = array(), $request = array(), $config = array()) {
        if( !$this->isValidRenderMode($mode) ) {
            throw new Exception('Invalid module render mode');
        }

        $temp_data = array(
            'data' => $data,
            'request' => $request,
            'config' => $config,
        );
        $module_data_file = $this->writeTempData(json_encode($temp_data));

        // TODO Get CSS as well as javascript for <head>
        $code_file = $this->writeJavascriptCode();

        // TODO output <head> contents
        return $this->runJavascript($code_file, $module_data_file, $mode);
    }

    protected function writeJavascriptCode() {
        return $this->writeTempData($this->javascript_code, 'js_code_');
    }

    private function loadModule() {
        // First we need the module.js code
        $this->javascript_code = file_get_contents($this->module_path . '/module.js');

        // Now load the libs
        $this->javascript_code .= $this->getLibs();

        // Grab the js/css to be placed inline above the module
        $this->inline = $this->getInline();
    }

    private function getLibs() {
        return $this->loadSource($this->module_path . self::LIB_PATH, 'js');
    }

    private function getInline() {
        $inline = '<script>' . $this->loadSource($this->module_path . self::JS_PATH, 'js') . '</script>';
        $inline .= '<style>' . $this->loadSource($this->module_path . self::JS_PATH, 'css') . '</style>';

        return $inline;
    }

    private function loadSource($path, $extension) {
        if ( !is_dir($path) ) {
            throw new Exception('Invalid JS source path');
        }

        $handle = opendir($path);
        if ( !$handle ) {
            throw new Exception('Unable to open path: ' . $path);
        }

        $data = '';
        while (false !== ($file = readdir($handle))) {
            if ( $file == '.' || $file == '..' ) {
                continue;
            }

            if( is_dir($path . '/' . $file) ) {
                $data .= $this->loadSource($path . '/' . $file, $extension);
            } elseif ( strpos($file, '.' . $extension) !== false ) {
                $data .= file_get_contents($path . '/' . $file);
            }
        }
        return $data;
    }

    private function runJavascript($code, $module_data_file, $mode) {
        $command = self::NODE_BINARY . ' ' . PATH . "js/module.js $mode $code $module_data_file" ;

        $output = $this->inline;
        $output .= shell_exec($command);

        return $output;
    }
}

?>