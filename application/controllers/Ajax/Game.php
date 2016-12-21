<?php
/**
 * @name GameController
 * @desc 默认控制器
 */

class Ajax_GameController extends AbstractController {

    /**
     * 默认动作
     */

    public function allAction() {
        $this->tpl = 'common/game_all.phtml';

        $allGame = Game_GameModel::getAllGamesByUid((int)Judge::getUid());
        $this->data['allGame'] = $allGame['rst'];

        $this->data['statusCode'] = 0;
        $this->data['html'] = $this->assign(true);

        $this->jsonResult();
        return $this->end();
    }
}