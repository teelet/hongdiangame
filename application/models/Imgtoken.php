<?php
/**
 * 图片上传token
 * User: shaohua5
 * Date: 16/11/1
 * Time: 下午5:02
 */

class ImgtokenModel{
    /*
     * 获取token
     */
    public static function getToken($num = 1){
        if($num <= 0){
            return false;
        }
        $params = array(
            'num' => $num
        );

        $token = Service_Api::get_img_token($params);
        return 0 == $token['code'] ? $token['result'] : [];
    }
}