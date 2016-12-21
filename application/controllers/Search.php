<?php
/**
 * 搜索
 * User: shaohua5
 * Date: 16/11/29
 * Time: 上午11:19
 */

class SearchController extends AbstractController {

    private static $count = 20;
    /*
     * 搜索结果页
     */
    public function resultAction(){
        $page = Comm_Context::param('page', 1);
        $this->tpl = 'search_result.phtml';
        $this->data['keywords'] = Comm_Context::param('keywords', '');
        $this->data['page_size'] = self::$count;
        //默认搜索文章
        $this->data['list'] = Search_SearchModel::search($this->data['keywords'], 0, $page, self::$count)['rst'];
        //24小时热搜
        $this->data['hot_search_list'] = Search_SearchModel::hot_search();
        $this->assign();
        return $this->end();
    }
}