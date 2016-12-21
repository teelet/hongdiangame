<?php
/**
 * @name IndexController
 * @desc 默认控制器
 */

class IndexController extends AbstractController {

    /**
     * 默认动作
     */

    public function indexAction() {
        $this->tpl = 'index.phtml';

        $this->data['mygame'] = array();
        $this->data['my_groups'] = array();
        //如果用户登录
        if($this->data['isLogin']){
            //获取用户添加的游戏
            $mygame = Game_GameModel::getGameByUid(Judge::getUid());
            //我加入的小组
            $my_groups = Group_GroupModel::getUserGroups(Judge::getUid(), 0);
            $this->data['my_groups'] = $my_groups['rst'];
        }else{
            //$mygame = Game_GameModel::getGameByUdid();
        }

        $this->data['mygame'] = $mygame;
        
        $main = Article_ArticleModel::getHotArticle(1);

        $regame = $main[0]['rst'];
        if(!empty($mygame) && is_array($regame)){
            foreach ($regame as $k => $g) {
                foreach ($mygame as $mg) {
                    if($g['gid'] == $mg['gid']){
                        $regame[$k]['hasAdd'] = 1;
                        break;
                    }
                }
            }
        }

        $this->data['regame'] = $regame;
        $this->data['list'] = $main[1]['rst'];

        $hot24 = Article_ArticleModel::hot24();

        $this->data['hot24'] = $hot24;
        $this->data['from'] = 1; // 1首页  2小组
        $this->assign();
        return $this->end();
    }

    public function gameAction(){
        $this->tpl = 'index.phtml';

        $gid = (int)Comm_Context::param('gid');
        $type = (int)Comm_Context::param('type',0);
        $this->data['g_name'] = Comm_Context::param('g_name');

        //如果游戏id为空，跳回主页
        if(empty($gid)){
            $this->redirect('/');
        }
        $this->data['mygame'] = array();
        $this->data['my_groups'] = array();
        //如果用户登录
        if($this->data['isLogin']){
            //获取用户添加的游戏
            $this->data['mygame'] = Game_GameModel::getGameByUid(Judge::getUid());
            //我加入的小组
            $my_groups = Group_GroupModel::getUserGroups(Judge::getUid(), 0);
            $this->data['my_groups'] = $my_groups['rst'];
        }else{
            $this->data['mygame'] = Game_GameModel::getGameByUdid();
        }
        
        $hot24 = Article_ArticleModel::hot24();

        $this->data['hot24'] = $hot24;
        $this->data['gid'] = $gid;
        $this->data['type'] = $type;
        $this->data['list_type'] = Comm_Config::getPhpConf('articlelisttype');
        $this->data['list'] = Article_ArticleModel::getGameHotArticle($gid,$type);
        $this->data['from'] = 1; // 1首页  2小组
        $this->data['head_title'] = sprintf($this->format_title, $this->data['g_name'].' - ', '');
        $this->assign();
        return $this->end();
    }
}