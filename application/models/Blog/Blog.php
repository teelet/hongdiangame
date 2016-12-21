<?php
/**
 * blog model
 * User: shaohua5
 * Date: 16/10/21
 * Time: 下午3:48
 */

class Blog_BlogModel{

    /*
     * 获取帖子详情
     * bid 帖子id
     * uid 流浪者uid
     */
    public static function getBlogDetail($bid, $uid = null){
        if(!is_numeric($bid) || $bid<=0){
            return false;
        }
        $params = array(
            'bid' => $bid,
            'uid' => $uid
        );
        $blog = Service_Api::blog_detail($params);
        return 0 == $blog['code'] ? $blog['result'] : [];
    }

    /*
     * 相关阅读
     * bid 帖子id
     */
    public static function getRelatedBlogs($bid){
        if(!is_numeric($bid) || $bid<=0){
            return false;
        }
        $params = array(
            'bid' => $bid
        );
        $blogs = Service_Api::blog_related_blogs($params);
        return 0 == $blogs['code'] ? $blogs['result'] : [];
    }

    /*
     * 热门视频
     * 
     */
    public static function getHotVideo(){
        $params = array(
            );
        $videos = Service_Api::blog_hot_video($params);
        return 0 == $videos['code'] ? $videos['result'] : [];
    }

    /*
     * 发帖
     */
    public static function postBlog($uid, $g_g_id, $title, $content, $imgs='', $source=1){
        if(empty($uid) || empty($g_g_id)){
            return false;
        }
        $params = array(
            'uid' => $uid,
            'g_g_id' => $g_g_id,
            'source' => $source,
            'title' => $title,
            'content' => $content,
        );
        if($imgs){
            $params['imgs'] = $imgs;
        }
        $res = Service_Api::post_blog($params);
        return $res;
    }

    /*
     * 删帖
     */
    public static function blogDelete($uid, $bid){
        $params = array(
            'uid' => $uid,
            'bid' => $bid
        );
        $res = Service_Api::blog_delete($params);
        return $res;
    }
}