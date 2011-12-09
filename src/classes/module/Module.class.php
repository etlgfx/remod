<?php

class Module {
    private $module_path;
    private $javascript_code;
    private $javascript_header;

    private $lib_path = '/lib';
    private $js_path = '/js';

    private $valid_render_modes = array(
        'render',
        'admin'
    );

    function __construct($path) {
        // Make sure we have no trailing slash
        if ( $path[strlen($path) - 1] == '/' ) {
            $path = substr($path, 0, strlen($path) - 1);
        }

        $this->module_path = $path;

        $this->javascript_code = '';
        $this->javascript_header = '';

        $this->loadModule();
    }

    public function getOutput($mode = 'render') {
        if( !$this->isValidRenderMode($mode) ) {
            // TODO throw some exception
        }

        $code_file = $this->writeJavascriptCode();
        $header_file = $this->writeJavascriptHeader();

        echo $code_file . PHP_EOL;

        $output = $this->runJavascript($code_file, $mode);
    }

    public function getJavascriptCode() {
        return $this->javascript_code;
    }

    public function getJavascriptHeader() {
        return $this->javascript_header;
    }

    protected function writeToTempFile($data) {
        $tmp_file = tempnam(sys_get_temp_dir(), uniqid());
        $fp = fopen($tmp_file, 'w');

        if ( !fwrite($fp, $data) ) {
            // TODO throw some exception
        }
        return $tmp_file;
    }

    protected function isValidRenderMode($mode) {
        return in_array($mode, $this->valid_render_modes);
    }

    protected function writeJavascriptCode() {
        return $this->writeToTempFile($this->javascript_code);
    }

    protected function writeJavascriptHeader() {
        return $this->writeToTempFile($this->javascript_header);
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
        return $this->loadSource($this->module_path . $this->lib_path);
    }

    private function getHeader() {
        return $this->loadSource($this->module_path . $this->js_path);
    }

    private function loadSource($path, $data = null) {
        if ( !is_dir($path) ) {
            // TODO throw exception
        }

        $handle = opendir($path);
        if ( !$handle ) {
            // TODO throw some exception
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
                // TODO throw some other exception?
            }
            $data .= $javascript_code;
        }
        return $data;
    }

    private function runJavascript($code, $mode) {
        $command = '/usr/bin/node /tmp/run_module.js ' . $code . ' ' . $mode;

        $output = '';
        $blh = system($code);
        exec($code);
        passthru($code);
    }

}

?>