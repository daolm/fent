socket_io_port = 8080;

function NotificationClient(channel) {    
    var client_pull = new ClientPull();
    client_pull.start();
    var host = window.location.host.split(':')[0];
    var socket = io.connect('http://' + host, {port : socket_io_port});    
    socket.on('connect', function() {
        if (client_pull.is_started) {
            client_pull.end();            
        }
        socket.emit('subscribe', channel);        
    });
    socket.on('notification', function(msg){                        
        addNewNotification($.parseJSON(msg));
    });
    socket.on('disconnect', function() {                    
        if (!client_pull.is_started) {
            client_pull.start();
        }        
    });
}