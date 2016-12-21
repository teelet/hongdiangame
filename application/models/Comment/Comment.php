<?php

class Comment_CommentModel{

    /*
     * 文章、帖子 评论列表
     * aid	  文章或帖子id
     * ctype  1文章，2帖子
     * page	  页数
     * count  每页的评论数
     */
    public static function getCommentList($aid, $ctype = 1, $page = 1, $count = 20){
        if(!is_numeric($aid) || $aid <= 0){
            return false;
        }
        $params = array(
            'aid' => $aid,
            'ctype' => $ctype,
            'page' => $page,
            'count' => $count
        );
        $rs = Service_Api::comment_list($params);
        //print_r($rs);exit;
        return 0 == $rs['code'] ? $rs['result'] : [];
    }

    /*
     * 文章、帖子 发评论
     * uid 当前登录用户id
     * aid    文章或帖子id
     * ctype  1文章，2帖子
     * content  评论内容
     * imgs  评论图片
     */
    public static function addComment($uid, $aid, $ctype, $content, $imgs = ''){
        $content = trim($content);
        if(!is_numeric($uid) || !is_numeric($aid) || $content == ''){
            return false;
        }
        $params = array(
                'uid' => $uid,
                'aid' => $aid,
                'ctype' => $ctype,
                'content' => $content,
                'source' => 1,
            );
        if(!empty($imgs)){
            $params['imgs'] = $imgs;
        }
        //Service_Api::$debug = 1;
        $rs = Service_Api::comment_add($params);
        //print_r($rs);exit;
        return 0 == $rs['code'] ? $rs['result'] : [];
    }

    /*
     * 点赞
     * uid 用户id
     * c_id 评论或回复的id
     */
    public static function doFavor($uid, $c_id){
        if(empty($uid) || empty($c_id)){
            return false;
        }
        $params = array(
            'uid' => $uid,
            'c_id' => $c_id
        );
        $rs = Service_Api::do_favor($params);
        return 0 == $rs['code'] ? $rs['result'] : [];
    }

    /*
     * 获取文章主评或回帖的回复列表
     * cid  主评、回帖的id
     * page
     * count
     */
    public static function getCommentReplyList($cid, $page = 1, $count = 20){
        if(empty($cid)){
            return false;
        }
        $params = array(
            'cid' => $cid,
            'page' => $page,
            'count' => $count
        );
        $rs = Service_Api::comment_reply_list($params);
        return 0 == $rs['code'] ? $rs['result'] : [];
    }


    public static function addReply($uid,$pid,$content){
        $content = trim($content);
        if(!is_numeric($uid) || !is_numeric($pid) || '' == $content){
            return false;
        }

        $params = array(
            'uid' => $uid,
            'pid' => $pid,
            'content' => $content,
            'source' => 1
        );
        //Service_Api::$debug = 1;
        $rs = Service_Api::comment_reply_add($params);
        //print_r($rs);
        return 0 == $rs['code'] ? $rs['result'] : [];
    }

    /*
     * 回复评论
     * uid 用户id
     * pid 被回复的评论id
     * content 内容
     */
    public static function addCommentReply($uid, $pid, $content){
        $content = trim($content);
        if(!is_numeric($uid) || !is_numeric($pid) || $content == ''){
            return false;
        }
        $params = array(
            'uid' => $uid,
            'pid' => $pid,
            'content' => $content,
            'source' => 1
        );
        $rs = Service_Api::comment_reply_add($params);
        return 0 == $rs['code'] ? $rs['result'] : [];
    }

}