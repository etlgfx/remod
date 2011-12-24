var net = require('net');

var server = net.createServer(function (socket) {
	socket.on('data', function (data) {
		var request = JSON.parse(data.toString('utf8'));
        socket.end(request.module.uuid +': '+ request.data.module.name);
	});
});

server.listen('/tmp/remod.sock');
