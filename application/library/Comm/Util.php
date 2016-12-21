<?php
class Comm_Util {
	
	/**
	 * 判断php宿主环境是否是64bit
	 * 
	 * ps: 在64bit下，php有诸多行为与32bit不一致，诸如mod、integer、json_encode/decode等，具体请自行google。
	 * 
	 * @return bool
	 */
	public static function is_64bit(){
        return PHP_INT_SIZE == 8;
	}
	
	/**
	 * 修正过的ip2long
	 * 
	 * 可去除ip地址中的前导0。32位php兼容，若超出127.255.255.255，则会返回一个float
	 * 
	 * for example: 02.168.010.010 => 2.168.10.10
	 * 
	 * 处理方法有很多种，目前先采用这种分段取绝对值取整的方法吧……
	 * @param string $ip
	 * @return float 使用unsigned int表示的ip。如果ip地址转换失败，则会返回0
	 */
	public static function ip2long($ip){
		$ip_chunks = explode('.', $ip, 4);
		foreach ($ip_chunks as $i => $v){
			$ip_chunks[$i] = abs(intval($v)); 
		}
		return sprintf('%u', ip2long(implode('.', $ip_chunks)));
	}
	
	/**
	 * 判断是否是内网ip
	 * @param string $ip
	 * @return boolean 
	 */
	public static function is_private_ip($ip){
		$ip_value = self::ip2long($ip);
		return ($ip_value & 0xFF000000) === 0x0A000000 //10.0.0.0-10.255.255.255
				|| ($ip_value & 0xFFF00000) === 0xAC100000 //172.16.0.0-172.31.255.255
				|| ($ip_value & 0xFFFF0000) === 0xC0A80000 //192.168.0.0-192.168.255.255
		;
	}
	
	/**
	 * 配置获取函数
	 * 
	 * @param string $key
	 */
	public static function conf($key) {
		return Comm_Config::get ( $key );
	}
	
	/**
	 * 日志函数
	 * 
	 * 给出一个日志formatter，并且指定是否应该立即写入
	 * 
	 * @see Comm_Log
	 * @param Comm_Log_Formatter $formatter
	 * @param bool $write_now
	 */
	public static function log(Comm_Log_Formatter $formatter, $write_now = false) {
		if ($write_now) {
			Comm_Log::write_single ( $formatter );
		} else {
			Comm_Log::add ( $formatter, $write_now );
		}
	}
	
	/**
	 * 多语言获取函数
	 * 
	 * @param string $key
	 * @param string $package
	 */
	public static function i18n($key, $package = "") {
		return Comm_I18n::text ( $key, $package );
	}
	
	/**
	 * 多语言获取函数的别名函数
	 * 
	 * @see Comm_I18n::i18n
	 * 
	 * @param string $key
	 * @param string $package
	 */
	public static function _($key, $package = "") {
		return self::i18n ( $key, $package );
	}
	
    /**
     * 使json_decode能处理32bit机器上溢出的数值类型
     * 
     * @param string $response
     * @param string $field_name
     * @param boolean $assoc
     * @return array|object
     */
    public static function json_decode($value, $assoc = true) {
        //PHP5.3以下版本不支持
        //TODO 获取机器CPU位数
        if (version_compare(PHP_VERSION, '5.3.0', '>') && defined('JSON_BIGINT_AS_STRING')) {
            return json_decode($value, $assoc, 512, JSON_BIGINT_AS_STRING);
        } else {
            $value = preg_replace("/\"(\w+)\":(\d+[\.\d+[e\+\d+]*]*)/", "\"\$1\":\"\$2\"", $value);
            return json_decode($value, $assoc);
        }
    }
    
   /**
     * To get ip belonged region according to ip
     * @param <string> $ip ip address, heard that can be ip strings, split by "," ,but i found it not used
     * @param <int> $type 地域名及ISP的显示格式  0 默认文本格式；
                                                 1 regions.xml中的id；
                                                 2 regions.xml中的code，即ISO-3166的地区代码；
                                                 3 regions.xml中的fips，即FIPS的地区代码。
     * @param <string> $encoding  编码类, gbk或utf-8, 默认为gbk
     * @return <int or array>
     */
    static function get_ip_source($ip, $type = 1, $encoding = 'utf-8') {
        if (!function_exists('lookup_ip_source'))
            return 0;
        $code = lookup_ip_source($ip, $type, $encoding);
        switch ($code) {
            case "-1" :
                return 0;
                break;
            case "-2" :
                return 0;
                break;
            case "-3" :
                return 0;
                break;
            default :
                return $code;
                break;
        }
    
    }
    
