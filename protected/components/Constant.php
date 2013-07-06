<?php

class Constant
{
    public static $SUCCESS = 1;
    public static $FAIL = 0;
    
    public static $REQUEST_BEING_CONSIDERED = 0;
    public static $REQUEST_ACCEPTED = 1;
    public static $REQUEST_REJECTED = 2;
    public static $REQUEST_FINISH = 3;        
    public static $REQUEST_VALID = 4;
    public static $REQUEST_INVALID_EXISTED = 5;
    public static $REQUEST_UNEXPIRED = 6;
    public static $REQUEST_EXPIRED = 7;
    
    public static $DEVICE_NORMAL = 0;
    public static $DEVICE_UNAVALABLE = 1;
    
    public static $NOTIFICATION_PAGE_SIZE = 20;
}
?>
