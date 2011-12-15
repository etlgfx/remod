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

Module.prototype.render = function(request, data, config) {
    return this.context.render(request, data, config);
}

Module.prototype.renderAdmin = function(config) {
    return this.context.renderAdmin(config);
}


var module_path = process.argv[2];
var mode = process.argv[3];
var module_data_file = process.argv[4];

var fs = require('fs');

var module_data = JSON.parse(fs.readFileSync(module_data_file, 'utf8'));

var myModule = new Module(module_path);

switch(mode) {
    case 'view':
        output = myModule.render(module_data["request"], module_data["config"], module_data["data"]);
        break;

    case 'admin':
        output = myModule.renderAdmin(module_data["config"]);
        break;

    default:
        // TODO die?
}

console.log(output);