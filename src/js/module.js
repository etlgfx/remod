var code = process.argv[2];
var mode = process.argv[3];

var vm = require('vm');
var fs = require('fs');
var util = require('util');

var context = vm.createContext();

fs.readFile(code, function (err, data) {
  if (err) throw err;
  vm.runInContext(data, context)

switch(mode) {
        case 'render':
                console.log(context.render('', '', ''));
                break;
        case 'admin':
                console.log(context.renderAdmin());
                break;
        default:
                break;
}
});