    /**
     * 根据实际场景，获取客户端IP
     * @param	boolean		$to_long	是否变为整型IP
     * @return	string
     */
    public static function getClientIp($to_long = false) {
        static $ip = null;
        if ($ip === null) {
            $module = Yaf_Dispatcher::getInstance()->getRequest()->getModuleName();
            switch ($module) {
            	case 'Internal' :
            	    isset($_GET['cip']) && $ip = $_GET['cip'];
            	    break;
            	case 'Openapi' :
            	    $headers = getallheaders();
            	    isset($headers['cip']) && $ip = $headers['cip'];
            	    break;
            	case 'Cli' :
            	    $ip = '0.0.0.0';
            	    //					$ip = `/sbin/ifconfig | grep 'inet addr' | awk '{ print $2 }' | awk -F ':' '{ print $2}' | head -1`;
            	    break;
            }
            empty($ip) && $ip = self::getRealClientIp();
        }
    
        return $to_long ? self::ip2long($ip) : $ip;
    }
    
    /**
     * 获取真实的客户端ip地址
     *
     * This function is copied from login.sina.com.cn/module/libmisc.php/get_ip()
     *
     * @param boolean $to_long	可选。是否返回一个unsigned int表示的ip地址
     * @return string|float		客户端ip。如果to_long为真，则返回一个unsigned int表示的ip地址；否则，返回字符串表示。
     */
    public static function getRealClientIp($to_long = false) {
        $forwarded = self::getServer('HTTP_X_FORWARDED_FOR');
        if ($forwarded) {
            $ip_chains = explode(',', $forwarded);
            $proxied_client_ip = $ip_chains ? trim(array_pop($ip_chains)) : '';
        }
    
        if (Comm_Util::isPrivateIp(self::getServer('REMOTE_ADDR')) && isset($proxied_client_ip)) {
            $real_ip = $proxied_client_ip;
        } else {
            $real_ip = self::getServer('REMOTE_ADDR');
        }
    
        return $to_long ? self::ip2long($real_ip) : $real_ip;
    }
    
    /**
     * 得到当前请求的环境变量
     *
     * @param string $name
     * @return string|null 当$name指定的环境变量不存在时，返回null
     */
    public static function getServer($name) {
        return isset($_SERVER[$name]) ? $_SERVER[$name] : null;
    }
    
    
    /**
     * 判断是否是内网ip
     * @param string $ip
     * @return boolean
     */
    public static function isPrivateIp($ip) {
        $ip_value = self::ip2long($ip);
        return ($ip_value & 0xFF000000) === 0x0A000000 ||         //10.0.0.0-10.255.255.255
        ($ip_value & 0xFFF00000) === 0xAC100000 ||         //172.16.0.0-172.31.255.255
        ($ip_value & 0xFFFF0000) === 0xC0A80000;        //192.168.0.0-192.168.255.255
    
    }
    
    public static function getCurrentUrl($urlencode = true) {
        $req_uri = self::getServer('REQUEST_URI');
        if (null === $req_uri) {
            $req_uri = self::getServer('PHP_SELF');
        }
    
        $https = self::getServer('HTTPS');
        $s = null === $https ? '' : ('on' == $https ? 's' : '');
    
        $protocol = self::getServer('SERVER_PROTOCOL');
        $protocol = strtolower(substr($protocol, 0, strpos($protocol, '/'))) . $s;
    
        $port = self::getServer('SERVER_PORT');
        $port = ($port == '80') ? '' : (':' . $port);
    
        $server_name = self::getServer('SERVER_NAME');
        $current_url = $protocol . '://' . $server_name . $port . $req_uri;
    
        return $urlencode ? rawurlencode($current_url) : $current_url;
    }

    public static function isPhone($phone){
        return (!empty($phone) && (int)preg_match("/^1[3|4|5|7|8][0-9]{9}$/ ", $phone) > 0) ? true : false;
    }

    public static function checkNickname($nickname){
        return (!empty($nickname) && false !== preg_match("/^[0-9a-zA-Z\x{4e00}-\x{9fa5}]+$/u",$nickname) > 0) ? true : false;
    }

    public static function checkPassword($password){
        return !empty($password) ? true : false;
    }
}
