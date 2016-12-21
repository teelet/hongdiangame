<?php
class Service_Api{
	private static $version = '1.0';
	private static $token = '';
	private static $apiBase = 'http://api.alexgame.cn';
	private static $dev_platform = 'web';
	private static $devip = '127.0.0.1';
	private static $apiMap = [];

	//是否返回debug信息
	public static $debug = false;
	public static $debugInfo = [];

	//通过代理访问
	public static $proxy = '';
	public static $proxyuserpwd = '';

	private static function _formatQuery($api,$params){
		$queryArr = array_merge(
			$params,
			[
				'version' => self::$version,
				'dev_platform' => self::$dev_platform,
				'udid' => Judge::getUdid(),
				'devip' => Comm_Util::getClientIp(),
			]
		);
		ksort($queryArr);
		$queryStr = "";
		foreach($queryArr as $key => $value){
			$queryStr .= $key."=".$value."&";
		}
		$time = substr(time(),0,7);
		$queryStr = $api.'?'.$queryStr.'time='.$time;
		if(self::$debug){
			self::$debugInfo['token']['before_encode'] = $queryStr;
		}
		$queryArr['token'] = md5($queryStr);
		//$queryArr['time'] = $time;
		return $queryArr;
	}

	private static function _curlRequest($remoteUrl, $params, $method, $connTimeout = 10, $callTimeout = 10) {
		$headers['Cookie'] = isset($_SERVER['HTTP_COOKIE']) ? $_SERVER['HTTP_COOKIE'] : '';
		$headers['User-Agent'] = $_SERVER['HTTP_USER_AGENT'];

		$headerArr = array();
		foreach($headers as $n => $v){
			$headerArr[] = $n.':'.$v;
		}
		$params = http_build_query($params);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		if(self::$proxy){
			curl_setopt($ch, CURLOPT_PROXY, self::$proxy);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, self::$proxyuserpwd);
		}
        if($method == 1){ //post
            curl_setopt($ch, CURLOPT_POST, $method );
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }else{ //get
            $remoteUrl = $remoteUrl.'?'.$params;
        }
        curl_setopt($ch, CURLOPT_URL, $remoteUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER , $headerArr );
		//curl_setopt($ch, CURLOPT_HEADER , true );

		if ($connTimeout != 0) {
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connTimeout);
		}
		if ($callTimeout != 0) {
			curl_setopt($ch, CURLOPT_TIMEOUT, $callTimeout);
		}
		$curlResult = curl_exec($ch);
		$errno = curl_errno($ch);
		$error = curl_error($ch);
		$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if(self::$debug){
			self::$debugInfo['response']['code'] = $responseCode;
			self::$debugInfo['response']['errno'] = $errno;
			self::$debugInfo['response']['error'] = $error;
			self::$debugInfo['response']['info'] = curl_getinfo($ch);
			self::$debugInfo['response']['result'] = $curlResult;
		}
		curl_close($ch);
		if($errno === 0 && $responseCode == 200){
			return $curlResult;
		}else{
			return false;
		}
	}

	public static function __callStatic($func,$params){
        self::$apiMap = Comm_Config::getPhpConf('interface');
		$func = strtolower($func);
		$params = empty($params) ? [] : $params[0];

		if(!array_key_exists($func,self::$apiMap)){
			return false;
		}

		$mustPar = self::$apiMap[$func]['mustpar'];
		if(!empty($mustPar)){
			$mustParArr = explode(',',$mustPar);
			foreach($mustParArr as $mpar){
				if(!array_key_exists($mpar,$params)){
					return false;
				}
			}
		}

		if(self::$apiMap[$func]['needsct']){
			if(Judge::isLogin()){
				$params['sct'] = Judge::getSct();
			}else{
				return false;
			}
		}

		$params = self::_formatQuery(self::$apiMap[$func]['api'],$params);
		$method = 'post' == strtolower(self::$apiMap[$func]['method']) ? 1 : 0;
		$url = self::$apiBase.self::$apiMap[$func]['api'];
		$result = self::_curlRequest($url,$params,$method);
		$result = json_decode($result,true);

		if(self::$debug){
			self::$debugInfo['request']['url'] = $url;
			self::$debugInfo['request']['params'] = $params;
			$result['debug'] = self::$debugInfo;
		}

		return $result;
	}

}

//example
// $params = array(
// 		'uid' => $uid,
// 	);
// //Service_Api::$debug = true;
// $rs = Service_Api::game_by_user($params);
