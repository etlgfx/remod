Module = function(path) {
    this.path_util = require('path');
    this.vm = require('vm');
    this.fs = require('fs');

    // Make sure we have no trailing slash
    if ( path[path.length - 1] == '/' ) {
        path = path.substr(0, path.length - 1);
    }
    this.path = path;

    this.libs = [];
//    this.css = [];
//    this.header_scripts = [];

    this.code = this.fs.readFileSync(path + '/module.js');

    this.context = this.vm.createContext();


    this.javascript_code = '';
    this.javascript_header = '';

    this.loadModule();
}

Module.prototype.loadModule = function() {
    this.libs = this.findFiles(this.path + '/libs', 'js');
//    this.css = this.findFiles(this.path + '/js', 'css');
    this.compile();
}

Module.prototype.findFiles = function(dir, ext) {
    // Recursively collect files for use in modules.
    var files = [];
    if(this.path_util.existsSync(dir)) {
        this.fs.readdirSync(dir).forEach(function(f) {
            var full = path.join(dir, f);
            if(this.path_util.existsSync(full)) {
                var more = findFiles(full, ext);
                files = files.concat(more);
            } else if(f.match('.' + ext + '$')) {
                files.push(full);
            }
        });
    }
    return files;
}

//Module.prototype.findJsFiles = function(dir) {
//    return findFiles(dir, 'js');
//}
//
//Module.prototype.findCssFiles = function(dir) {
//    return findFiles(dir, 'css');
//}

//Module.prototype.compile = function() {
//    this.sandbox = {};
//    this.compileLibs();
//    this.compileCode(this.code, MODULE_FILENAME);
//}

Module.prototype.findLibs = function() {
    var files = [];
    this.libs.forEach(function(p) {
        files = files.concat(findJsFiles(p));
    });
    return files;
}

Module.prototype.compile = function() {
    // Compile libs and append to code
    var libs = this.findLibs().forEach(function(f) {
        libs += this.fs.readFileSync(path.join(process.cwd(), f));
    });
    this.code = this.code + libs;

    this.vm.runInContext(this.code, this.context);
}

Module.prototype.render = function() {
    return this.context.render();
}

Module.prototype.renderAdmin = function() {
    return this.context.renderAdmin();
}