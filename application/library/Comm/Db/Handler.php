<?php

class Comm_Db_Handler {
    
    private static $instances = array();
    
    /**
     * @param $DbName 库名
     * @param $config 配置文件 形如： array('host' => '127.0.0.1', 'port' => 3306, 'user' => 'root', 'passwd' => '111111', 'dbname' => 'test');
     * @return Ambigous <multitype:, Comm_Db_Mysql>
     */
    public static function getInstance($DbName, $config){
        if(isset(self::$instances[$DbName])){
            return self::$instances[$DbName];
        }else{
            $medoo_conf = array(
                // 必须配置项
                'database_type' => 'mysql',
                'database_name' => $config['dbname'],
                'server'        => $config['host'],
                'username'      => $config['user'],
                'password'      => $config['passwd'],
                'charset'       => 'utf8',
                'port' => $config['port'],
                'option' => array( // 连接参数扩展
                    PDO::ATTR_CASE => PDO::CASE_NATURAL
                )
            );
            self::$instances[$DbName] = new Comm_Db_Medoo($medoo_conf);

            return self::$instances[$DbName];
        }
    }
    
}