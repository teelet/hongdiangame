<?php
/**
 * ajax test
 * User: shaohua5
 */

class Ajax_UserController extends AbstractController {

	public function loginAction(){
        if(Judge::isLogin()){
            $this->data['statusCode'] = 0;
            $this->data['message'] = '登录成功';
            $this->jsonResult();
            return $this->end();
        }

        $phone = Comm_Context::form('phone');
        $password = Comm_Context::form('password');

        if(!Comm_Util::isPhone($phone)){
            $this->data['statusCode'] = -1;
            $this->data['message'] = '手机号格式错误';
            $this->jsonResult();
            return $this->end();
        }

        if(!Comm_Util::checkPassword($password)){
            $this->data['statusCode'] = -2;
            $this->data['message'] = '密码格式错误';
            $this->jsonResult();
            return $this->end();
        }

        if(false !== $rs = User_UserModel::login($phone,$password)){
            $user = $rs['result'];
            if(0 == $rs['code']){
                Judge::login($user['sid'],$user['uid']);
                $this->data['message'] = '登录成功';
            }elseif(202 == $rs['code']){
                User_UserModel::sms($user['uid'],$phone);

                $this->data['code'] = md5($user['name'].$user['uid'].Judge::getVerifyCode());
                $this->data['uid'] = $user['uid'];
                $this->data['phone'] = $user['name'];
                $this->data['message'] = '请激活账号';
            }
            $this->data['statusCode'] = (int)$rs['code'];
        }else{
            $this->data['statusCode'] = 1;
            $this->data['message'] = '手机号或密码错误';
        }

        $this->jsonResult();
        return $this->end();
    }

    public function logoutAction(){
        if(Judge::isLogin()){
            if(Judge::logout()){
                $this->data['statusCode'] = 0;
                $this->data['message'] = '退出登录成功';
            }
        }else{
            $this->data['statusCode'] = 1;
            $this->data['message'] = '退出登录失败';
        }
        $this->jsonResult();
        return $this->end();
    }

    public function registAction(){
        $phone = Comm_Context::form('phone');
        $nickname = Comm_Context::form('nickname');
        $password = Comm_Context::form('password');

        if(!Comm_Util::isPhone($phone)){
            $this->data['statusCode'] = -1;
            $this->data['message'] = '手机号格式错误';
            $this->jsonResult();
            return $this->end();
        }

        if(!Comm_Util::checkPassword($password)){
            $this->data['statusCode'] = -2;
            $this->data['message'] = '密码格式错误';
            $this->jsonResult();
            return $this->end();
        }

        if(!Comm_Util::checkNickname($nickname)){
            $this->data['statusCode'] = -3;
            $this->data['message'] = '昵称格式错误';
            $this->jsonResult();
            return $this->end();
        }

        if(false !== $user = User_UserModel::regist($phone,$nickname,$password)){
            User_UserModel::sms($user['uid'],$phone);
            /*
            由于注册和发第一次短信是连贯操作，所以没有有效校验手段。
            此处返回一个手机号与固定盐的校验串，保证暴露在外的短信接口不能用随意手机号调用。
            但是由于无法接触服务端存储，并没有办法做频次控制
            */
            $this->data['code'] = md5($phone.$user['uid'].Judge::getVerifyCode());
            $this->data['uid'] = $user['uid'];
            $this->data['phone'] = $phone;

            $this->data['statusCode'] = 0;
            $this->data['message'] = '注册成功';
        }else{
            $this->data['statusCode'] = 1;
            $this->data['message'] = '注册失败';
        }
        $this->jsonResult();
        return $this->end();
    }

    public function smsAction(){
        $phone = Comm_Context::form('phone');
        $uid = Comm_Context::form('uid');
        $code = Comm_Context::form('code');

        if(md5($phone.$uid.Judge::getVerifyCode()) != $code){
            $this->data['statusCode'] = -1;
            $this->data['message'] = '参数错误';
            $this->jsonResult();
            return $this->end();
        }

        if(false !== User_UserModel::sms($uid,$phone)){
            $this->data['statusCode'] = 0;
            $this->data['message'] = '短信已发送，请耐心等待';
        }else{
            $this->data['statusCode'] = 1;
            $this->data['message'] = '短信发送太频繁，请稍后重试';
        }
        $this->jsonResult();
        return $this->end();
    }

