<?php
/**
 * ajax test
 * User: shaohua5
 */

class Ajax_TestController extends AbstractController {

    /*
     * 文章上线,下线
     */
    public function test(){
        $this->data['status'] = 0;
        $this->data['result'] = "操作成功";
        $this->jsonResult();
        return $this->end();
    }
}