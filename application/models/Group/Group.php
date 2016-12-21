<?php
/**
 * 小组
 * User: shaohua5
 * Date: 16/10/20
 * Time: 上午11:23
 */

class Group_GroupModel{

    /*
     * 获取热门推荐贴
     * uid 用户id
     */
    public static function getRecommendBlogs($uid = null){
        $params = array();
        if(!empty($uid)){
            $params['uid'] = $uid;
        }
        $blogs = Service_Api::group_recommend($params);
        return 0 == $blogs['code'] ? $blogs['result'] : [];
    }

    /*
     * 用户加入、常去的小组
     * uid
     * type  0：我加入的小组 1：常去的小组
     */
    public static function getUserGroups($uid, $type = 0){
        $params = array(
            'uid' => $uid,
            'type' => $type
        );
        $groups = Service_Api::user_group($params);
        return 0 == $groups['code'] ? $groups['result'] : [];
    }

    /*
     * 获取推荐小组
     */
    public static function getRecommendGroup(){
        $groups = Service_Api::rec_group_list();
        return 0 == $groups['code'] ? $groups['result'] : [];
    }

    /*
     * 获取活跃游友
     */
    public static function getActUser(){
        $users = Service_Api::act_user_list();
        return 0 == $users['code'] ? $users['result'] : [];
    }

    /*
     * 获取小组简介和帖子列表
     * g_g_id  小组id
     * type 帖子排序方式        非必传，0：默认，按发帖时间倒排 1：按热度排序 2… 先按0默认
     * feed_type     数据类型	必传 0：普通帖 1：精华帖
     * page 页数
     * num 每页条数
     */
    public static function getGroupInfoAndBlogs($g_g_id, $type = 0, $feed_type = 0, $page = 1, $num = 20){
        if (empty($g_g_id)){
            return false;
        }
        $params = array(
            'ggid' => $g_g_id,
            'type' => $type,
            'feed_type' => $feed_type,
            'page' => $page,
            'num'  => $num
        );
        $groupInfoAndBlogs = Service_Api::group_info_and_blogs($params);
        return 0 == $groupInfoAndBlogs['code'] ? $groupInfoAndBlogs['result'] : [];
    }

    /*
     * 获取小组用户列表
     * g_g_id 小组id
     */
    public static function getGroupUserList($g_g_id, $page = 1, $num = 20)
    {
        if (empty($g_g_id)) {
            return false;
        }
        $params = array(
            'ggid' => $g_g_id,
            'page' => $page,
            'num' => $num
        );
        $userList = Service_Api::group_user_list($params);
        return 0 == $userList['code'] ? $userList['result'] : [];
    }

    /*
     * 活跃用户
     */
    public static function getActUsers(){
        $params = array();
        $users = Service_Api::act_user_list($params);
        return 0 == $users['code'] ? $users['result'] : [];
    }

    /*
     * 推荐小组
     */
    public static function getRecommendGroups(){
        $params = array();
        $groups = Service_Api::rec_group_list($params);
        return 0 == $groups['code'] ? $groups['result'] : [];
    }

    /*
     * 加入小组
     * uid 用户id
     * g_g_id 小组id
     */
    public static function addGroup($uid, $g_g_id){
        if(empty($uid) || empty($g_g_id)){
            return false;
        }
        $params = array(
            'uid' => $uid,
            'ggid' => $g_g_id
        );
        $res = Service_Api::user_add_group($params);
        return $res['code'];
    }

    /*
     * 退出小组
     * uid 用户id
     * g_g_id 小组id
     */
    public static function deleteGroup($uid, $g_g_id){
        if(empty($uid) || empty($g_g_id)){
            return false;
        }
        $params = array(
            'uid' => $uid,
            'ggid' => $g_g_id
        );
        $res = Service_Api::user_delete_group($params);
        return $res['code'];
    }

    /*
     * 获取个人签到信息
     * uid
     * g_g_id
     */
    public static function getUserSignInfo($uid, $g_g_id){
        if(empty($uid) || empty($g_g_id)){
            return false;
        }
        $params = array(
            'uid' => $uid,
            'ggid' => $g_g_id
        );
        $signInfo = Service_Api::group_user_sign_info($params);
        return 0 == $signInfo['code'] ? $signInfo['result'] : [];
    }

    /*
     * 检查用户是否加入了小组
     * uid
     * ggid  多个小组id逗号隔开  1,2,3
     */
    public static function isInGroup($uid, $ggid){
        $params = array(
            'uid' => $uid,
            'ggid' => $ggid
        );
        $res = Service_Api::is_in_group($params);
        return 0 == $res['code'] ? $res['result'] : [];
    }

    /*
     * 用户签到
     * uid
     * $ggid 小组id
     */
    public static function userSign($uid, $ggid){
        $params = array(
            'uid' => $uid,
            'ggid' => $ggid
        );
        $res = Service_Api::group_user_sign($params);
        return 0 == $res['code'] ? $res : [];
    }

    /*
     * 获取全部小组
     */
    public static function getAllGroup($page = 1, $num = 20){
        $params = array(
            'page' => $page,
            'num' => $num
        );
        $res = Service_Api::get_all_group($params);
        return 0 == $res['code'] ? $res['result'] : [];
    }

    //根据类型列出小组
    public static function getAllGroupByType($type,$uid,$page=1,$num=20){
        $params = array(
            'type' => $type,
            'uid' => $uid,
            'page'=>$page,
            'num'=>$num
        );
        $res = Service_Api::all_group_by_type($params);
        return 0 == $res['code'] ? $res['result'] : [];
    }
}
