    public function activateAction(){
        $phone = Comm_Context::form('phone');
        $uid = Comm_Context::form('uid');
        $smscode = Comm_Context::form('smscode');
        $code = Comm_Context::form('code');


        if(md5($phone.$uid.Judge::getVerifyCode()) != $code){
            $this->data['statusCode'] = -1;
            $this->data['message'] = '参数错误';
            $this->jsonResult();
            return $this->end();
        }

        if(false === preg_match('/^\d{6}$/', $smscode)){
            $this->data['statusCode'] = -2;
            $this->data['message'] = '短信验证码错误';
            $this->jsonResult();
            return $this->end();
        }

        if(false !== User_UserModel::activate($uid,$smscode)){
            $this->data['statusCode'] = 0;
            $this->data['message'] = '账号激活成功';
        }else{
            $this->data['statusCode'] = 1;
            $this->data['message'] = '激活失败，请重新激活';
        }
        $this->jsonResult();
        return $this->end();
    }

    public function verifyAction(){
        $phone = Comm_Context::form('phone');

        if(!Comm_Util::isPhone($phone)){
            $this->data['statusCode'] = -1;
            $this->data['message'] = '手机号格式错误';
            $this->jsonResult();
            return $this->end();
        }

        if(false != $uid = User_UserModel::getUidByPhone($phone)){
            User_UserModel::sms($uid,$phone);

            $this->data['code'] = md5($phone.$uid.Judge::getVerifyCode());
            $this->data['uid'] = $uid;
            $this->data['phone'] = $phone;

            $this->data['statusCode'] = 0;
            $this->data['message'] = '';
        }else{
            $this->data['statusCode'] = 1;
            $this->data['message'] = '账号未注册';
        }
        $this->jsonResult();
        return $this->end();
    }

    public function modifyAction(){
        $phone = Comm_Context::form('phone');
        $password = Comm_Context::form('password');
        $smscode = Comm_Context::form('smscode');
        $uid = Comm_Context::form('uid');
        $code = Comm_Context::form('code');

        if(!Comm_Util::checkPassword($password)){
            $this->data['statusCode'] = -1;
            $this->data['message'] = '密码格式错误';
            $this->jsonResult();
            return $this->end();
        }

        if(false === preg_match('/^\d{6}$/', $smscode)){
            $this->data['statusCode'] = -2;
            $this->data['message'] = '短信验证码错误';
            $this->jsonResult();
            return $this->end();
        }

        if(md5($phone.$uid.Judge::getVerifyCode()) != $code){
            $this->data['statusCode'] = -3;
            $this->data['message'] = '参数错误';
            $this->jsonResult();
            return $this->end();
        }

        if(false !== User_UserModel::resetPassword($uid,$password,$smscode)){
            $this->data['statusCode'] = 0;
            $this->data['message'] = '密码重置成功';
        }else{
            $this->data['statusCode'] = 1;
            $this->data['message'] = '密码重置失败';
        }
        $this->jsonResult();
        return $this->end();
    }

    //添加游戏
    public function addGameAction(){
        if(!Judge::isLogin()){
            $this->data['statusCode'] = -99;
            $this->data['message'] = '请先登录';
            $this->jsonResult();
            return $this->end();
        }
        $gid = Comm_Context::form('gid');
        $uid = (int)Judge::getUid();

        if(!preg_match("/^\d+(,\d+)*$/", $gid) || $uid <= 0){
            $this->data['statusCode'] = -1;
            $this->data['message'] = '参数错误';
            $this->jsonResult();
            return $this->end();
        }
        
        $rs = Game_GameModel::addGameByUid($uid,$gid);
        
        if($rs){
            $this->data['statusCode'] = 0;
            $this->data['message'] = '添加成功';
        }else{
            $this->data['statusCode'] = 1;
            $this->data['message'] = '添加失败';
        }

        $this->jsonResult();
        return $this->end();
    }

