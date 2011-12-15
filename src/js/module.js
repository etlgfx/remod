var vm = require('vm');
var fs = require('fs');

var mode = process.argv[2];
var code = process.argv[3];
var module_data_file = process.argv[4];

var module_data = JSON.parse(fs.readFileSync(module_data_file, 'utf8'));
var context = vm.createContext();

fs.readFile(code, function (err, data) {
  if (err) throw err;
  vm.runInContext(data, context);

switch(mode) {
        case 'view':
                console.log(context.render(module_data["request"], module_data["config"], module_data["data"]));
                break;
        case 'admin':
                console.log(context.renderAdmin(module_data["config"]));
                break;
        default:
                break;
}
});
