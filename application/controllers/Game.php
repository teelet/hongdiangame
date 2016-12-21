<?php
/**
 * @name IndexController
 * @desc 默认控制器
 */

class GameController extends AbstractController {

    /**
     * 默认动作
     */

    public function indexAction() {
        $this->tpl = 'game.phtml';

        $this->data['mygame'] = array();
        $this->data['my_groups'] = array();
        //如果用户登录
        if($this->data['isLogin']){
            //获取用户添加的游戏
            $mygame = Game_GameModel::getGameByUid(Judge::getUid());
            $this->data['mygame'] = $mygame;
            //我加入的小组
            $my_groups = Group_GroupModel::getUserGroups(Judge::getUid(), 0);
            $this->data['my_groups'] = $my_groups['rst'];
        }
        
        $allGame = Game_GameModel::getAllGamesByUid((int)Judge::getUid());
        $allGame = $allGame['rst'];
        if(is_array($allGame)){
            foreach ($allGame as $key => $gt) {
                foreach ($gt['games'] as $k => $g) {
                    if(1 == $g['hasAdd']){
                        unset($allGame[$key]['games'][$k]);
                    }
                }
            }
        }
        $this->data['allGame'] = $allGame;
       
        //24小时热门资讯
        $hot24 = Article_ArticleModel::hot24();
        $this->data['hot24'] = $hot24;

        //热门视频
        $hotVideo = Blog_BlogModel::getHotVideo();
        $this->data['hotVideo'] = $hotVideo;
        $this->data['from'] = 1;
        $this->assign();
        return $this->end();
    }
}