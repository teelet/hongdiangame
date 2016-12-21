<?php
/**
 * model: User
 * User: shaohua5
 */

class User_UserModel{

    public static function test(){
        return "hello world";
    }

    public static function delGame($uid,$gid){
        $params = array(
                'uid'   => $uid,
                'gid'   => $gid,
            );
        //Service_Api::$debug = true;
        $rs = Service_Api::user_delete_game($params);
        //print_r($rs);exit;
        return 0 == $rs['code'] ? true : false;
    }

    public static function login($phone,$password){
        $params = array(
                'phone' => $phone,
                'password' => $password,
            );
        //Service_Api::$debug = true;
        $rs = Service_Api::login($params);
        //print_r($rs);exit;

        //202 未激活用户
        return 0 == $rs['code'] || 202 == $rs['code'] ? $rs : false;
    }

    public static function regist($phone,$nickname,$password){
        $params = array(
                'phone' => $phone,
                'nickname' => $nickname,
                'password' => $password,
            );
        //Service_Api::$debug = true;
        $rs = Service_Api::regist($params);
        //print_r($rs);
        return 0 == $rs['code'] ? $rs['result'] : false;
    }
    //发短信
    public static function sms($uid,$phone){
        $params = array(
                'uid' => $uid,
                'phone' => $phone,
            );
        //Service_Api::$debug = true;
        $rs = Service_Api::registCode($params);
        //print_r($rs);
        return 0 == $rs['code'] ? $rs['result'] : false;
    }
    //激活账号
    public static function activate($uid,$code){
        $params = array(
                'uid' => $uid,
                'code' => $code,
            );
        //Service_Api::$debug = true;
        $rs = Service_Api::checkCode($params);
        //print_r($rs);
        return 0 == $rs['code'] ? $rs['result'] : false;
    }
    //依据phone获取uid
    public static function getUidByPhone($phone){
        $params = array(
                'phone' => $phone,
            );
        //Service_Api::$debug = true;
        $rs = Service_Api::get_uid_by_phone($params);
        //print_r($rs);exit;
        return 0 == $rs['code'] ? $rs['result'] : false;
    }
    //重置密码
    public static function resetPassword($uid,$password,$code){
        $params = array(
                'uid' => $uid,
                'password' => $password,
                'code' => $code,
            );
        //Service_Api::$debug = true;
        $rs = Service_Api::reset_you($params);
        //print_r($rs);
        return 0 == $rs['code'] ? $rs['result'] : false;
    }

    /*
     * 他人基本信息
     * uid
     */
    public static function getUserInfo($uid){
        $params = array(
            'other_uid' => $uid
        );
        $rs = Service_Api::get_user_info($params);
        return 0 == $rs['code'] ? $rs['result'] : false;
    }

    /*
     * 用户的发布的评论列表
     */
    public static function getUserCommentList($uid, $page=1, $pageSize=20){
        $params = array(
            'uid' => $uid,
            'page' => $page,
            'num' => $pageSize
        );
        $rs = Service_Api::user_comment_list($params);
        return 0 == $rs['code'] ? $rs['result'] : false;
    }

    /*
     * 用户已加入的小组列表
     */
    public static function getUserGroupList($uid, $page=1, $pageSize=20){
        $params = array(
            'uid' => $uid,
            'page' => $page,
            'num' => $pageSize
        );
        $rs = Service_Api::user_group_list($params);
        return 0 == $rs['code'] ? $rs['result'] : false;
    }

    /*
     * 用户发布的帖子列表
     */
    public static function getUserBlogList($uid, $page=1, $pageSize=20){
        $params = array(
            'uid' => $uid,
            'page' => $page,
            'num' => $pageSize
        );
        $rs = Service_Api::user_blog_list($params);
        return 0 == $rs['code'] ? $rs['result'] : false;
    }

    public static function getMsgUnreadNum($uid){
        $params = array(
            'uid' => $uid
        );
        $rs = Service_Api::msg_unread_num($params);
        return 0 == $rs['code'] ? $rs['result'] : false;
    }

    /*
     * 用户消息
     * uid
     * type 0：系统消息， 1：评论的回复， 2：帖子的回帖，3：回帖的回复，4：评论的赞，5：回帖的赞
     */
    public static function getMsg($uid, $type, $page = 1, $count = 20){
        $params = array(
            'uid' => $uid,
            'type' => $type,
            'page' => $page,
            'count' => $count
        );
        $rs = Service_Api::get_user_msg($params);
        return 0 == $rs['code'] ? $rs['result'] : false;
    }

    /*
     * 删除个人消息
     * uid
     * id 消息id
     */
    public static function deleteMsg($uid, $id){
        $params = array(
            'uid' => $uid,
            'id' => $id
        );
        $rs = Service_Api::delete_user_msg($params);
        return $rs['code'];
    }

    /**
     * 修改密码
     */
    public static function modifyYou($uid,$old_pass,$new_pass){
       $params = array(
           'uid' => $uid,
           'old_pass' => $old_pass,
           'new_pass' => $new_pass
       );
        $rs = Service_Api::modify_you($params);
        return $rs['code'];
    }

    /**
     * 修改头像
     */
    public static function updateFace($uid,$img){
        $params = array(
            'uid' => $uid,
            'img' => $img
        );
        $rs = Service_Api::update_face($params);
        return $rs['code'];
    }

    /**
     * 获取自己的信息
     */
    public static function getMySelf($uid){
        $params = array(
            'uid' => $uid
        );
        $rs = Service_Api::myself($params);
        return 0 == $rs['code'] ? $rs['result'] : false;
    }

    /**
     * 编辑个人信息
     */
    public static function edit($uid,$nickname,$summary,$sex,$birthday,$province,$city){
        $params = array(
            'uid' => $uid,
            'nickname' => $nickname,
            'summary' => $summary,
            'sex' => $sex,
            'birthday' => $birthday,
            'province' =>$province,
            'city' => $city
        );
        $rs = Service_Api::edit($params);
        return $rs['code'];
    }

    /*
     * 检查是否有未读的推荐流
     * uid 非必传
     * type 0：热门帖子
     */
    public static function hadUnreadRecommend($uid, $type){
        $params = array(
            'type' => $type
        );
        if(!empty($uid)){
            $params['uid'] = $uid;
        }
        //Service_Api::$debug=true;
        $rs = Service_Api::has_unread_recommend($params);
        //var_dump($rs);
        return $rs['result'];
    }
}