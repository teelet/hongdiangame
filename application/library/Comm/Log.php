<?php
class Comm_Log{
	const db = 'gameinfo';
	/**
	 *  写操作记录到数据库
	 */
	public static function writeLog($msg){
		try {
			Comm_Context::init();
			$user = Comm_Context::cookie("user");
			$ip = Comm_Context::get_client_ip(); 
			// $ip = $_SERVER["REMOTE_ADDR"];

			$config = Comm_Config::getPhpConf('db/db.'.self::db.'.write');
        	$instance = Comm_Db_Handler::getInstance(self::db, $config);
        	$res = $instance->insert("root_log",array("username"=>$user,"content"=>$msg,"ip"=>$ip));
        	return $res;
		} catch (Exception $e) {

		}
		return false;
		
	}
}