var app = require('express')();
var fs = require('fs');
var path = require('path');

//replace below key with your own server files
var options = {
  key: fs.readFileSync(path.resolve('../chat_ssl/private.key')),
  cert: fs.readFileSync(path.resolve('../chat_ssl/chat_cert.crt'))
};

var clients 		= {};
var connected_users = [];
var https = require('https').createServer(options, app);
var io = require('socket.io')(https);

//chat port listen
https.listen(3000, function(){
  console.log('listening on *:3000');
});

io.on('connection', function(socket){
  //add new user's id to socket.
	  socket.on('add-user', function(data) {
			//console.log(data);
			clients[data.userId] = {
			  "socket": socket.id,
			};
			connected_users.push(data.userId);
			io.sockets.emit('connected-users', { users_connected: connected_users });
	  });

	  //sending messsages to require person
	  socket.on('send_msg', function(data){
		  //console.log('data);
		  if (clients[data.user_id]) {
			io.sockets.connected[clients[ data.user_id ].socket].emit("send_msg", data);
		  } else {
			console.log("User does not exist");
		  }
	  });

	  //Removing the socket on disconnect
	  socket.on('disconnect', function() {
		for(var name in clients) {
		  if(clients[name].socket === socket.id) {
			delete clients[name];
			break;
		  }
		}
	  });
});