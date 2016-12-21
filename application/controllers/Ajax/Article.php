<?php
/**
 * ajax test
 * User: shaohua5
 */

class Ajax_ArticleController extends AbstractController {

    //首页咨询流获取更多
    public function indexMoreAction(){
        $this->tpl = 'common/index_article_list.phtml';
    	
        $main = Article_ArticleModel::getHotArticle(0);
    	$list = $main[0]['rst'];

    	$this->data['statusCode'] = 0;
        $this->data['list'] = $list;
        $this->data['total'] = count($list);
        $this->data['html'] = $this->assign(true);
        unset($this->data['list']);

        $this->jsonResult();
        return $this->end();
    }

    public function indexGameMoreAction(){
        $this->tpl = 'common/index_article_list.phtml';

        $gid = (int)Comm_Context::param('gid');
        if(empty($gid)){
            $this->data['statusCode'] = -1;
            $this->data['message'] = '参数错误';
            $this->jsonResult();
            return $this->end();
        }
        $type = (int)Comm_Context::param('type',0);

        $list = Article_ArticleModel::getGameHotArticle($gid,$type);

        $this->data['statusCode'] = 0;
        $this->data['list'] = $list;
        $this->data['total'] = count($list);
        $this->data['html'] = $this->assign(true);
        unset($this->data['list']);

        $this->jsonResult();
        return $this->end();
    }

    public function reportAction(){
        if(!Judge::isLogin()){
            $this->data['statusCode'] = -99;
            $this->data['message'] = '未登录';
            $this->jsonResult();
            return $this->end();
        }
        $uid = Judge::getUid();

        $aid = (int)Comm_Context::form('aid');

        if(empty($aid)){
            $this->data['statusCode'] = -1;
            $this->data['message'] = '参数错误';
            $this->jsonResult();
            return $this->end();
        }

        $rs = Article_ArticleModel::report($uid,$aid);
        $this->data['statusCode'] = 0;
        $this->data['message'] = '举报成功';

        $this->jsonResult();
        return $this->end();
    }

    public function unreadAction(){
        $type = (int)Comm_Context::param('type');
        $uid = (int)Judge::getUid();

        $rs = Article_ArticleModel::unread($type,$uid);

        if(1 == (int)$rs){
            $this->data['statusCode'] = 0;
            $this->data['message'] = '您有未读新闻,请点击查看';
        }else{
            $this->data['statusCode'] = 1;
            $this->data['message'] = '没有未读新闻';
        }

        $this->jsonResult();
        return $this->end();
    }
}