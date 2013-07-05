socket_io_port = 8080;

var io = require('socket.io').listen(socket_io_port),
    fs = require('fs'),
    redis = require('redis');

io.configure(function() {
    io.set('close timeout', 60 * 60 * 24); 
});

function RedisClient(redis_channel) {
    this.sub = redis.createClient();
    this.redis_channel = redis_channel;
}

RedisClient.prototype.subscribe = function(socket) {
    this.sub.subscribe(this.redis_channel);
    this.sub.on('message', function(channel, message) {
        socket.emit('notification', message);
    });
};

RedisClient.prototype.unsubscribe = function() {
    this.sub.unsubscribe(this.redis_channel);
};

RedisClient.prototype.destroy = function() {
    if (this.sub !== null) {
        this.sub.quit();
    }
};

io.sockets.on('connection', function(socket) { 
    var rc;
    socket.on('subscribe', function(redis_channel) {
        rc = new RedisClient(redis_channel);
        rc.subscribe(socket);
    });
    socket.on('disconnect', function() {
        if (rc === null) {
            return;
        }
        rc.unsubscribe();
        rc.destroy();
    });
});