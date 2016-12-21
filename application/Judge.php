<?php
/*
 * 用户登录及校验
 *
 * 
 */

class Judge {
	const PIN_CODE = '3RGKVDSa8I23kIAhZtsi9a123T2ULi53';

	private static $verfycode = 'Hi^2sjd23!#2132zied';

	public static $request = null;
	public static $sid = null;
	public static $uid = null;
	public static $vid = null;
	public static $udid = null;

    public static function init(Yaf_Request_Abstract $request) {
    	self::$request = $request;
    	self::$sid = $request->getCookie('sid');
    	self::$uid = $request->getCookie('uid');
    	self::$vid = $request->getCookie('vid');

    	if(is_null($request->getCookie('udid'))){
    		$udid = self::genUdid();
    		setcookie('udid',$udid,time()+60*60*24,'/','.'.$request->getServer('HTTP_HOST'));
    		self::$udid = $udid;
    	}else{
    		self::$udid = $request->getCookie('udid');
    	}
	}

	public static function isLogin() {
		if(!is_null(self::$sid) && !is_null(self::$uid) && !is_null(self::$vid) && self::$vid == self::genVid(self::$sid,self::$uid)){
			return true;
		}
		return false;
	}

	public static function login($sid,$uid){
		return 
		setcookie('sid',$sid,time()+60*60*24*14,'/','.'.self::$request->getServer('HTTP_HOST')) &&
		setcookie('uid',$uid,time()+60*60*24*14,'/','.'.self::$request->getServer('HTTP_HOST')) &&
		setcookie('vid',self::genVid($sid,$uid),time()+60*60*24*14,'/','.'.self::$request->getServer('HTTP_HOST'));
	}

	//sid是否是变化的？如果不是变化的那么vid就不会变，必须考虑要增加其他项。
	public static function genVid($sid,$uid){
		return sha1(sha1($sid.$uid).self::$verfycode);
	}

	public static function genUdid(){
		return md5(self::$request->getServer('HTTP_USER_AGENT').time().self::$verfycode);
	}

	public static function getUid(){
		if(self::isLogin()){
			return self::$uid;
		}
		return false;
	}

	public static function getUdid(){
		return self::$udid;
	}

	public static function getSct(){
		if(self::isLogin()){
			$str = md5(self::$request->getCookie('sid').self::PIN_CODE.substr(time(), 0, 7));
			return $str[0].$str[5].$str[2].$str[10].$str[16].$str[8].$str[20].$str[26];
		}
		return false;
	}

	public static function logout(){
		return 
		setcookie('sid','',time() - 60,'/','.'.self::$request->getServer('HTTP_HOST')) &&
		setcookie('uid','',time() - 60,'/','.'.self::$request->getServer('HTTP_HOST')) &&
		setcookie('vid','',time() - 60,'/','.'.self::$request->getServer('HTTP_HOST'));
	}

	public static function getVerifyCode(){
		return self::$verfycode;
	}
}