<?php

class RedisNotification
{
    private $_server = 'localhost';
    private $_port = 6379;
    private $_channel = 'notifications';
    private $_redis;    
    
    public static function checkRequirement()
    {
        return class_exists('Redis', false);
    }
    
    public function __construct($channel = null, $server = null, $port = null)
    {        
        $this->_redis = new Redis();
        if (isset($server)) {
            $this->_server = $server;
        }
        if (isset($port)) {
            $this->_port = $port;
        }
        if (isset($channel)) {
            $this->_channel = $channel;        
        }
    }
    
    public function setServer($server)
    {
        $this->_server = $server;
    }
    
    public function setPort($port)
    {
        $this->_port = $port;
    }
    
    public function setChannel($channel)
    {
        $this->_channel = $channel;
    }
    
    public function connect()
    {
        return $this->_redis->connect($this->_server, $this->_port);
    }
        
    
    public function getUserChannel($user_id)
    {
        return $this->_redis->get($user_id);
    }
    
    public function checkIn($user_id)
    {
        if (!$this->_redis->exists($user_id)) {
            $this->_redis->set($user_id, md5(microtime().$user_id.rand(10000, 99999)));
        }
        return $this->getUserChannel($user_id);
    }
    
    public function checkOut($user_id)
    {
        $this->_redis->del($user_id);
    }
    
    public function setChannelByUserId($user_id)
    {
        if (!$user_id) {
            $user_id = 'admin';
        }
        $channel = $this->getUserChannel($user_id);
        if ($channel !== false) {
            $this->setChannel($channel);
            return true;
        } else {
            return false;
        }
    }
    
    public function publishNotifications($content)
    {
        return $this->_redis->publish($this->_channel, $content);
    }
}
?>
