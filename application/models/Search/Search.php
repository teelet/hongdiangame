<?php
/**
 * 搜索
 * User: shaohua5
 * Date: 16/11/28
 * Time: 下午5:27
 */

class Search_SearchModel{
    /*
     * 搜索
     * tab  0文章 1小组 2用户
     */
    public static function search($keywrods, $tab = 0, $page = 1, $count = 10){
        $params = array(
            'keywords' => $keywrods,
            'type' => $tab,
            'page' => $page,
            'num' => $count
        );
        $rs = Service_Api::search($params);
        return 0 == $rs['code'] ? $rs['result'] : false;
    }

    /*
     * 24小时热搜
     */
    public static function hot_search(){
        $params = array();
        $rs = Service_Api::hot_search_list($params);
        return 0 == $rs['code'] ? $rs['result'] : false;
    }
}