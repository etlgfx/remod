vm = require('vm');
fs = require('fs');
path = require('path');

function readFiles(dir, ext) {
    // Recursively collect files for use in modules.
    var content = '';
    if(path.existsSync(dir)) {
        fs.readdirSync(dir).forEach(function(f) {
            var full = path.join(dir, f);

            // If this file matches our extension, read its contents
            if ( f.match('.' + ext + '$') ) {
                content += fs.readFileSync(full, 'utf8');
            }

            // If this is a directory, recurse into it
            file_stat = fs.statSync(full);
            if ( file_stat.isDirectory() ) {
                content += readFiles(full, ext);
            }
        });
    }
    return content;
}

// Set up our arguments from the command line
var module_path = process.argv[2];
var mode = process.argv[3];
var module_data_file = process.argv[4];

// Read in the contents of the related files
var libs = readFiles(module_path + '/libs', 'js');
var css = readFiles(module_path + '/js', 'css');
var js = readFiles(module_path + '/js', 'js');

// Start off the code with the module.js file, and create
// a context to hold the code for execute later
var code = fs.readFileSync(module_path + '/module.js');
var context = vm.createContext();

// Grab module data passed from app via JSON
var module_data = JSON.parse(fs.readFileSync(module_data_file, 'utf8'));

// Append the contents of the libs directory to the code
code = code + libs;

// Load the code into the context
vm.runInContext(code, context);

// Finally, run the code based on the mode argument
switch(mode) {
    case 'view':
        output = context.render(module_data["request"], module_data["config"], module_data["data"]);
        break;

    case 'admin':
        output = context.renderAdmin(module_data["config"]);
        break;

    default:
        // TODO die?
}

// Display the output of our module
console.log('<script>' + js + '</script>');
console.log('<style>' + css + '</style>');
console.log(output);
