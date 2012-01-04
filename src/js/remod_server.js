var net = require('net');
var remod = require('./remod.js');

var server = net.createServer(
	function (socket) {
		var len = null, crc = null, json = null;
		var to = null;

		socket.on('data', function (data) {
			var str = data.toString('utf8');

			if (len) {
				json += str;
			}
			else {
				len = parseInt(str.substring(0, 8), 16);
				crc = parseInt(str.substring(8, 16), 16);
				json = str.substring(16);
			}

			if (json.length == len) {
				try {
					var json = JSON.parse(json, 'utf8');

					socket.end(
						remod.renderSocket(
							'view',
							json.module,
							{
								'request': json.request,
								'config': json.config,
								'data': json.data,
							}
						)
					);

					clearTimeout(to);
				}
				catch (e) {
					console.log(e +" -:- "+ len +' '+ crc +' '+ json);
					socket.end("");
				}
			}

			if (!to) {
				to = setTimeout(function() {
					console.log(str);
					console.log();
				}, 200);
			}
		});
	}
);

server.listen('/tmp/remod.sock');
