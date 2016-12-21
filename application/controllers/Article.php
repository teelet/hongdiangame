<?php
/**
 * @name ArticleController
 * @desc 文章控制器
 */

class ArticleController extends AbstractController {

    /**
     * 默认动作
     */

    public function indexAction() {
        $this->tpl = 'article.phtml';
        
        $aid = (int)Comm_Context::param('aid');

        //文章详情
        $article = Article_ArticleModel::getArticleContent($aid);
        $this->data['article'] = $article;

        //获取第一页评论
        $comment = Comment_CommentModel::getCommentList($aid);
        $this->data['comment'] = $comment;

        //相关文章及广告
        $relate = Article_ArticleModel::getRelateArticle($aid);
        $this->data['relate'] = $relate;

        //热门视频
        $hotVideo = Blog_BlogModel::getHotVideo();
        $this->data['hotVideo'] = $hotVideo;

        $server = $this->getRequest()->getServer();
        $this->data['cururl'] = 'http://'.$server['HTTP_HOST'].$server['REQUEST_URI'];

        $this->data['list_type'] = Comm_Config::getPhpConf('articlelisttype');

        $this->data['head_title'] = sprintf($this->format_title, $this->data['article']['title'].' - ', '');
        $this->assign();
        return $this->end();
    }
}