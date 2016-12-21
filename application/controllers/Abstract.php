<?php
/**
 * Index 模块下，所有controller的父类
 * shaohua
 */

class AbstractController extends Yaf_Controller_Abstract {

    protected $param = array();  //参数
    protected $data = array();   //结果
    protected $format_title = '%s红点游戏%s(www.hongdiangame.com)';
    
    /*模版文件*/
    protected $tpl = '';

    public function init(){
        $this->data['head_title'] = sprintf($this->format_title, '', ' - 陪你玩!');
        if(Judge::isLogin()){
            $this->data['isLogin'] = true;
            $this->data['self'] = User_UserModel::getUserInfo(Judge::getUid());
        }
    }
    
    /*模版渲染*/
    public function assign($return_string = FALSE){
        try {
            $view = new Yaf_View_Simple(TPL_PATH);
            $view->assign($this->data);
            $html = $view->render($this->tpl);
            if($return_string){
                return $html;
            }else{
                echo $html;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    /*输出json串*/
    public function jsonResult($json_encode = FALSE) {
        unset($this->data['isLogin']);
        unset($this->data['self']);
        try {
            if($json_encode){
                return json_decode($this->data);
            }else{
                header('Content-type:text/json; charset=utf-8');
                echo json_encode($this->data);
            }
        }catch (Exception $e) {
            echo $e->getMessage();
        } 
    }
    
    public function end() {
        //关闭自动渲染
        return FALSE;
    }
    
    /*
     * 格式化结果
     * status 报告信息基本 0 成功， 1 失败
     */
    public function format($status = 0){
        switch ($status) {
            case 0 :
                $this->data['statusCode'] = Comm_Config::getPhpConf('error/iErrorMsg.statusCode.success');
                $this->data['message'] = Comm_Config::getPhpConf('error/iErrorMsg.message.successMsg');
                break;
            case 1 :
                $this->data['statusCode'] = Comm_Config::getPhpConf('error/iErrorMsg.statusCode.error');
                $this->data['message'] = Comm_Config::getPhpConf('error/iErrorMsg.message.errorMsg');
                break;
        }
    }
}