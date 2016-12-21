<?php
/**
 * 帖子
 * User: shaohua5
 * Date: 16/10/21
 * Time: 下午3:32
 */

class BlogController extends AbstractController {

    private static $count = 20;

    /*
     * 帖子详情页
     */
    public function detailAction(){
        $bid = (int)Comm_Context::param('bid'); //帖子id
        $g_g_name = Comm_Context::param('g_g_name'); //小组名
        if(!is_numeric($bid) || $bid<=0){
            $this->redirect('/');
        }
        $this->tpl = 'blogdetail.phtml';
        //获取帖子详情
        $uid = Judge::getUid();
        $uid = $uid ? $uid : null;
        $this->data['blog'] = Blog_BlogModel::getBlogDetail($bid, $uid);
        //相关月底
        $this->data['related_blogs'] = Blog_BlogModel::getRelatedBlogs($bid);
        //热门视频

        //评论信息
        if(!$this->data['blog']['reply_num'] > 0){
            $this->data['comment_list'] = Comment_CommentModel::getCommentList($bid, 2, 1, self::$count);
        }
        $this->data['page_size'] = self::$count;
        $this->data['g_g_name'] = $g_g_name;
        $this->data['floor'] = 1; //回帖楼层

        $this->data['head_title'] = sprintf($this->format_title, $this->data['blog']['title'].' - '.$g_g_name. ' - ', '');
        $this->assign();
        return $this->end();
    }
}