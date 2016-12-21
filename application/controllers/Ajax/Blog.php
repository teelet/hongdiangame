<?php
/**
 * blog
 * User: shaohua5
 * Date: 16/11/2
 * Time: 下午3:23
 */

class Ajax_BlogController extends AbstractController {
    private static $pageSize = 10;

    /*
     * 发帖
     */
    public function postAction(){
        $g_g_id = (int)Comm_Context::form('g_g_id'); //小组id
        $title = Comm_Context::form('title');
        $content = Comm_Context::form('content');
        $imgs = Comm_Context::form('imgs');
        $uid = Judge::getUid();
        if(empty($uid) || empty($g_g_id) || empty($title) || empty($content)){
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        $res = Blog_BlogModel::postBlog($uid, $g_g_id, $title, $content, $imgs);
        if(!empty($res) && $res['code'] == 0){
            $this->data['bid'] = $res['result']['bid'];
            $this->format(0);
            $this->jsonResult();
            return $this->end();
        }else{
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
    }

    /*
     * 帖子更多回帖
     */
    public function getMoreCommentAction(){
        $this->tpl = 'common/blog_comment_list.phtml';
        $this->data['bid'] = (int)Comm_Context::param('bid');
        if(!is_numeric($this->data['bid']) || $this->data['bid'] <= 0){
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        $this->data['page'] = (int)Comm_Context::param('page', 1);
        $this->data['page_size'] = (int)Comm_Context::param('pageSize', 20);
        $this->data['floor'] = (int)Comm_Context::param('floor', 1);
        $this->data['comment_list'] = Comment_CommentModel::getCommentList($this->data['bid'], 2, $this->data['page'], $this->data['page_size']);
        $this->data['html'] = $this->assign(true);
        unset($this->data['comment_list']);
        $this->format(0);
        $this->jsonResult();
        return $this->end();
    }

    /*
     * 回复评论
     */
    public function commentReplyAction(){
        $this->tpl = 'common/blog_reply_cardlist.phtml';
        $this->data['uid'] = Judge::getUid();
        $this->data['pid'] = (int)Comm_Context::form('pid');
        if(empty($this->data['uid']) || empty($this->data['pid'])){
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        $this->data['content'] = Comm_Context::form('content', '');
        $this->data['touid'] = Comm_Context::form('touid');
        $this->data['toname'] = Comm_Context::form('toname');
        $this->data['commentuid'] = Comm_Context::form('commentuid');
        $this->data['floor'] = Comm_Context::form('floor');
        $res = Comment_CommentModel::addCommentReply($this->data['uid'], $this->data['pid'], $this->data['content']);
        $this->data['result'] = $res;
        if(empty($res)){
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        $this->data['html'] = $this->assign(true);
        unset($this->data['content']);
        $this->format(0);
        $this->jsonResult();
        return $this->end();
    }

    /*
     * 回帖
     */
    public function postCommentAction(){
        $this->data['uid'] = Judge::getUid();
        $this->data['bid'] = (int)Comm_Context::form('bid');
        if(empty($this->data['uid']) || empty($this->data['bid'])){
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        $this->data['content'] = Comm_Context::form('content', '');
        $this->data['imgs'] = Comm_Context::form('imgs', '');
        $res = Comment_CommentModel::addComment($this->data['uid'], $this->data['bid'], 2, $this->data['content'], $this->data['imgs']);
        $this->data['result'] = $res;
        $this->format(0);
        $this->jsonResult();
        return $this->end();
    }

    /*
     * 加载更多回帖的回复
     */
    public function getMoreCommentReplyAction(){
        $this->tpl = 'common/blog_comment_reply_list.phtml';
        $this->data['id'] = (int)Comm_Context::param('id'); //回帖的id
        $this->data['comment_uid'] = Comm_Context::param('comment_uid'); //主回帖的id
        $this->data['floor'] = (int)Comm_Context::param('floor'); //楼层
        if(empty($this->data['id']) || empty($this->data['comment_uid']) || empty($this->data['floor'])){
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        $this->data['page'] = (int)Comm_Context::param('page');
        $rs = Comment_CommentModel::getCommentReplyList($this->data['id'], $this->data['page'], self::$pageSize);
        if(empty($rs)){
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        $this->data['replys'] = $rs['list'];
        $this->data['user_info'] = $rs['userinfo'];
        $this->data['html'] = $this->assign(true);
        unset($this->data['replys']);
        unset($this->data['user_info']);
        $this->format(0);
        $this->jsonResult();
        return $this->end();
    }

    /*
     * 删除帖子
     */
    public function blogDeleteAction(){
        $this->data['uid'] = (int)Comm_Context::param('uid');
        $this->data['bid'] = (int)Comm_Context::param('bid');
        if(empty($this->data['uid']) || empty($this->data['bid'])){
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        $res = Blog_BlogModel::blogDelete($this->data['uid'], $this->data['bid']);
        if(empty($res)){
            $this->format(1);
        }else{
            $this->format(0);
        }
        $this->jsonResult();
        return $this->end();
    }
}