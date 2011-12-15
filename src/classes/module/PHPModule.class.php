<?php

class PHPModule extends Module {
    private $module_path;
    private $javascript_code;
    private $javascript_header;

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
        $temp_file = $this->writeTempData(json_encode($temp_data));

        // TODO Get CSS as well as javascript for <head>
        $code_file = $this->writeJavascriptCode();
        $header_file = $this->writeJavascriptHeader();

        // TODO output <head> contents
        return $this->runJavascript($code_file, $temp_file, $mode);
    }

    public function getJavascriptCode() {
        return $this->javascript_code;
    }

    public function getJavascriptHeader() {
        return $this->javascript_header;
    }

    protected function writeJavascriptCode() {
        return $this->writeTempData($this->javascript_code, 'js_code_');
    }

    protected function writeJavascriptHeader() {
        return $this->writeTempData($this->javascript_header, 'js_header_');
    }

    private function loadModule() {
        // First we need the module.js code
        $this->javascript_code = file_get_contents($this->module_path . '/module.js');

        // Now load the libs
        $this->javascript_code .= $this->getLibs();

        // Grab the JS source to be placed in a <script> tag along with module output
        $this->javascript_header = $this->getHeader();
    }

    private function getLibs() {
        return $this->loadSource($this->module_path . self::LIB_PATH);
    }

    private function getHeader() {
        return $this->loadSource($this->module_path . self::JS_PATH);
    }

    private function loadSource($path, $data = null) {
        if ( !is_dir($path) ) {
            throw new Exception('Invalid JS source path');
        }

        $handle = opendir($path);
        if ( !$handle ) {
            throw new Exception('Unable to open path: ' . $path);
        }

        while (false !== ($file = readdir($handle))) {
            if ( $file == '.' || $file == '..' ) {
                continue;
            }

            if( is_dir($path . '/' . $file) ) {
                return $this->loadSource($path . '/' . $file, $data);
            }
            $javascript_code = file_get_contents($path . '/' . $file);

            if( !$javascript_code ) {
                throw new Exception('Error reading file: ' . $path . '/' . $file);
            }
            $data .= $javascript_code;
        }
        return $data;
    }

    private function runJavascript($code, $temp_file, $mode) {
        $command = self::NODE_BINARY . ' ' . PATH . "js/module.js $mode $code $temp_file" ;
        return shell_exec($command);
    }
}

?>