    //删除游戏
    public function delGameAction(){
        if(!Judge::isLogin()){
            $this->data['statusCode'] = -99;
            $this->data['message'] = '请先登录';
            $this->jsonResult();
            return $this->end();
        }
        $gid = Comm_Context::form('gid');
        $uid = (int)Judge::getUid();

        if(!preg_match("/^\d+(,\d+)*$/", $gid) || $uid <= 0){
            $this->data['statusCode'] = -1;
            $this->data['message'] = '参数错误';
            $this->jsonResult();
            return $this->end();
        }
        
        $rs = Game_GameModel::delGameByUid($uid,$gid);
        
        if($rs){
            $this->data['statusCode'] = 0;
            $this->data['message'] = '删除成功';
        }else{
            $this->data['statusCode'] = 1;
            $this->data['message'] = '删除失败';
        }

        $this->jsonResult();
        return $this->end();
    }

    //同时增加、删除游戏
    public function editGameAction(){
        if(!Judge::isLogin()){
            $this->data['statusCode'] = -99;
            $this->data['message'] = '请先登录';
            $this->jsonResult();
            return $this->end();
        }
        $agid = Comm_Context::form('agid');
        $dgid = Comm_Context::form('dgid');
        $uid = (int)Judge::getUid();

        if(!preg_match("/^(\d+(,\d+)*)?$/", $agid) || !preg_match("/^(\d+(,\d+)*)?$/", $dgid) || $uid <= 0){
            $this->data['statusCode'] = -1;
            $this->data['message'] = '参数错误';
            $this->jsonResult();
            return $this->end();
        }
        
        $rs1 = Game_GameModel::delGameByUid($uid,$dgid);
        $rs2 = Game_GameModel::addGameByUid($uid,$agid);
        
        if($rs1 && $rs2){
            $this->data['statusCode'] = 0;
            $this->data['message'] = '保存成功';
        }else{
            $this->data['statusCode'] = 1;
            $this->data['message'] = '保存失败';
        }

        $this->jsonResult();
        return $this->end();
    }

