/**
 * Created by shaohua5 on 16/10/27.
 */
$(function () {
    //添加或删除小组
    $(document).on('click','.groups .like-list a b,.groups .like-list a img, .mygroup a b',function(){
        var _this = $(this);
        if(!sso.isLogin()){
            sso.login();
            return false;
        }

        var g_g_id = _this.parent('a').children('b').data('ggid') + 0;   //小组id
        var name = _this.parent('a').children('b').data('name');         //小组名字
        var isAdd = _this.parent('a').children('b').hasClass('add');     //是否加入该小组

        var url = isAdd ? '/ajax_group/addGroup' : '/ajax_group/deleteGroup';

        $.post(url,{'g_g_id':g_g_id},function(re){
            if(0 == re.statusCode){  //添加或删除小组成功
                var n = _this.parent().clone();
                if(isAdd){
                    //左侧菜单栏添加
                    $('.subNavBox .navContent_group').append('<li id="left_nav_game_'+g_g_id+'"><a href="/group?g_g_id='+g_g_id+'&g_g_name='+name+'"><b></b>'+name+'</a></li>');

                    //我的小组添加
                    $('.mygroup .like-list').append(n);

                    //该小组去掉添加class
                    n.find('b').removeClass('add');
                    n.find('b').addClass('del');

                }else{
                    //左侧菜单栏
                    $('#left_nav_game_'+g_g_id).remove();

                    //该小组去掉class
                    n.find('b').addClass('add');
                    n.find('b').removeClass('del');

                    var web = n.find('b').hasClass('web');
                    if(web){
                        //网游小组
                        $('.group_1 .like-list').append(n);
                    }else{
                        //手游小组
                        $('.group_2 .like-list').append(n);
                    }

                    //一旦我的小组中没有元素，则收起来
                    var aNum = $('.mygroup .like-list').children('a').length - 1;
                    if(aNum == 0){
                        $('.mygroup .like-list').css('min-height','30px');
                    }
                }
                _this.parent().remove();
            }else{
                popup(re.message);
            }
        });

    });
    //网游小组加载更多
    var type_w = 1;   //1代表网游小组
    var page = 2;
    var num = 20;
    $(document).on('click','.page-botton .show_more_webGroup',function(){
        var _this = $(this);
        $.post('/ajax_group/getMoreWebGroup',{'type':type_w,'page':page,'num':num},function(re){
            var rs = re.web_group;
            if(rs.length != 0){
                var html = '';
                for(var i=0; i<rs.length;i++){
                    var isdel = 1 == rs[i]['hasAdd'] ? 'del' : 'add';
                    html += '<a href="javascript:;">' +
                        '<img src="'+rs[i]['url']+'"/>' +
                        '<span>'+rs[i]['name']+'</span>' +
                        '<b class="'+isdel+' web" data-gid="'+rs[i]['gid']+'" data-name="'+rs[i]['name']+'" data-ggid="'+rs[i]['g_g_id']+'">' +
                        '</b>' +
                        '</a>';
                }
                $('.group_1 .like-list').append(html);
            }else{
                _this.text('暂时没有更多数据');
            }
        });
        page++;
    });
    //手游小组加载更多
    var type_m = 2 ;//2代表手游小组
    $(document).on('click','.page-botton .show_more_mobileGroup',function(){
        var _this = $(this);
        $.post('/ajax_group/getMoreMobileGroup',{'type':type_m,'page':page,'num':num},function(re){
            var rs = re.mobile_group;
            if(rs.length != 0){
                var html = '';
                for(var j=0 ; j<rs.length; j++){
                    var isdel = 1==rs[j]['hasAdd'] ? "del" : "add";
                    html += '<a href="javascript:;">' +
                        '<img src="'+rs[j]['url']+'"/>' +
                        '<span>'+rs[j]['name']+'</span>' +
                        '<b class="'+isdel+' mobile" data-gid="'+rs[j]['gid']+'" data-name="'+rs[j]['name']+'" data-ggid="'+rs[j]['g_g_id']+'">' +
                        '</b>' +
                        '</a>';
                }
                $('.group_2 .like-list').append(html);
            }else{
                _this.text('暂时没有更多数据');
            }
        });
        page++;
    });

    var isScroll = false;
    //加入小组 来自小组profile页
    $('.add_group_profile').click(function () {
        if(!sso.isLogin()){
            sso.panel('login');
            sso.key13();
            return false;
        }
        var thisObj = $(this);
        var g_g_id = tmpObj.g_g_id //小组id
        $.ajax({
            url  : '/ajax_group/addgroup',
            type : 'POST',
            dataType : 'json',
            data : {'g_g_id':g_g_id},
            success : function (dt) {
                if(dt.statusCode == 0){
                    $('.blog_remove').data('is', 1);
                    thisObj.text('√已加入');
                    thisObj.hide(500);
                }
            },
            error : function () {
            }
        });
    });

    //全部小组列表  加入小组
    $('.tan_all_group, .box1').on('click', '.item, .add', function () {
        if(!sso.isLogin()){
            $('.tan_all_group').fadeOut(800);
            sso.panel('login');
            sso.key13();
            return false;
        }
        var that = $(this);
        var g_g_id = that.parent('a').children('b').data('ggid'); //小组id
        $.ajax({
            url  : '/ajax_group/addgroup',
            type : 'POST',
            dataType : 'json',
            data : {'g_g_id':g_g_id},
            success : function (dt) {
                if(dt.statusCode == 0){
                    var g_g_name = that.siblings('span').text();
                    var str = '<li id="left_nav_game_'+ g_g_id +'"><a href="/group?g_g_id='+ g_g_id +'&g_g_name='+g_g_name+'"><b></b>'+ g_g_name +'</a></li>';
                    $('.subNavBox ul').eq(1).append(str);
                    that.parent('a').children('b').removeClass('add');
                    that.parent('a').children('b').addClass('del');
                }
            },
            error : function () {
            }
        });
    });

    //全部小组列表  删除小组
    $('.tan_all_group, .box1').on('click', '.del', function () {
        if(!sso.isLogin()){
            $('.tan_all_group').fadeOut(800);
            sso.panel('login');
            sso.key13();
            return false;
        }
        var that = $(this);
        var g_g_id = that.parent('a').children('b').data('ggid'); //小组id
        $.ajax({
            url  : '/ajax_group/deletegroup',
            type : 'POST',
            dataType : 'json',
            data : {'g_g_id':g_g_id},
            success : function (dt) {
                if(dt.statusCode == 0){
                    var id = '#left_nav_game_' + g_g_id;
                    $(id).remove();
                    that.parent('a').children('b').removeClass('del');
                    that.parent('a').children('b').addClass('add');
                }
            },
            error : function () {
            }
        });
    });

    //feed流切换
    $('.feed_type_btn').click(function () {
        if($(this).attr('data-type') == 0){
            tmpObj.feed_type = 0;
        }else if($(this).attr('data-type') == 1){
            tmpObj.feed_type = 1;
        }
        var notice = $('.notice');
        $('.main_blogs').html('');
        $('.main_blogs').append(notice);
        notice.html('加载中...');
        notice.css({"display":"block"});
        tmpObj.page = 1;
        $.ajax({
            url  : '/ajax_group/getMoreBlogFeed',
            type : 'GET',
            dataType : 'json',
            data : {'g_g_id':tmpObj.g_g_id, 'page':tmpObj.page, 'pageSize':tmpObj.pageSize, 'feed_type':tmpObj.feed_type},
            success : function (dt) {
                if(dt.html){
                    $('.main_blogs').append(dt.html);
                    tmpObj.pageCount = dt.pageCount;
                    //notice.html('为您推荐了'+dt.total+'篇帖子');
                }else{
                    notice.html('暂无更多信息');
                }
                setTimeout(function(){
                    notice.slideUp('fast');
                },1000);
            },
            error : function () {
            }
        });

    });

    //加载更多group feed流
    $(window).on('scroll', function () {
        if($('.main_blogs').length < 1 || isScroll == true){
            return false;
        }
        tmpObj.page++;
        if(tmpObj.page >= tmpObj.pageCount){
            //return false;
        }
        var scrollT = $(this).scrollTop();
        var height = $(this).height();
        var scrollH = $(document).height();
        if(scrollH - scrollT - height <= 10){
            isScroll = true;
            var _notice = $('.notice');
            var _clone = _notice.clone();
            _clone.html('加载中...');
            _clone.css({"display":"block"});
            $('.main_blogs').append(_clone);
            $.ajax({
                url  : '/ajax_group/getMoreBlogFeed',
                type : 'GET',
                dataType : 'json',
                data : {'g_g_id':tmpObj.g_g_id, 'page':tmpObj.page, 'pageSize':tmpObj.pageSize, 'feed_type':tmpObj.feed_type},
                success : function (dt) {
                    if(dt.html){
                        //_clone.html('为您推荐了'+dt.total+'篇帖子');
                        isScroll = false;
                        $('.main_blogs').append(dt.html);
                    }else{
                        _clone.html('暂无更多信息');
                        setTimeout(function(){
                            isScroll = false;
                        },3000);
                    }
                    setTimeout(function(){
                        _clone.slideUp('fast', function () {
                            _clone.remove();
                        });
                    },1000);
                },
                error : function () {
                    isScroll = false;
                    _clone.slideUp('fast', function () {
                        _clone.remove();
                    });
                    popup('网络故障,请重试~');
                }
            });
        }
    });



    //加载更多group feed流
    /*
    $('.main_blogs').on('click', '.blog_showmore', function () {
        tmpObj.page++;
        var _that = $(this);
        var _notice = $('.notice');
        $(document).scrollTop(340);
        _notice.html('推荐中...');
        _notice.css({"display":"block"});
        $.ajax({
            url  : '/ajax_group/getMoreBlogFeed',
            type : 'GET',
            dataType : 'json',
            data : {'g_g_id':tmpObj.g_g_id, 'page':tmpObj.page, 'pageSize':tmpObj.pageSize, 'feed_type':tmpObj.feed_type},
            success : function (dt) {
                if(dt.html){
                    _notice.after(_that.clone());
                    _that.remove();
                    _notice.after(dt.html);
                    _notice.html('为您推荐了'+dt.total+'篇帖子');
                }else{
                    _notice.html('暂无更多信息');
                }
                setTimeout(function(){
                    _notice.slideUp('fast');
                },1000);
            }
        });
    });
    */

    function view(str){
        str = str.replace(/\n/g,'<br/>');
        str = str.replace(/\[\/表情([0-9]*)\]/g,'<img src="/static/face/$1.gif" border="0" />');
        return str;
    }

    //发表帖子
    $('.blog_post_btn').click(function () {
        if(!sso.isLogin()){
            sso.panel('login');
            sso.key13();
            return false;
        }
        var title = $('.blog_title').val().trim(); //标题
        if(title.length == 0){
            $('.blog_title').focus();
            return false;
        }
        var content = (window.editor.html()).trim(); //正文
        if(content.length == 0){
            $('.blog_content').focus();
            return false;
        }
        content = view(content);
        var imgStr = '';  //图片
        if($('.cpic em').size() > 0){
            var imgs = [];
            $('.cpic em').each(function (i) {
                imgs.push(JSON.parse(infos[$(this).children('img').attr('data-hash')]));
            })
            imgStr = JSON.stringify(imgs);
        }

        $.ajax({
            url  : '/ajax_blog/post',
            type : 'POST',
            dataType : 'json',
            data : {'g_g_id':tmpObj.g_g_id, 'title':title, 'content':content, 'imgs':imgStr},
            success : function (dt) {
                if(dt.statusCode == 0){
                    $('.blog_post_btn').val('√已发布');
                    $('.blog_post_btn').unbind('click');
                    $('#select_img').unbind('click');
                    $('#face2').unbind('click');
                    setTimeout(function () {
                        window.location.href = '/blog/detail?bid='+ dt.bid + 'g_g_name=' + tmpObj.g_g_name;
                    }, 1000);
                }else{
                    popup('网络故障,请重试~');
                }
            },
            error : function () {
                popup('网络故障,请重试~');
            }
        });

    });

    //用户profile页 tab切换
    $('.tab_profile a').click(function(){
        var index = $(this).index();
        $('.tab1').hide();
        $('.tab1').eq(index).show();
        $('.tab_profile a').removeClass('on');
        $('.tab_profile a').eq(index).addClass('on');
        $('.profile_show_more').text('查看更多');
        //数据加载
        var obj = $('.tab1').eq(index).children('div');
        obj.html('');
        tmpObj.index = index;
        tmpObj.page = 1;
        switch (index) {
            case 0 : proComment(obj); //评论
                break;
            case 1 : proGroup(obj); //小组
                break;
            case 2 : proBlog(obj); //帖子
                break;
        }
    });
    //用户profile页 加载更多
    $('.profile_show_more').click(function () {
        if(tmpObj.page >= tmpObj.pageCount){
            $(this).text('暂无数据');
            return;
        }
        tmpObj.page++;
        var obj = $('.tab1').eq(tmpObj.index).children('div');
        switch (tmpObj.index) {
            case 0 :
                proComment(obj); //评论
                break;
            case 1 :
                proGroup(obj); //小组
                break;
            case 2 :
                proBlog(obj); //帖子
        }

    });
    //profile评论
    function proComment(obj) {
        $.ajax({
            url  : '/ajax_user/proComment',
            type : 'GET',
            dataType : 'json',
            data : {'uid':tmpObj.vid, 'page':tmpObj.page, 'pageSize':tmpObj.pageSize},
            success : function (dt) {
                if(dt.statusCode == 0){
                    obj.append(dt.html);
                    tmpObj.pageCount = dt.page_count;
                }else{
                    popup('网络故障,请重试~');
                }
            },
            error : function () {
                popup('网络故障,请重试~');
            }
        });
    }
    //profile小组
    function proGroup(obj) {
        $.ajax({
            url  : '/ajax_user/proGroup',
            type : 'GET',
            dataType : 'json',
            data : {'uid':tmpObj.vid, 'page':tmpObj.page, 'pageSize':tmpObj.pageSize},
            success : function (dt) {
                if(dt.statusCode == 0){
                    obj.append(dt.html);
                    tmpObj.pageCount = dt.page_count;
                }else{
                    popup('网络故障,请重试~');
                }
            },
            error : function () {
                popup('网络故障,请重试~');
            }
        });
    }
    //profile帖子
    function proBlog(obj) {
        $.ajax({
            url  : '/ajax_user/proBlog',
            type : 'GET',
            dataType : 'json',
            data : {'uid':tmpObj.vid, 'page':tmpObj.page, 'pageSize':tmpObj.pageSize},
            success : function (dt) {
                if(dt.statusCode == 0){
                    obj.append(dt.html);
                    tmpObj.pageCount = dt.page_count;
                }else{
                    popup('网络故障,请重试~');
                }
            },
            error : function () {
                popup('网络故障,请重试~');
            }
        });
    }

    //推荐小组换一换
    $('.more_group').click(function () {
        $.ajax({
            url  : '/ajax_group/recommendGroup',
            type : 'GET',
            dataType : 'json',
            data : {},
            success : function (dt) {
                if(dt.statusCode == 0){
                    $('.recommend_group').html(dt.html)
                }else{
                    popup('网络故障,请重试~');
                }
            },
            error : function () {
                popup('网络故障,请重试~');
            }
        });
    });

    //热门贴feed_more 滚动加载
    $(window).on('scroll', function () {
        if($('.main_content').length < 1 || isScroll == true){
            return false;
        }
        //显示 、 隐藏未读信息条
        var _top = $(document).scrollTop();
        if(_top > 380){
            $('.hasUnreadRecommend2').css({"display":"block"});
        }else{
            $('.hasUnreadRecommend2').css({"display":"none"});
        }

        var scrollT = $(this).scrollTop();
        var height = $(this).height();
        var scrollH = $(document).height();
        if(scrollH - scrollT - height <= 10){
            isScroll = true;
            var _notice = $('.notice');
            var _clone = _notice.clone();
            _clone.html('推荐中...');
            _clone.css({"display":"block"});
            $('.main_content').append(_clone);
            $.ajax({
                url  : '/ajax_group/moreRecommendBlogs',
                type : 'GET',
                dataType : 'json',
                data : {},
                success : function (dt) {
                    isScroll = false;
                    if(dt.statusCode == 0){
                        $('.main_content').append(dt.html);
                        _clone.html('为您推荐了'+dt.total+'篇帖子');
                    }else{
                        _clone.html('暂无更多信息');
                    }
                    setTimeout(function(){
                        _clone.slideUp('fast', function () {
                            _clone.remove();
                        });
                    },1000);
                },
                error : function () {
                    isScroll = false;
                    _clone.slideUp('fast', function () {
                        _clone.remove();
                    });
                    popup('网络故障,请重试~');
                }
            });
        }
    });

    //热门贴feed_more
    /*
    $('.main_content').on('click', '.refresh', function () {
        var _that = $(this);
        var _notice = $('.notice');
        $(document).scrollTop(330);
        _notice.html('推荐中...');
        _notice.css({"display":"block"});
        $.ajax({
            url  : '/ajax_group/moreRecommendBlogs',
            type : 'GET',
            dataType : 'json',
            data : {},
            success : function (dt) {
                if(dt.statusCode == 0){
                    _notice.after(_that.clone());
                    _that.remove();
                    _notice.after(dt.html);
                    _notice.html('为您推荐了'+dt.total+'篇帖子');
                }else{
                    _notice.html('暂无更多信息');
                }
                setTimeout(function(){
                    _notice.slideUp('fast');
                },1000);
            },
            error : function () {
                popup('网络故障,请重试~');
            }
        });
    });
    */

    //加载更多帖子评论
    $('.blog_comment_showmore').click(function () {
        tmpObj.page++;
        $.ajax({
            url  : '/ajax_blog/getMoreComment',
            type : 'GET',
            dataType : 'json',
            data : {'bid':tmpObj.bid, 'page':tmpObj.page, 'pageSize':tmpObj.pageSize, 'floor':tmpObj.floor},
            success : function (dt) {
                if(dt.statusCode == 0){
                    $('.blog_comment_list').append(dt.html);
                }else{
                    popup('网络故障,请重试~');
                }
            },
            error : function () {
                $('.blog_comment_showmore').text('暂无数据');
            }
        });
    });

    //blog主评论的回复
    $('.blog_comment_list').on('click', '.blog_reply_btn', function () {
        if(!sso.isLogin()){
            sso.panel('login');
            sso.key13();
            return false;
        }
        var id = $(this).parent('form').siblings('div').children('.hf-icon').attr('data-id');
        var content = $(this).siblings('textarea').val().trim();
        content = view(content);
        if(content.length < 5 || content.length > 1000){
            popup('字数限制在5~1000');
            $(this).siblings('textarea').focus();
            return false;
        }
        var touid = $(this).attr('data-touid');
        var commentuid = $(this).attr('data-commentuid');
        var floor = $(this).attr('data-floor');
        var toname = $(this).attr('data-toname');
        var that = $(this);
        $.ajax({
            url  : '/ajax_blog/commentReply',
            type : 'POST',
            dataType : 'json',
            data : {'pid':id, 'content':content, 'touid':touid, 'commentuid':commentuid, 'floor':floor, 'toname':toname},
            success : function (dt) {
                if(dt.statusCode == 0){
                    var flag = that.attr('data-flag');
                    if(flag == 0){ //评论的回复
                        that.parent('form').siblings('.huf-box').children('.reply_list').append(dt.html);
                    }else if(flag == 1){ //回复的回复
                        that.parent('form').parent('div').parent('.reply_list').append(dt.html);
                    }
                    that.parent('form').hide();
                }
            },
            error : function () {
            }
        });
    });

    //回帖
    $('.post_blog_comment').click(function () {
        if(!sso.isLogin()){
            sso.panel('login');
            sso.key13();
            return false;
        }
        var content = $('#content3').val().trim(); //正文
        if(content.length < 5 || content.length > 1000){
            $(this).siblings('textarea').focus();
            return false;
        }
        content = view(content);
        var imgStr = '';  //图片
        if($('.cpic em').size() > 0){
            var imgs = [];
            $('.cpic em').each(function (i) {
                imgs.push(JSON.parse(infos[$(this).children('img').attr('data-hash')]));
            })
            imgStr = JSON.stringify(imgs);
        }

        $.ajax({
            url  : '/ajax_blog/postComment',
            type : 'POST',
            dataType : 'json',
            data : {'bid':tmpObj.bid, 'content':content, 'imgs':imgStr},
            success : function (dt) {
                if(dt.statusCode == 0){
                    $('.post_blog_comment').val('√已发布');
                    setTimeout(function () {
                        $('#content3').val('');
                        $('.cpic em').html('');
                        $('.post_blog_comment').val('发表');
                    }, 2000);
                }else{
                    popup('网络故障,请重试~');
                }
            },
            error : function () {
                popup('网络故障,请重试~');
            }
        });

    });

    //对帖子评论和回复 点赞
    $('.blog_comment_list').on('click', '.action_zan', function () {
        if(!sso.isLogin()){
            sso.panel('login');
            sso.key13();
            return false;
        }
        if($(this).attr('data-flag') == 1){
            popup('不可重复点赞~');
            return;
        }
        var id = $(this).siblings('.hf-icon').attr('data-id');
        var that = $(this);
        $.ajax({
            url  : '/ajax_comment/doFavor',
            type : 'POST',
            dataType : 'json',
            data : {'c_id':id},
            success : function (dt) {
                if(dt.statusCode == 0){
                    that.text(parseInt(that.text()) + 1);
                    that.attr('data-flag', 1);
                }else{
                    popup('网络故障,请重试~');
                }
            },
            error : function () {
                popup('网络故障,请重试~');
            }
        });
    });

    //个人profile页的评论  (文章 + 帖子)
    $('.user_comment_list').on('click', '.comment_btn', function () {
        if(!sso.isLogin()){
            sso.panel('login');
            sso.key13();
            return false;
        }
        var content = $(this).siblings('textarea').val().trim();
        if(content.length < 5 || content.length > 1000){
            $(this).siblings('textarea').focus();
            return false;
        }
        content = view(content);
        var type = $(this).attr('data-type');
        var id = $(this).attr('data-id');
        var that = $(this);
        $.ajax({
            url  : '/ajax_user/proPostComment',
            type : 'POST',
            dataType : 'json',
            data : {'id':id, 'content':content, 'type':type},
            success : function (dt) {
                if(dt.statusCode == 0){
                    that.val('√已发布');
                    setTimeout(function () {
                        that.val('发表');
                        that.siblings('textarea').val('');
                        that.parent('form').hide();
                    }, 2000);
                }else{
                    popup('网络故障,请重试~');
                }
            },
            error : function () {
                popup('网络故障,请重试~');
            }
        });
    });

    //回帖的回复 查看更多
    $('.blog_comment_list').on('click', '.blog_comment_reply_more', function () {
        var that = $(this);
        var id = that.data('id');
        var page = parseInt(that.data('page'));
        var floor = that.data('floor');
        var comment_uid = that.data('commentuid');
        that.text('查看更多');
        $.ajax({
            url  : '/ajax_blog/getMoreCommentReply',
            type : 'GET',
            dataType : 'json',
            data : {'id':id, 'page':page, 'floor':floor, 'comment_uid':comment_uid},
            success : function (dt) {
                if(dt.statusCode == 0){
                    if(dt.html){
                        if(page <= 1){
                            that.siblings('.reply_list').html('');
                        }
                        that.siblings('.reply_list').append(dt.html);
                        page++;
                        that.data('page', page);
                    }else{
                        that.text('');
                    }
                }else{
                    popup('网络故障,请重试~');
                }
            },
            error : function () {
                popup('网络故障,请重试~');
            }
        });
    });

    //个人消息 加载更多
    $('.message_more').click(function () {
        tmpObj.page++;
        $.ajax({
            url  : '/ajax_user/moreMessage',
            type : 'GET',
            dataType : 'json',
            data : {'feed_type':tmpObj.feedType, 'page':tmpObj.page, 'page_size':tmpObj.pageSize},
            success : function (dt) {
                if(dt.statusCode == 0){
                    if(dt.html != ''){
                        $('.message_list').append(dt.html);
                    }else{
                        $('.message_more').hide();
                    }
                }else{
                    popup('网络故障,请重试~');
                }
            },
            error : function () {
                popup('网络故障,请重试~');
            }
        });
    });

    //删除个人消息
    $('.message_list').on('click', '.message_del', function () {
        var id = $(this).data('id');
        var that = $(this);
        $.ajax({
            url  : '/ajax_user/deleteMessage',
            type : 'GET',
            dataType : 'json',
            data : {'id':id},
            success : function (dt) {
                if(dt.statusCode == 0){
                    that.parents('.pl-hf').remove();
                }else{
                    popup('网络故障,请重试~');
                }
            },
            error : function () {
                popup('网络故障,请重试~');
            }
        });
    });

    //个人消息页  回复消息
    $('.message_list').on('click', '.msg_btn', function () {
        var content = $(this).parent('div').siblings('textarea').val().trim();
        content = view(content);
        if(content.length < 5 || content.length > 1000){
            alert('字数限制在5~1000');
            $(this).parent('div').siblings('textarea').focus();
            return false;
        }
        var id = $(this).data('id');
        var that = $(this);
        $.ajax({
            url  : '/ajax_user/msgReply',
            type : 'POST',
            dataType : 'json',
            data : {'pid':id, 'content':content},
            success : function (dt) {
                if(dt.statusCode == 0){
                    that.val('√已发布');
                    that.unbind('click');
                    setTimeout(function () {
                        that.parent('div').parent('form').hide();
                        that.val('发表');
                        that.parent('div').siblings('textarea').val('')
                    }, 200);
                }
            },
            error : function () {
            }
        });
    });

    //profile页点赞
    $('.pro_comment_list').on('click', '.action_zan', function () {
        if(!sso.isLogin()){
            sso.panel('login');
            sso.key13();
            return false;
        }
        if($(this).attr('data-flag') == 1){
            popup('不可重复点赞~');
            return;
        }
        var id = $(this).siblings('.hf-icon').attr('data-id');
        var that = $(this);
        $.ajax({
            url  : '/ajax_comment/doFavor',
            type : 'POST',
            dataType : 'json',
            data : {'c_id':id},
            success : function (dt) {
                if(dt.statusCode == 0){
                    that.attr('data-flag', 1);
                    that.text('已赞');
                    that.css({'color':'red'});
                }else{
                    popup('网络故障,请重试~');
                }
            },
            error : function () {
                popup('网络故障,请重试~');
            }
        });
    });

    //签到
    $('.group_sign').click(function () {
        if(!sso.isLogin()){
            sso.panel('login');
            sso.key13();
            return false;
        }
        var is = $(this).data('is');
        if(is != 1){
            popup('还未加入小组');
            return;
        }
        var that = $(this);
        $.ajax({
            url  : '/ajax_user/userSign',
            type : 'POST',
            dataType : 'json',
            data : {'ggid':tmpObj.g_g_id},
            success : function (dt) {
                if(dt.statusCode == 0){
                    that.text('已签到');
                    that.unbind('click');
                }else{
                    popup('网络故障,请重试~');
                }
            },
            error : function () {
                popup('网络故障,请重试~');
            }
        });
    });

    //删除帖子
    $('.blog_remove').click(function () {
        if(!sso.isLogin()){
            sso.panel('login');
            sso.key13();
            return false;
        }
        var that = $(this);
        var uid = that.data('uid');
        $.ajax({
            url  : '/ajax_blog/blogDelete',
            type : 'get',
            dataType : 'json',
            data : {'bid':tmpObj.bid, 'uid':uid},
            success : function (dt) {
                if(dt.statusCode == 0){
                    that.text('已删除');
                    that.css({'color':'red'});
                    that.unbind('click');
                    window.opener.location.reload();
                    setTimeout(function () {
                        window.close();
                    }, 500);

                }else{
                    popup('网络故障,请重试~');
                }
            },
            error : function () {
                popup('网络故障,请重试~');
            }
        });
    });


    var isLoad = false;
    /*
    //全部小组列表 弹窗
    $('.all_group_more').click(function () {
        $('.tan_all_group').fadeIn(800);
        isLoad = true;
        more_group();
    });
    */
    function more_group() {
        tmpObj.page++;
        $.ajax({
            url  : '/ajax_group/getAllGroup',
            type : 'get',
            dataType : 'json',
            data : {'page':tmpObj.page},
            success : function (dt) {
                if(dt.statusCode == 0){
                    $('.tan-txt .like-list').append(dt.html);
                    tmpObj.pageCount = dt.pageCount;
                    isLoad = false;
                }
            }
        });
    }

    $('.tan_all_group').on('click', '.close', function () {
        $('.tan_all_group').fadeOut(800);
    });

    //滚动加载更多小组

    $('.tan-txt').on('scroll', function () {
        if(tmpObj.page >= tmpObj.pageCount){
            return false;
        }
        var scrollH = $(this)[0].clientHeight; //滚动条总高度
        var scrollT = $(this)[0].scrollTop; //滚动条当前高度
        var contentH = $('.like-list').height() ; //文本高度
        if(contentH - scrollH - scrollT<= 50){
            if(isLoad == true){
                return false;
            }
            isLoad == true;
            more_group();
        }
    });

    //检查是否有未读帖子
    if($('.main_content').length > 0) {
        setInterval(function () {
            if ($('.hasUnreadRecommend1').is(":visible") == true || $('.hasUnreadRecommend2').is(":visible") == true) {
                return false;
            }
            $.ajax({
                url: '/ajax_user/hasUnreadRecommend',
                type: 'get',
                dataType: 'json',
                data: {'type': 0},
                success: function (dt) {
                    if (dt.hasUnreadRecommend == 1) {
                        hasUnreadRecommend('您有未读新闻,请点击查看');
                    }
                }
            });
        }, 5000);
    }

    function hasUnreadRecommend(msg) {
        var node1 = $('<a class="hasUnreadRecommend1" style="z-index: 10000;"></a>');
        var node2 = $('<a class="hasUnreadRecommend2" style="z-index: 10000;"></a>');
        msg = '<a href="" style="color:#ff4d4d; font-size:14px">' + msg + '</a>' + '<h2 style="cursor: pointer; float: right; color: white; margin-right: 20px">X</h2>';
        node1.html(msg).css({"display":"block"});
        $('.main_content').prepend(node1);
        var _left = $('.main_content').offset().left;
        node2.html(msg).css({"display":"none", "position":"fixed", "top":0, "left":_left});
        $("body").append(node2);
        var _top = $(document).scrollTop();
        if(_top > 380){
            node2.css({"display":"block"});
        }
    }

    $(document).on('click', '.hasUnreadRecommend1,.hasUnreadRecommend2 h2', function () {
        setTimeout(function () {
            $('.hasUnreadRecommend1').slideUp('fast', function () {
                $('.hasUnreadRecommend1').remove();
            });
        }, 300);
        setTimeout(function () {
            $('.hasUnreadRecommend2').slideUp('fast', function () {
                $('.hasUnreadRecommend2').remove();
            });
        }, 300);
    })
});

