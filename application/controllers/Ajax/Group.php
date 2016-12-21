<?php
/**
 * ajax 小组
 * User: shaohua5
 * Date: 16/10/26
 * Time: 下午3:49
 */
class Ajax_GroupController extends AbstractController {

    /*
     * 加载小组成员列表
     */
    public function getMoreUserListAction(){
        $this->tpl = 'common/group_usercardlist.phtml';
        $this->data['page'] = (int)Comm_Context::param('page', 1);
        $this->data['pageSize'] = (int)Comm_Context::param('pageSize', 10);
        $this->data['g_g_id'] = (int)Comm_Context::param('g_g_id');
        if (empty($this->data['g_g_id'])){ //返回错误
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        $users = Group_GroupModel::getGroupUserList($this->data['g_g_id'], $this->data['page'], $this->data['pageSize']);
        $this->data['users'] = $users['memberRst'];
        $this->format(0);
        $this->data['html'] = $this->assign(true);
        unset($this->data['users']); //去除不需要的返回数据
        $this->jsonResult();
        return $this->end();
    }

    /*
     * 用户加入小组
     */
    public function addGroupAction(){
        $g_g_id = (int)Comm_Context::form('g_g_id');
        if(!$this->data['isLogin'] || empty($g_g_id)) { //判断用户是否登录 或 参数是否合法
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        $uid = Judge::getUid();
        $res = Group_GroupModel::addGroup($uid, $g_g_id);
        if($res == 0){ //0 成功
            $this->format(0);
        }else{
            $this->format(1);
        }
        $this->jsonResult();
        return $this->end();
    }

    /*
     * 用户退出小组
     */
    public function deleteGroupAction(){
        $g_g_id = (int)Comm_Context::form('g_g_id');
        if(!$this->data['isLogin'] || empty($g_g_id)) { //判断用户是否登录 或 参数是否合法
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }
        $uid = Judge::getUid();
        $res = Group_GroupModel::deleteGroup($uid, $g_g_id);
        if($res == 0){ //0 成功
            $this->format(0);
        }else{
            $this->format(1);
        }
        $this->jsonResult();
        return $this->end();
    }

    /*
     * 加载更多blog流
     */
    public function getMoreBlogFeedAction(){
        $this->tpl = 'common/blogcardlist.phtml';
        $this->data['page'] = (int)Comm_Context::param('page', 1);
        $this->data['pageSize'] = (int)Comm_Context::param('pageSize', 15);
        $this->data['g_g_id'] = (int)Comm_Context::param('g_g_id');
        $this->data['feed_type'] = (int)Comm_Context::param('feed_type', 0);   //0 全部   1 精华
        if(empty($this->data['g_g_id'])){
            $this->format(1);
            $this->jsonResult();
            return $this->end();
        }

        $this->data['groupInfoAndBlogs'] = Group_GroupModel::getGroupInfoAndBlogs($this->data['g_g_id'], 0, $this->data['feed_type'], $this->data['page'], $this->data['pageSize']);
        //var_dump($this->data['groupInfoAndBlogs']);
        $this->data['pageCount'] = $this->data['groupInfoAndBlogs']['pageCount'];
        $this->data['blog_list'] = $this->data['groupInfoAndBlogs']['blogs'];
        $this->data['total'] = count($this->data['blog_list']);
        $this->format(0);
        $this->data['html'] = $this->assign(true);
        unset($this->data['groupInfoAndBlogs']); //去除不需要的返回数据
        unset($this->data['blog_list']);
        $this->jsonResult();
        return $this->end();
    }

    /*
     * 推荐小组 换一换
     */
    public function recommendGroupAction(){
        $this->tpl = 'common/recommend_group_list.phtml';
        //推荐小组
        $this->data['recommend_groups'] = Group_GroupModel::getRecommendGroups();
        //是否加入了小组
        if($this->data['recommend_groups'] && $this->data['isLogin']){
            $ggids = array();
            foreach($this->data['recommend_groups'] as $group){
                $ggids[] = $group['ggid'];
            }
            $in_group = Group_GroupModel::isInGroup(Judge::getUid(), implode($ggids, ','));
            $this->data['is_in_group'] = array();
            foreach($in_group as $item){
                if($item['status'] == 0){
                    $this->data['is_in_group'][$item['ggid']] = 0;
                }else{
                    $this->data['is_in_group'][$item['ggid']] = 1;
                }
            }
        }
        $this->format(0);
        $this->data['html'] = $this->assign(true);
        unset($this->data['recommend_groups']);
        unset($this->data['is_in_group']);
        $this->jsonResult();
        return $this->end();
    }

    /*
     * 热门贴 加载更多
     */
    public function moreRecommendBlogsAction(){
        $this->tpl = 'common/blogcardlist.phtml';
        //获取热门贴
        $uid = Judge::getUid();
        $uid = $uid ? $uid : null;
        $this->data['blog_list'] = Group_GroupModel::getRecommendBlogs($uid);
        $this->data['total'] = count($this->data['blog_list']);
        $this->format(0);
        $this->data['html'] = $this->assign(true);
        unset($this->data['blog_list']);
        $this->jsonResult();
        return $this->end();
    }

    /*
     * 加载全部小组
     */
    public function getAllGroupAction(){
        $this->tpl = 'common/all_group_cardlist.phtml';
        $this->data['page'] = Comm_Context::param('page', 1);
        $this->data['pageSize'] = Comm_Context::param('pageSize', 40);
        //获取全部小组
        $all_groups = Group_GroupModel::getAllGroup($this->data['page'], $this->data['pageSize']);
        $this->data['pageCount'] = $all_groups['pageCount'];
        $all_groups = $all_groups['rst'];
        $ggids = array();
        if($all_groups){
            foreach($all_groups as $group){
                $ggids[] = $group['g_g_id'];
            }
        }
        //是否加入了小组
        $uid = Judge::getUid();
        $uid = $uid ? $uid : null;
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
        //游戏推荐
        $this->data['all_groups'] = $all_groups;
        $this->format(0);
        $this->data['html'] = $this->assign(true);
        unset($this->data['is_in_group']);
        unset($this->data['all_groups']);
        $this->jsonResult();
        return $this->end();
    }

    /**
     * 获取更多的网游小组
     */
    public function getMoreWebGroupAction(){
        $uid = (int)Judge::getUid();
        $type = (int)Comm_Context::form('type');
        $page = (int)Comm_Context::form('page');
        $num = (int)Comm_Context::form('num');

        $web_group = Group_GroupModel::getAllGroupByType($type,$uid,$page,$num);
        $web_group = $web_group['rst'];
        //检查用户是否加入小组
        foreach($web_group as $key=>$group){
            $isInGroup = Group_GroupModel::isInGroup($uid,$group['g_g_id']);
            foreach($isInGroup as $v){
                if(1 == $v['status']){
                    unset($web_group[$key]);
                }
            }
        }
        $this->data['web_group'] = $web_group;
        $this->jsonResult();
        return $this->end();
    }

    /**
     * 获取更多的手游小组
     */
    public function getMoreMobileGroupAction(){
        $uid = (int)Judge::getUid();
        $type = (int)Comm_Context::form('type');
        $page = (int)Comm_Context::form('page');
        $num = (int)Comm_Context::form('num');

        $mobile_group = Group_GroupModel::getAllGroupByType($type,$uid,$page,$num);
        $mobile_group = $mobile_group['rst'];
        //检查用户是否加入小组
        foreach($mobile_group as $key=>$group){
            $isInGroup = Group_GroupModel::isInGroup($uid,$group['g_g_id']);
            foreach($isInGroup as $v){
                if(1 == $v['status']){
                    unset($mobile_group[$key]);
                }
            }
        }
        $this->data['mobile_group'] = $mobile_group;
        $this->jsonResult();
        return $this->end();
    }
}