    /*
     * profile页评论
     */
    public function proCommentAction(){
        $this->tpl = 'common/pro_comment_list.phtml';
        $this->data['page'] = (int)Comm_Context::param('page', 1);
        $this->data['page_size'] = (int)Comm_Context::param('pageSize', 10);
        $this->data['uid'] = (int)Comm_Context::param('uid');
        if (empty($this->data['uid'])){ //返回错误
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        $this->data['user_info'] = User_UserModel::getUserInfo($this->data['uid']);
        //获取用户发出的评论列表
        $res = User_UserModel::getUserCommentList($this->data['uid'], $this->data['page'], $this->data['page_size']);
        $this->data['pro_comment_list'] = $res['rst'];
        $this->data['page_count'] = $res['pageCount'];
        $this->data['html'] = $this->assign(true);
        unset($this->data['pro_comment_list']);
        $this->format(0);
        $this->jsonResult();
        return $this->end();
    }

    /*
     * profile小组
     */
    public function proGroupAction(){
        $this->tpl = 'common/pro_group_list.phtml';
        $this->data['page'] = (int)Comm_Context::param('page', 1);
        $this->data['page_size'] = (int)Comm_Context::param('pageSize', 10);
        $this->data['uid'] = (int)Comm_Context::param('uid');
        if (empty($this->data['uid'])){ //返回错误
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        //他人的小组列表
        $res = User_UserModel::getUserGroupList($this->data['uid'], $this->data['page'], $this->data['page_size']);
        $this->data['pro_group_list'] = $res['rst'];
        $this->data['page_count'] = $res['pageCount'];
        $this->data['html'] = $this->assign(true);
        unset($this->data['pro_comment_list']);
        $this->format(0);
        $this->jsonResult();
        return $this->end();
    }

    /*
     * profile帖子
     */
    public function proBlogAction(){
        $this->tpl = 'common/blogcardlist.phtml';
        $this->data['page'] = (int)Comm_Context::param('page', 1);
        $this->data['page_size'] = (int)Comm_Context::param('pageSize', 10);
        $this->data['uid'] = (int)Comm_Context::param('uid');
        if (empty($this->data['uid'])){ //返回错误
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        //他人的帖子列表
        $res = User_UserModel::getUserBlogList($this->data['uid'], $this->data['page'], $this->data['page_size']);
        $this->data['blog_list'] = $res['rst'];
        $this->data['page_count'] = $res['pageCount'];
        $this->data['html'] = $this->assign(true);
        unset($this->data['blog_list']);
        $this->format(0);
        $this->jsonResult();
        return $this->end();
    }

    /*
     * profile 发布评论
     */
    public function proPostCommentAction(){
        $this->data['uid'] = Judge::getUid();
        $this->data['id'] = Comm_Context::form('id');  //文章id或帖子id
        if (empty($this->data['uid']) || empty($this->data['id'])){ //返回错误
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        $this->data['type'] = Comm_Context::form('type', 1); //1文章，2帖子
        $this->data['content'] = Comm_Context::form('content', '');
        $rs = Comment_CommentModel::addComment($this->data['uid'], $this->data['id'], $this->data['type'], $this->data['content']);
        if(empty($rs)){
            $this->format(1);
        }else{
            $this->format(0);
        }
        $this->jsonResult();
        return $this->end();
    }

	public function msgnumAction(){
        if(!Judge::isLogin()){
            $this->data['statusCode'] = -99;
            $this->data['message'] = '请先登录';
            $this->jsonResult();
            return $this->end();
        }

        $uid = Judge::getUid();
        $rs = User_UserModel::getMsgUnreadNum($uid);

        $this->data['statusCode'] = 0;
        $this->data['result'] = (int)$rs;
        $this->jsonResult();
        return $this->end();
    }

    /*
     * 个人消息加载
     */
    public function moreMessageAction(){
        $this->tpl = 'common/msg_cardlist.phtml';
        $uid = Judge::getUid();
        $this->data['feed_type'] = Comm_Context::param('feed_type');
        if(empty($uid) || empty($this->data['feed_type'])){
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        $this->data['page'] = Comm_Context::param('page', 1);
        $this->data['page_size'] = Comm_Context::param('page_size', 20);
        $msg = User_UserModel::getMsg($uid, $this->data['feed_type'], $this->data['page'], $this->data['page_size']);
        $this->data['messages'] = $msg['rst'];
        $this->data['html'] = $this->assign(true);
        unset($this->data['messages']);
        $this->format(0);
        $this->jsonResult();
        return $this->end();
    }

    /*
     * 删除个人消息
     */
    public function deleteMessageAction(){
        $uid = Judge::getUid();
        $this->data['id'] = Comm_Context::param('id');
        if(empty($uid) || empty($this->data['id'])){
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        $res = User_UserModel::deleteMsg($uid, $this->data['id']);
        if($res == 0){ //成功
            $this->format(0);
        }else{ //失败
            $this->format(1);
        }
        $this->jsonResult();
        return $this->end();
    }

    /*
     * 个人消息回复
     */
    public function msgReplyAction(){
        $this->data['uid'] = Judge::getUid();
        $this->data['pid'] = (int)Comm_Context::form('pid');
        if(empty($this->data['uid']) || empty($this->data['pid'])){
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        $this->data['content'] = Comm_Context::form('content', '');
        $res = Comment_CommentModel::addCommentReply($this->data['uid'], $this->data['pid'], $this->data['content']);
        if(empty($res)){
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        unset($this->data['content']);
        $this->format(0);
        $this->jsonResult();
        return $this->end();
    }

    /*
     * 签到
     */
    public function userSignAction(){
        $this->data['uid'] = Judge::getUid();
        $this->data['ggid'] = (int)Comm_Context::form('ggid');
        if(empty($this->data['uid']) || empty($this->data['ggid'])){
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        $res = Group_GroupModel::userSign($this->data['uid'], $this->data['ggid']);
        if(empty($res)){
            $this->format(1);
        }else{
            $this->format(0);
        }
        $this->jsonResult();
        return $this->end();
    }

    /**
     * 发送验证码
     */
    public function sendVerifyAction(){
        $this->data['uid'] = Comm_Context::form('uid');
        $this->data['phone'] = Comm_Context::form('phone');
        if(!empty($this->data['uid']) && $this->data['uid'] >0 && !empty($this->data['phone'])){
            $rs=User_UserModel::sms($this->data['uid'],$this->data['phone']);
            if(empty($rs)){
                $this->format(1);
            }else{
                $this->format(0);
            }
            $this->jsonResult();
            return $this->end();
        }
    }

    /**
     * 保存修改密码
     */
    public function changePasswordAction(){
        $uid = Comm_Context::form('uid');
        $phone = Comm_Context::form('phone');
        $oldpass = Comm_Context::form('oldpass');
        $newpass = Comm_Context::form('newpass');
        $renewpass = Comm_Context::form('renewpass');
        $smscode =  Comm_Context::form('smscode');

        if(!Comm_Util::checkPassword($oldpass)){
            $this->data['statusCode'] = -1;
            $this->data['message'] = '旧密码格式错误';
            $this->jsonResult();
            return $this->end();
        }

        if($newpass != $renewpass){
            $this->data['statusCode'] = -2;
            $this->data['message'] = '两次输入密码不一致';
            $this->jsonResult();
            return $this->end();
        }

        if(false === preg_match('/^\d{6}$/', $smscode)){
            $this->data['statusCode'] = -3;
            $this->data['message'] = '短信验证码格式错误';
            $this->jsonResult();
            return $this->end();
        }

        //判断旧密码是否正确
        $rs = User_UserModel::login($phone,$oldpass);
        if(empty($rs)){
            //$this->format(1);
            $this->data['statusCode'] = -4;
            $this->data['message'] = '旧密码错误';
            $this->jsonResult();
            return $this->end();
        }

        //保存用户修改密码
        $rs = User_UserModel::modifyYou($uid,$oldpass,$newpass);
        if(empty($rs)){
            $this->data['statusCode'] = 0;
            $this->data['message'] = '密码修改成功';
            $this->jsonResult();
            return $this->end();
        }
    }

    /**
     * 修改用户头像
     */
    public function changeUserFaceAction(){
        $uid = Comm_Context::form('uid');
        $img = Comm_Context::form('img');
        $re = User_UserModel::updateFace($uid,$img);
        if(empty($re)) {
            $this->data['statusCode'] = 0;
            $this->data['message'] = '头像修改成功';
            $this->jsonResult();
            return $this->end();
        }
    }

    /**
     * 根据省份查询城市
     */
    public function getCityByProAction(){
        $proName = Comm_Context::form('proName');
        $allArea = require_once ('conf/area/province.php');

        //将省份的键值对换
        $pro = $allArea['province'];
        $pro = array_flip($pro);

        //找到哪个省对应得键号
        $who = $pro[$proName];

        //找到该省对应得所有城市
        $city = $allArea['city'][$who];
        $this->data['city'] = $city;

        $this->jsonResult();
        return $this->end();
    }

    /**
     * 编辑个人信息
     */
    public function editUserInfoAction(){
        $uid = Judge::getUid();
        if(empty($uid)){
            $this->data['statusCode'] = -1;
            $this->data['message'] = '请登录';
        }
        $nickname = Comm_Context::form('nickname');
        if(empty($nickname)){
            $this->data['statusCode'] = -2;
            $this->data['message'] = '昵称不能为空';
        }
        $summary = Comm_Context::form('summary');
        if(empty($summary)){
            $this->data['statusCode'] = -3;
            $this->data['message'] = '个性签名不能为空';
        }
        $sex = Comm_Context::form('sex');
        if(empty($sex)){
            $this->data['statusCode'] = -4;
            $this->data['message'] = '性别不能为空';
        }
        $year = Comm_Context::form('year');
        $month = Comm_Context::form('month');
        $day = Comm_Context::form('day');
        if($month <10 && $day <10){
            $birthday = $year . '0' . $month . '0' . $day;
        }else if($day < 10){
            $birthday = $year . $month . '0' .$day;
        }else if($month < 10){
            $birthday = $year . '0' . $month . $day;
        }else{
            $birthday = $year .$month .$day ;
        }
        $province = Comm_Context::form('province');
        $city = Comm_Context::form('city');
        if($province =='省份' || $city == '城市'){
            $this->data['statusCode'] = -5;
            $this->data['message'] = '请选择省份或城市';
        }

        $re = User_UserModel::edit($uid,$nickname,$summary,$sex,$birthday,$province,$city);
        if(empty($re)){
            $this->data['statusCode'] = 0;
            $this->data['message'] = '个人资料编辑成功';
            $this->jsonResult();
            return $this->end();
        }

        $this->jsonResult();
        return $this->end();
    }

    /*
     * 检查是否有未阅读的信息
     */
    public function hasUnreadRecommendAction(){
        $this->data['type'] = Comm_Context::param('type', 0);
        $this->data['hasUnreadRecommend'] = 0;
        $has = User_UserModel::hadUnreadRecommend(Judge::getUid(), $this->data['type']);
        if($has){
            $this->data['hasUnreadRecommend'] = 1;
        }
        $this->format(0);
        $this->jsonResult();
        return $this->end();
    }
}