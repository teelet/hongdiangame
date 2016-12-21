<?php
/**
 * 搜索
 * User: shaohua5
 * Date: 16/11/28
 * Time: 下午4:57
 */

class Ajax_SearchController extends AbstractController {

    /*
     * 搜索框
     */
    public function doSearchAction(){
        $keywords = Comm_Context::form('keywords');
        if(empty($keywords)){
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        $this->tpl = 'search_list.phtml';
        $this->data['result'] = Search_SearchModel::search($keywords);
        $this->data['html'] = $this->assign(true);
        unset($this->data['result']);
        $this->format(0);
        $this->jsonResult();
        return $this->end();
    }

    /*
     * 搜索结果页
     */
    public function searchResultAction(){
        $this->data['keywords'] = Comm_Context::param('keywords');
        $this->data['tab'] = Comm_Context::param('tab', 0); //0文章 1小组 2用户
        $this->data['page'] = Comm_Context::param('page', 1);
        $this->data['count'] = Comm_Context::param('page_size', 20);
        if(empty($this->data['keywords'])){
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        $res = Search_SearchModel::search($this->data['keywords'], $this->data['tab'] ,$this->data['page'], $this->data['count'])['rst'];
        switch ($this->data['tab']) { //0文章 1小组 2用户
            case 0 :
                $this->tpl = 'common/index_article_list.phtml';
                break;
            case 1 :
                $this->tpl = 'common/search_group_list.phtml';
                $ggids = array();
                foreach($res as $group){
                    $ggids[] = $group['g_g_id'];
                }
                $in_group = Group_GroupModel::isInGroup(Judge::getUid(), implode($ggids, ','));
                $this->data['is_in_group'] = array();
                foreach($in_group as $item){
                    if($item['status'] == 0){
                        $this->data['is_in_group'][$item['ggid']] = 0;
                    }else{
                        $this->data['is_in_group'][$item['ggid']] = 1;
                    }
                }
                break;
            case 2 :
                $this->tpl = 'common/search_user_list.phtml';
                break;
        }
        $this->data['list'] = $res;
        $this->data['html'] = $this->assign(true);
        unset($this->data['list']);
        $this->format(0);
        $this->jsonResult();
        return $this->end();
    }

}