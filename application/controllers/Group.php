<?php
/**
 * 小组 group
 * User: shaohua5
 * Date: 16/10/19
 * Time: 下午5:37
 */

class GroupController extends AbstractController {

    private static $recommend_count = 5; //推荐的游戏个数
    private static $page_size = 15;
    /*
     * 默认动作
     */
    public function indexAction() {
        //小组id
        $g_g_id = (int)Comm_Context::param('g_g_id');
        if($this->data['isLogin']){ //判断用户是否登录
            $uid = Judge::getUid();
            //我的游戏
            $this->data['mygame'] = Game_GameModel::getGameByUid($uid);
            //我加入的小组
            $my_groups = Group_GroupModel::getUserGroups($uid, 0);
            $this->data['my_groups'] = $my_groups['rst'];
            if(empty($g_g_id)){
                $this->hotDefault();
            }else{
                //判断g_g_id是否为用户已加入小组
                $in_group = Group_GroupModel::isInGroup($uid, $g_g_id);
                if($in_group[0]['status'] == 1){
                    $this->data['is_my_group'] = true;
                }
                //获取我的签到信息
                $this->data['my_sign_info'] = Group_GroupModel::getUserSignInfo($uid, $g_g_id);

                $this->groupIndex($g_g_id);
            }
        }else{ //没有登录, 显示热门贴
            $this->data['mygame'] = Game_GameModel::getGameByUdid();
            if(empty($g_g_id)){
                $this->hotDefault();
            }else{
                $this->groupIndex($g_g_id);
            }
        }
        $this->data['from'] = 2; // 1首页  2小组
        $this->assign();
        return $this->end();
    }

    private function hotDefault(){
        $this->tpl = 'hotblog.phtml'; //视图
        //获取全部小组
        $all_groups = Group_GroupModel::getAllGroup(1, 5)['rst'];
        $ggids = array();
        if($all_groups){
            foreach($all_groups as $group){
                $ggids[] = $group['g_g_id'];
            }
        }
        //获取热门贴
        $uid = Judge::getUid();
        $uid = $uid ? $uid : null;
        $this->data['blog_list'] = Group_GroupModel::getRecommendBlogs($uid);
        //推荐小组
        $this->data['recommend_groups'] = Group_GroupModel::getRecommendGroups();

        if($this->data['recommend_groups'] && $this->data['isLogin']){
            foreach($this->data['recommend_groups'] as $group){
                $ggids[] = $group['ggid'];
            }
        }
        //是否加入了小组
        $this->data['is_in_group'] = array();
        if(count($ggids) > 0 && !empty($uid)){
            $in_group = Group_GroupModel::isInGroup($uid, implode(array_unique($ggids), ','));
            foreach($in_group as $item){
                if($item['status'] == 0){
                    $this->data['is_in_group'][$item['ggid']] = 0;
                }else{
                    $this->data['is_in_group'][$item['ggid']] = 1;
                }
            }
        }
        //活跃游友
        $this->data['act_users'] = Group_GroupModel::getActUsers();
        //游戏推荐
        $this->data['all_groups'] = $all_groups;

        $this->data['head_title'] = sprintf($this->format_title, '小组 - ', '');
    }

    /*
     * 发帖页
     */
    public function postBlogAction(){
        $this->tpl = 'postblog.phtml'; //视图
        $this->data['g_g_id'] = Comm_Context::param('g_g_id', 0);
        $this->data['g_g_name'] = Comm_Context::param('g_g_name');
        //判断g_g_id是否为用户已加入小组
        $in_group = Group_GroupModel::isInGroup(Judge::getUid(), $this->data['g_g_id']);
        $this->data['is_my_group'] = false;
        if($in_group[0]['status'] == 1){
            $this->data['is_my_group'] = true;
        }
        $this->data['head_title'] = sprintf($this->format_title, '发帖');
        $this->assign();
        return $this->end();
    }

