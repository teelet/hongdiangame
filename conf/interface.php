<?php
/**
 * 接口列表
 * User: shaohua5
 * Date: 16/9/18
 * Time: 上午11:14
 */
return array(
    //获取用户游戏列表
    'game_by_user'  => ['api'=>'/game/game_by_user.json', 'method'=>'post/get', 'mustpar'=>''],
    //获取用户游戏列表
    'game_by_uid'  => ['api'=>'/game/game_by_uid.json', 'method'=>'post/get', 'mustpar'=>'uid', 'needsct'=>true],
    //未选择游戏时首页资讯流
    'hot_article'	=>	['api'=>'/article/hot_article.json','method'=>'post/get','mustpar'=>'is_first'],
    //按游戏获取热门资讯
    'game_hot_article'   =>  ['api'=>'/article/article_list.json','method'=>'post/get','mustpar'=>'gid,type'],
    //热门视频
    'blog_hot_video' => ['api'=>'/blog/blog_hot_video.json','method'=>'post/get','mustpar'=>''],
    //文章详情页
    'article_detail'   =>  ['api'=>'/article/detail.json','method'=>'post/get','mustpar'=>'aid'],
    //文章举报
    'article_report'    => ['api'=>'/article/report.json', 'method'=>'post', 'mustpar'=>'uid,aid', 'needsct'=>true],
    //相关文章及广告
    'article_related'   =>  ['api'=>'/article/news_related.json','method'=>'post/get','mustpar'=>'aid'],
    //临时用户登录
    'login'			=>	['api'=>'/user/login.json','method'=>'post','mustpar'=>'phone,password'],
    //临时用户注册
    'regist'		=>	['api'=>'/user/regist.json','method'=>'post','mustpar'=>'phone,password,nickname'],
    //临时注册短信
    'registcode'	=>	['api'=>'/user/registcode.json','method'=>'post','mustpar'=>'uid,phone'],
    //账号激活
    'checkcode'    =>  ['api'=>'/user/checkcode.json','method'=>'post','mustpar'=>'uid,code'],
    //用户公开信息
    'user_info'    =>  ['api'=>'/user/info.json','method'=>'post/get','mustpar'=>'uid'],
    //依据手机号获取用户id
    'get_uid_by_phone'    =>  ['api'=>'/user/get_uid_by_phone.json','method'=>'post','mustpar'=>'phone'],
    //重置密码
    'reset_you' => ['api'=>'/user/reset_you.json','method'=>'post','mustpar'=>'uid,password,code'],
    //获取全部游戏列表
    'game_all_type' => ['api'=>'/game/all_type.json', 'method'=>'post/get'],
    //web专用获取全部游戏列表
    'game_all_type_uid' => ['api'=>'/game/all_type_uid.json', 'method'=>'post/get'],
    //小组首页推荐流
    'group_recommend' => ['api'=>'/group/recommend.json', 'method'=>'post/get'],
    //用户加入、常去的小组
    'user_group' => ['api'=>'/group/my_group.json', 'method'=>'post/get', 'mustpar'=>'uid,type', 'needsct'=>true],
    //获取帖子详情
    'blog_detail' => ['api'=>'/blog/detail.json', 'method'=>'post/get', 'mustpar'=>'bid'],
    //获取推荐的小组列表
    'rec_group_list' => ['api'=>'/group/rec_group.json', 'method'=>'post/get'],
    //获取活跃用户列表
    'act_user_list'  => ['api'=>'/user/active.json', 'method'=>'post/get'],
    //根据小组id获取帖子列表
    'group_info_and_blogs' => ['api'=>'/group/blog.json', 'method'=>'post/get', 'mustpar'=>'type,feed_type'],
    //帖子相关阅读
    'blog_related_blogs' => ['api'=>'/blog/blog_related.json', 'method'=>'post/get', 'mustpar'=>'bid'],
    //文章、帖子的评论列表
    'comment_list' => ['api'=>'/comment/list_by_aid.json', 'method'=>'post/get', 'mustpar'=>'aid'],
    //文章、帖子添加评论
    'comment_add' => ['api'=>'/comment/add.json', 'method'=>'post', 'mustpar'=>'uid,aid,ctype,content,source', 'needsct'=>true],
    //获取小组成员列表
    'group_user_list' => ['api'=>'/group/member.json', 'method'=>'post/get', 'mustpar'=>'ggid'],
    //用户添加游戏
    'user_add_game' => ['api'=>'/user/add_game.json', 'method'=>'post', 'mustpar'=>'gid'],
    //用户添加游戏
    'uid_add_game' => ['api'=>'/user/uid_add_game.json', 'method'=>'post', 'mustpar'=>'uid,gid', 'needsct'=> true],
    //用户删除游戏
    'user_delete_game' => ['api'=>'/user/delete_game.json', 'method'=>'post', 'mustpar'=>'gid'],
    //用户删除游戏
    'uid_delete_game' => ['api'=>'/user/uid_delete_game.json', 'method'=>'post', 'mustpar'=>'uid,gid', 'needsct'=> true],
    //用户删除游戏
    'user_delete_game' => ['api'=>'/user/delete_game.json', 'method'=>'post', 'mustpar'=>'uid,gid', 'needsct'=> true],
    //24小时热门新闻
    'hot24' => ['api'=>'/article/article_rank_day.json', 'method'=>'post/get', 'mustpar'=>''],
    //用户加入小组
    'user_add_group' => ['api'=>'/user/add_group.json', 'method'=>'post', 'mustpar'=>'uid,ggid', 'needsct'=>true],
    //用户退出小组
    'user_delete_group' => ['api'=>'/user/delete_group.json', 'method'=>'post', 'mustpar'=>'uid,ggid', 'needsct'=>true],
    //获取个人签到信息
    'group_user_sign_info' => ['api'=>'/user/is_sign.json', 'method'=>'post/get', 'mustpar'=>'uid,ggid', 'needsct'=>true],
    //获取图片上传token
    'get_img_token' => ['api'=>'/image/imgtoken.json', 'method'=>'post/get', 'mustpar'=>'num', 'needsct'=>true],
    ///发帖
    'post_blog' => ['api'=>'/blog/postblog.json', 'method'=>'post', 'mustpar'=>'uid,g_g_id,source,title,content', 'needsct'=>true],
    //获取用户基本信息
    'get_user_info' => ['api'=>'/user/info.json', 'method'=>'post/get', 'mustpar'=>'other_uid'],
    //用户发出的评论列表
    'user_comment_list' => ['api'=>'/comment/list_by_uid.json', 'method'=>'post/get', 'mustpar'=>'uid'],
    //检查用户是否加入了小组
    'is_in_group' => ['api'=>'/user/in_group.json', 'method'=>'post', 'mustpar'=>'uid,ggid'],
    //他人加入的小组列表
    'user_group_list' => ['api'=>'/group/list_by_uid.json', 'method'=>'get', 'mustpar'=>'uid'],
    //他人发布的帖子列表
    'user_blog_list' => ['api'=>'/blog/list_by_uid.json', 'method'=>'get', 'mustpar'=>'uid'],
    //回复评论
    'comment_reply_add' => ['api'=>'/comment/add_reply.json', 'method'=>'post', 'mustpar'=>'uid,pid,content', 'needsct'=>true],
    //给评论或回复点赞
    'do_favor' => ['api'=>'/comment/favor.json', 'method'=>'post', 'mustpar'=>'uid,c_id', 'needsct'=>true],
    //获取文章评论和回帖的回复列表
    'comment_reply_list' => ['api'=>'/comment/list_reply.json', 'method'=>'post', 'mustpar'=>'cid'],
    //未读消息数
    'msg_unread_num' =>  ['api'=>'/msg/msg/unread_num.json', 'method'=>'post/get', 'mustpar'=>'uid', 'needsct'=>true],
    //个人消息列表
    'get_user_msg' =>  ['api'=>'/msg/web/list.json', 'method'=>'get', 'mustpar'=>'uid,type', 'needsct'=>true],
    //删除个人消息
    'delete_user_msg' => ['api'=>'/msg/del.json', 'method'=>'post', 'mustpar'=>'uid,id', 'needsct'=>true],
    //用户小组签到
    'group_user_sign' => ['api'=>'/user/sign.json', 'method'=>'post', 'mustpar'=>'uid,ggid', 'needsct'=>true],
    //删帖
    'blog_delete' => ['api'=>'/blog/delete_blog.json', 'method'=>'get', 'mustpar'=>'uid,bid', 'needsct'=>true],
    //搜索
    'search' => ['api'=>'/article/search.json', 'method'=>'post', 'mustpar'=>'keywords'],
    //24小时热搜
    'hot_search_list' => ['api'=>'/article/hot_search.json', 'method'=>'get/post'],
    //修改密码
    'modify_you'=>['api'=>'/user/modify_you.json','method'=>'post','mustpar'=>'uid,old_pass,new_pass'],
    //修改头像
    'update_face'=>['api'=>'/user/update_face.json','method'=>'post','mustpar'=>'uid,img','needsct'=>true],
    //获取自己的信息
    'myself'=>['api'=>'/user/myself.json','method'=>'post','mustpar'=>'uid','needsct'=>true],
    //编辑个人资料
    'edit'=>['api'=>'/user/edit.json','method'=>'post','mustpar'=>'uid,nickname,summary,sex,birthday,province,city','needsct'=>true],
    //获取全部小组
    'get_all_group' =>['api'=>'/group/all_group.json','method'=>'get'],
    //检查是否有未读推荐
    'has_unread_recommend' =>['api'=>'/blog/has_unread.json','method'=>'get'],
    //是否有未读资讯推荐
    'has_unread_article' =>['api'=>'/article/has_unread.json','method'=>'get', 'mustpar'=>'type'],
    //根据类型列出小组
    'all_group_by_type' =>['api'=>'/group/all_group_by_type.json','method'=>'get/post','mustpar'=>'type'],
);
