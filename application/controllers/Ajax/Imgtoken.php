<?php
/**
 * 获取图片上传token
 * User: shaohua5
 * Date: 16/11/1
 * Time: 下午4:54
 */

class Ajax_ImgtokenController extends AbstractController {

    /*
     * 获取token
     */
    public function tokenAction(){
        $num = (int)Comm_Context::param('num', 0);
        //获取token
        $token = ImgtokenModel::getToken($num);
        $this->data['token'] = $token;
        $this->format(0);
        $this->jsonResult();
        return $this->end();
    }
}