    /*
     * 小组成员
     */
    public function userListAction(){
        $this->tpl = 'groupuserlist.phtml'; //视图
        $g_g_id = (int)Comm_Context::param('g_g_id'); //小组id
        $this->data['g_g_name'] = Comm_Context::param('g_g_name', ''); //小组名
        //获取小组用户列表
        $this->data['user_list'] = Group_GroupModel::getGroupUserList($g_g_id, 1, self::$page_size);
        $this->data['page_size'] = self::$page_size;
        $this->data['page_count'] =  $this->data['user_list']['pageCount'];
        $this->data['g_g_id'] = $g_g_id;
        $this->data['head_title'] = sprintf($this->format_title, '小组成员 - '.$this->data['g_g_name']. ' - ', '');
        $this->assign();
        return $this->end();
    }

    private function groupIndex($g_g_id){
        $this->tpl = 'groupindex.phtml'; //视图
        $g_g_name = Comm_Context::param('g_g_name');
        //feed流类型   0 全部 1 精华
        $this->data['feed_type'] = (int)Comm_Context::param('feed_type', 0);
        //获取小组简介和帖子列表
        $this->data['groupInfoAndBlogs'] = Group_GroupModel::getGroupInfoAndBlogs($g_g_id, 0, $this->data['feed_type'], 1, self::$page_size);
        //var_dump($this->data['groupInfoAndBlogs']);
        //活跃游友
        $this->data['act_users'] = Group_GroupModel::getActUsers();
        //小组成员
        $this->data['user_list'] = Group_GroupModel::getGroupUserList($g_g_id, 1, 5);

        $this->data['g_g_id'] = $g_g_id;
        $this->data['blog_list'] = $this->data['groupInfoAndBlogs']['blogs'];
        $this->data['page_size'] = self::$page_size;
        $this->data['head_title'] = sprintf($this->format_title, $g_g_name.' - ', '');
    }

    /**
     * 获取所有的小组
     */
    public function getAllGroupAction(){
        $this->tpl = 'group.phtml';

        //如果用户登录
        if($this->data['isLogin']){
            //获取我的小组
            $my_groups = Group_GroupModel::getUserGroups(Judge::getUid(), 0);
            $my_groups = $my_groups['rst'];
            foreach($my_groups as $key=>$value){
                $isInGroup = Group_GroupModel::isInGroup((int)Judge::getUid(),$value['g_g_id']);
                foreach($isInGroup as $v){
                    if(0 == $v['status']){  //剔除用户未加入小组
                        unset($my_groups[$key]);
                    }
                }
            }
            $this->data['my_groups'] = $my_groups;
        }

        //所有网游小组
        $web_group = Group_GroupModel::getAllGroupByType(1,(int)Judge::getUid());
        $web_group = $web_group['rst'];
        if(is_array($web_group)){
            //检查用户是否已经加入网游小组
            foreach ($web_group as $key=>$web){
                $isInGroup = Group_GroupModel::isInGroup((int)Judge::getUid(),$web['g_g_id']);
                foreach($isInGroup as $v){
                    if(1 == $v['status']){
                        unset($web_group[$key]);
                    }
                }
            }
        }
        $this->data['web_group'] = $web_group;

        //所有手游小组
        $mobile_group = Group_GroupModel::getAllGroupByType(2,(int)Judge::getUid());
        $mobile_group = $mobile_group['rst'];
        if(is_array($mobile_group)){
            //检查用户是否已经加入手游小组
            foreach($mobile_group as $key=>$mobile){
                $isInGroup = Group_GroupModel::isInGroup((int)Judge::getUid(),$mobile['g_g_id']);
                foreach($isInGroup as $v){
                    if(1 == $v['status']){
                        unset($mobile_group[$key]);
                    }
                }
            }
        }
        $this->data['mobile_group'] = $mobile_group;


        //24小时热门资讯
        $hot24 = Article_ArticleModel::hot24();
        $this->data['hot24'] = $hot24;

        //热门视频
        $hotVideo = Blog_BlogModel::getHotVideo();
        $this->data['hotVideo'] = $hotVideo;
        $this->data['from'] = 2;
        $this->data['head_title'] = sprintf($this->format_title, '小组 - ', '');
        $this->assign();
        return $this->end();
    }
}