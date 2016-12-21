<?php
/**
 * 游戏 model
 * User: shaohua5
 * Date: 16/10/20
 * Time: 上午10:37
 */

class Game_GameModel {

    /*
     * 获取全部游戏
     */
    public static function getAllGames(){
        $params = array();
        $games = Service_Api::game_all_type($params);
        return 0 == $games['code'] ? $games['result'] : [];
    }

    //web专用获取全部游戏接口，如果用户登录，需传uid，返回的游戏列表有hasAdd字段，标记用户是否关注过该游戏。
    public static function getAllGamesByUid($uid){
        $params = array(
                'uid' => (int)$uid,
            );
        //Service_Api::$debug = true;
        $rs = Service_Api::game_all_type_uid($params);
        //print_r($rs);
        return 0 == $rs['code'] ? $rs['result'] : false;
    }

    //按udid获取用户游戏
    public static function getGameByUdid(){
    	$params = array();
    	//Service_Api::$debug = true;
    	$rs = Service_Api::game_by_user($params);
        //print_r($rs);
    	return 0 == $rs['code'] ? $rs['result'] : false;
    }

    //按uid获取用户游戏
    public static function getGameByUid($uid){
    	$params = array(
    			'uid' => $uid,
    		);
    	//Service_Api::$debug = true;
    	$rs = Service_Api::game_by_uid($params);
        //print_r($rs);
    	return 0 == $rs['code'] ? $rs['result'] : false;
    }

    //按udid添加游戏
    public static function addGameByUdid($gid){
    	$params = array(
                'gid'   => $gid,
            );
        //Service_Api::$debug = true;
        $rs = Service_Api::user_add_game($params);
        //print_r($rs);exit;
        return 0 == $rs['code'] ? $rs['result'] : false;
    }

    //按uid添加游戏
    public static function addGameByUid($uid,$gid){
        $params = array(
                'uid'   => $uid,
                'gid'   => $gid,
            );
        //Service_Api::$debug = true;
        $rs = Service_Api::uid_add_game($params);
        //print_r($rs);exit;
        return 0 == $rs['code'] ? $rs['result'] : false;
    }

    //按udid删除游戏
    public static function delGameByUdid($gid){
    	$params = array(
                'gid'   => $gid,
            );
        //Service_Api::$debug = true;
        $rs = Service_Api::user_delete_game($params);
        //print_r($rs);exit;
        return 0 == $rs['code'] ? true : false;
    }

    //按uid删除游戏
    public static function delGameByUid($uid,$gid){
        $params = array(
                'uid'   => $uid,
                'gid'   => $gid,
            );
        //Service_Api::$debug = true;
        $rs = Service_Api::uid_delete_game($params);
        //print_r($rs);exit;
        return 0 == $rs['code'] ? true : false;
    }
}