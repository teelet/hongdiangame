<?php

/**
 * @author shaohua5
 * api开发的curl调试工具
 * request: http://i.domain/curl/get
 */

class CurlController extends AbstractController {

    public function getAction(){
        //帖子正文
        $data = array(
            'uid'  => 1, //阅读者uid
            'buid' => 1, //帖子uid
            'bid'  => 10  //帖子bid              
        );
        $url = "http://i.youqu.intra.weibo.com/blog_detail";

        $method = 'GET';
        $http = new Comm_HttpRequest();
		$http->url = $url;
		$http->set_method($method);
        foreach ($data as $k => $v){
            $http->add_query_field($k, $v);
        }
		$http->set_timeout(5000);
		$http->set_connect_timeout(5000);
		$http->send();
		$ret = $http->get_response_content();
		echo $ret;
        exit;
    }

    public function postAction(){
        //发帖
        $data = array(
            'uid'       => 1,
            'g_g_id'    => 1, //英雄联盟
            'title'     => '我是标题newnew',
            'content'   => '我是内容newnew',
            'address'   => '北京',
            'pic_num'   => '2',
            'pic_name_1' => 'a_2.jpg',
            'pic_name_2' => 'b_2.jpg',
            'atime'     => date('Y-m-d H:i:s'),
            'ctime'     => time()
        );
        $url = "http://i.youqu.intra.weibo.com/blog_postblog";

        $method = 'POST';
        $http = new Comm_HttpRequest();
        $http->url = $url;
        $http->set_method($method);
        foreach ($data as $k => $v){
            $http->add_post_field($k, $v);
        }
        $http->set_timeout(5000);
        $http->set_connect_timeout(5000);
        $http->send();
        $ret = $http->get_response_content();
        echo $ret;
        exit;
    }
    
}