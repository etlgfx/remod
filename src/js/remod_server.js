var net = require('net');
var remod = require('./remod.js');

var server = net.createServer(function (socket) {
	socket.on('data', function (data) {
		var request = JSON.parse(data.toString('utf8'));

		console.log('request: '+ request);

		socket.end(
			remod.renderSocket(
				'view',
				request.module,
				{
					'request': {},
					'config': request.data,
					'data': {}
				}
			)
		);
	});
});

server.listen('/tmp/remod.sock');
