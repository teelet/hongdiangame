<?php
/**
 * 操作文章
 * User: shaohua5
 */
class Article_ArticleModel {

    /*
     * 获取文章内容
     * aid 文章id
     */
    public static function getArticleContent($aid){
       $params = array(
                'aid' => $aid,
            );
        //Service_Api::$debug = true;
        $rs = Service_Api::article_detail($params);
        //print_r($rs);
        return 0 == $rs['code'] ? $rs['result'] : [];
    }

    /*
     * 依据文章id，获取相关文章及广告
     * aid 文章id
     */

    public static function getRelateArticle($aid){
        $params = array(
                'aid' => $aid,
            );
        //Service_Api::$debug = true;
        $rs = Service_Api::article_related($params);
        //print_r($rs);
        return 0 == $rs['code'] ? $rs['result'] : [];
    }


    /*
     * 资讯首页接口
     */

    public static function getHotArticle($is_first = 0){
        $params = array(
                'is_first' => $is_first,
            );
        //Service_Api::$debug = true;
        $rs = Service_Api::hot_article($params);
        //print_r($rs);
        return 0 == $rs['code'] ? $rs['result'] : [];
    }

    // 按游戏获取热门资讯

    public static function getGameHotArticle($gid, $type = 0){
        $params = array(
                'gid'   => (int)$gid,
                'type'  => (int)$type,
            );
        //Service_Api::$debug = true;
        $rs = Service_Api::game_hot_article($params);
        //print_r($rs);exit;
        return 0 == $rs['code'] ? $rs['result'] : [];
    }

    //24小时热榜
    public static function hot24(){
        $params = array();
        //Service_Api::$debug = true;
        $rs = Service_Api::hot24($params);
        //print_r($rs);exit;
        return 0 == $rs['code'] ? $rs['result'] : [];
    }

    public static function report($uid,$aid){
        $params = array(
                'uid' => $uid,
                'aid' => $aid,
            );
        //Service_Api::$debug = true;
        $rs = Service_Api::article_report($params);
        //print_r($rs);exit;
        return 0 == $rs['code'] ? $rs['result'] : false;
    }

    public static function unread($type,$uid = 0){
        $params = array(
                'uid' => $uid,
                'aid' => $aid,
            );
        //Service_Api::$debug = true;
        $rs = Service_Api::has_unread_article($params);
        //print_r($rs);exit;
        return 0 == $rs['code'] ? $rs['result'] : false;
    }
}
