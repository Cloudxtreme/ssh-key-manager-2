<?php
class SSHMgrMongo {
    private static $username = 'ssh_mgr';
    private static $password = 'y3xGbWZ82tpT5SZUEdNPEjha8nCN3A';
    private static $host = 'localhost';
    private static $database = 'ssh_mgr';
    private static $userCollection = 'User';
    private static $projectCollection = 'Project';
    private static $projUserConCollection = 'ProjUserCon';
    private static function getMongoDB() {
        $mongoDBconnection = new MongoClient('mongodb://'.self::$username.':'.self::$password.'@'.self::$host.'/'.self::$database);
        return $mongoDBconnection->{self::$database};
    }
    public static function getUserC() {
        return self::getMongoDB()->{self::$userCollection};
    }
    public static function getProjC() {
        return self::getMongoDB()->{self::$projectCollection};
    }
    public static function getProjUserConC() {
        return self::getMongoDB()->{self::$projUserConCollection};
    }
}
?>
