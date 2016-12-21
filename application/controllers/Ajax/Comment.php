<?php
/**
 * ajax test
 * User: shaohua5
 */

class Ajax_CommentController extends AbstractController {

    //文章页获取评论列表
    public function commentlistAction(){
        $this->tpl = 'common/article_comment_list.phtml';

        $aid = (int)Comm_Context::param('aid');
        if($aid < 1){
            $this->data['statusCode'] = -1;
            $this->data['message'] = '文章错误';
            $this->jsonResult();
            return $this->end();
        }
        $ctype = 1;//文章
        $page = (int)Comm_Context::param('page');
        $page = $page > 0 ? $page : 1;
        $pagesize = 20;

        $comment = Comment_CommentModel::getCommentList($aid,$ctype,$page,$pagesize);
        if(!empty($comment)){
            $this->data['statusCode'] = 0;
            $this->data['comment'] = $comment;
            $this->data['html'] = $this->assign(true);
            unset($this->data['comment']);
        }else{
            $this->data['statusCode'] = 1;
            $this->data['message'] = '没有评论了';
        }
        $this->jsonResult();
        return $this->end();
    }

    //文章页发表评论
    public function commentAction(){
        $this->tpl = 'common/article_comment_list.phtml';

        if(!Judge::isLogin()){
            $this->data['statusCode'] = -99;
            $this->data['message'] = '未登录';
            $this->jsonResult();
            return $this->end();
        }

        $uid = Judge::getUid();
        $aid = (int)Comm_Context::form('aid');
        $content = trim(Comm_Context::form('content'));
        if($aid < 1 || '' == $content){
            $this->data['statusCode'] = -1;
            $this->data['message'] = '参数错误';
            $this->jsonResult();
            return $this->end();
        }
        $ctype = 1;

        $result = Comment_CommentModel::addComment($uid,$aid,$ctype,$content);
        if(!empty($result)){
            $comment['list'][] = $result;
            $comment['userinfo'][$uid] = User_UserModel::getUserInfo($uid); 

            $this->data['statusCode'] = 0;
            $this->data['comment'] = $comment;
            $this->data['html'] = $this->assign(true);
            unset($this->data['comment']);
        }else{
            $this->data['statusCode'] = 1;
            $this->data['message'] = '评论失败';
        }
        $this->jsonResult();
        return $this->end();
    }

    //回复列表
    public function replylistAction(){
        $this->tpl = 'common/article_reply_list.phtml';

        $cid = (int)Comm_Context::param('cid');
        if($cid < 1){
            $this->data['statusCode'] = -1;
            $this->data['message'] = '参数错误';
            $this->jsonResult();
            return $this->end();
        }
        $page = (int)Comm_Context::param('page');
        $page = $page > 0 ? $page : 1;
        $pagesize = 20;

        $rs = Comment_CommentModel::getCommentReplyList($cid,$page,$pagesize);

        if(!empty($rs)){
            $reply = 1 == $page ? array_slice($rs['list'], 3) : $rs['list'];
            $userinfo = $rs['userinfo'];

            $this->data['statusCode'] = 0;
            $this->data['pageCount'] = $rs['pageCount'];
            $this->data['reply'] = $reply;
            $this->data['userinfo'] = $userinfo;
            $this->data['html'] = $this->assign(true);
            unset($this->data['reply']);
            unset($this->data['userinfo']);
        }else{
            $this->data['statusCode'] = 1;
            $this->data['message'] = '没有回复了';
        }
        $this->jsonResult();
        return $this->end();
    }

    //发表回复
    public function replyAction(){
        $this->tpl = 'common/article_reply_list.phtml';

        if(!Judge::isLogin()){
            $this->data['statusCode'] = -99;
            $this->data['message'] = '未登录';
            $this->jsonResult();
            return $this->end();
        }

        $uid = Judge::getUid();
        $pid = (int)Comm_Context::form('pid');
        $content = trim(Comm_Context::form('content'));
        if($pid < 1 || '' == $content){
            $this->data['statusCode'] = -1;
            $this->data['message'] = '参数错误';
            $this->jsonResult();
            return $this->end();
        }

        $rs = Comment_CommentModel::addReply($uid,$pid,$content);
        if(!empty($rs)){
            $reply[] = $rs;
            $userinfo[$uid] = User_UserModel::getUserInfo($uid); 

            $this->data['statusCode'] = 0;
            $this->data['reply'] = $reply;
            $this->data['userinfo'] = $userinfo;
            $this->data['html'] = $this->assign(true);
            unset($this->data['reply']);
            unset($this->data['userinfo']);
        }else{
            $this->data['statusCode'] = 1;
            $this->data['message'] = '评论失败';
        }

        $this->jsonResult();
        return $this->end();
    }

    public function doFavorAction(){
        $uid = Comm_Context::form('uid', Judge::getUid());
        $c_id = Comm_Context::form('c_id');
        $res = Comment_CommentModel::doFavor($uid, $c_id);
        if(empty($res)){
            $this->format(1);
        }else{
            $this->format(0);
        }
        $this->jsonResult();
        return $this->end();
    }
}