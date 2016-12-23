<?php
/**
 * @name IndexController
 * @desc 默认控制器
 */

class UserController extends AbstractController {

    private static $count = 20;

    /**
     * 默认动作
     */

    public function indexAction() {
        //
    }

    /*
     * 用户个人消息
     */
    public function messageAction(){
        if(!$this->data['isLogin']){
            $this->redirect("/");
        }
        $uid = Judge::getUid();
        $this->tpl = 'user_msg.phtml'; //视图
        $this->data['tab'] = (int)Comm_Context::param('tab', 0);
        $this->data['type'] = (int)Comm_Context::param('type', 0);
        $this->data['include_tpl'] = 'common/msg_cardlist.phtml';
        //分别获取数据
        $tmp = '';
        switch ($this->data['tab']){
            case 0 : //评论回复
                $feed_type = 1;
                $tmp = '评论';
                break;
            case 1 : //评论的赞
                $feed_type = 4;
                $tmp = '评论';
                break;
            case 2 : //收到的回帖
                $feed_type = 2;
                $tmp = '小组';
                break;
            case 3 : //回帖的回复
                $feed_type = 3;
                $tmp = '小组';
                break;
            case 4 : //回帖的赞
                $feed_type = 5;
                $tmp = '小组';
                break;
            case 5 : //系统通知
                $feed_type = 0;
                $tmp = '系统';
                break;
            default :
                $feed_type = 1;
                $tmp = '评论';
        }
        $msg = User_UserModel::getMsg($uid, $feed_type, 1, self::$count);
        $this->data['messages'] = $msg['rst'];
        $this->data['page_count'] = $msg['pageCount'];
        $this->data['page_size'] = self::$count;
        $this->data['feed_type'] = $feed_type;
        $this->data['head_title'] = sprintf($this->format_title, $tmp.'通知 - ', '');
        $this->assign();
        return $this->end();
    }

    /*
     * 用户个人profile
     */
    public function profileAction(){
        $this->data['page_size'] = 20;
        $this->tpl = 'user_profile.phtml'; //视图
        $uid = Judge::getUid();
        //访问的用户uid 默认访问自己
        $this->data['vid'] = Comm_Context::param('uid', $uid);
        //用户个人信息
        $this->data['user_info'] = User_UserModel::getUserInfo($this->data['vid']);
        //获取推荐小组
        $this->data['recommend_groups'] = Group_GroupModel::getRecommendGroups();
        //是否加入了小组
        if($this->data['recommend_groups'] && $this->data['isLogin']){
            $ggids = array();
            foreach($this->data['recommend_groups'] as $group){
                $ggids[] = $group['ggid'];
            }
            $in_group = Group_GroupModel::isInGroup($uid, implode($ggids, ','));
            $this->data['is_in_group'] = array();
            foreach($in_group as $item){
                if($item['status'] == 0){
                    $this->data['is_in_group'][$item['ggid']] = 0;
                }else{
                    $this->data['is_in_group'][$item['ggid']] = 1;
                }
            }
        }
        //获取用户发出的评论列表
        $res = User_UserModel::getUserCommentList($this->data['vid'], 1, $this->data['page_size']);
        $this->data['pro_comment_list'] = $res['rst'];
        $this->data['page_count'] = $res['pageCount'];
        $this->data['head_title'] = sprintf($this->format_title, $this->data['user_info']['nickname'].' - ', '');
        $this->assign();
        return $this->end();
    }

    /**
     * 账户设置与安全 userinfo
     */
    public function userinfoAction(){
        if(!$this->data['isLogin']){
            $this->redirect("/");
        }

        //获取用户的信息
        $uid = Judge::getUid();
        $re = User_UserModel::getMySelf($uid);
        $this->data['nickname'] = $re['nickname'];  //昵称
        $this->data['summary'] = $re['summary'];   //个性签名
        $this->data['sex'] = $re['sex']; //性别 1代表男 2代表女
        $this->data['birthday'] = $re['birthday'] ;  //生日
        $this->data['province'] = $re['province'];  //省份
        $this->data['city'] = $re['city'];  //城市

        $this->data['url'] = $re['url'];

        $this->tpl = "user_userinfo.phtml"; //视图
        $this->data['type'] = (int)Comm_Context::param('type',0);
        $tmp = '';
        switch($this->data['type']){
            case 1 :
                $this->data['include_tpl'] = 'common/user_headpicture.phtml';
                $tmp = '';
                break;
            case 2 :
                $this->data['include_tpl'] = 'common/user_changepassword.phtml';
                $tmp = '';
                break;
            case 3 :
                $this->data['include_tpl'] = 'common/user_changeuserinfo.phtml';
                $tmp = '个人信息';
                break;
            default :
                $this->data['include_tpl'] = 'common/user_detailinfo.phtml';
                $tmp = '个人信息';
                break;
        }
        $this->data['head_title'] = sprintf($this->format_title, $tmp.'通知 - ', '');
        $this->assign();
        return $this->end();
    }
}
