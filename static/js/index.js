$(function(){
	//滚动方式刷新资讯
	$(document).on('scroll',window,function(){
		if($(document).scrollTop() + $(window).height() + 100 >= $(document).height()) {
			var s_n = $('.scroll-notice');

			if(s_n.is(':visible')){
				return false;
			}

			var gid = parseInt(s_n.data('gid'));
			gid = isNaN(gid) ? 0 : gid;
			var type = parseInt(s_n.data('type'));
			type = isNaN(type) ? 0 : type;
			if(gid > 0){
				var url = '/ajax_article/indexgamemore?type='+type+'&gid='+gid+'&r='+(new Date()).getTime();
			}else{
				var url = '/ajax_article/indexmore?'+'r='+(new Date()).getTime();
			}

			var ns_n = s_n.clone();
			s_n.html('推荐中...');
			s_n.css({"display":"block"});

			$.get(url,function(r){
				if(0 == r.statusCode){
					s_n.after(ns_n);
					s_n.after(r.html);
					if(r.total > 0){
						s_n.html('为您推荐了'+r.total+'篇文章');
					}else{
						s_n.html('暂无更多信息');
					}
					setTimeout(function(){
						s_n.slideUp('fast',function(){
							s_n.remove();
						});
					},3000);
				}
			});
		}
	});

	//滚动页面时，如果未读新闻提醒处于显示状态，则处理其样式
	$(document).on('scroll',window,function(){
		var u_n = $('.unread-notice');

		var gid = parseInt(u_n.data('gid'));
		gid = isNaN(gid) ? 0 : gid;

		if(u_n.is(':visible')){
			var u_n_top = 0 == gid ? 340 : 100;
			if($(document).scrollTop() <= u_n_top){
				u_n.css(
					{
						"position":"static",
					}
				);
			}else{
				u_n.css(
					{
						"position":"fixed",
						"top":0,
						"left":$('.con-c').offset().left,
					}
				);
			}
		}
	})

	$(document).on('click','.unread-notice h2',function(){
		$(this).parent().find('font').html('');
		$(this).parent().slideUp('fast');		
	});

	$(document).on('click','.unread-notice font',function(){
		window.location.reload();
	})

	setInterval(function(){
		var u_n = $('.unread-notice');

		if(u_n.is(':visible')){
			return false;
		}

		var gid = parseInt(u_n.data('gid'));
		gid = isNaN(gid) ? 0 : gid;
		var type = parseInt(u_n.data('type'));
		type = isNaN(type) ? 0 : type;

		var u_n_type = 0 == gid ? 0 : type + 1;
		var u_n_top = 0 == gid ? 340 : 100;

		$.get('/ajax_article/unread',{"type":u_n_type},function(r){
			if(0 == r.statusCode){
				u_n.find('font').html(r.message);
				if($(document).scrollTop() <= u_n_top){
					u_n.css(
						{
							"display":"block",
							"z-index":"9999",
							"position":"static",
						}
					);
				}else{
					u_n.css(
						{
							"display":"block",
							"z-index":"9999",
							"position":"fixed",
							"top":0,
							"left":$('.con-c').offset().left,
						}
					);
				}
			}
		});
		
	},5000);

	//点击方式刷新资讯
	// $(document).on('click','.refresh',function(){
	// 	var _this = $(this);
	// 	if(0 == _this.data('enable')){
	// 		return false;
	// 	}

	// 	var gid = parseInt(_this.data('gid'));
	// 	gid = isNaN(gid) ? 0 : gid;
	// 	var type = parseInt(_this.data('type'));
	// 	type = isNaN(type) ? 0 : type;

	// 	if(gid > 0){
	// 		var url = '/ajax_article/indexgamemore?type='+type+'&gid='+gid+'&r='+(new Date()).getTime();
	// 	}else{
	// 		var url = '/ajax_article/indexmore?'+'r='+(new Date()).getTime();
	// 	}
	// 	_this.data('enable',0);
	// 	var top = gid > 0 ? 100 : 340;
	// 	$(document).scrollTop(top);
	// 	var _notice = $('.notice');
	// 	_notice.html('推荐中...');
	// 	_notice.css({"display":"block"});
	// 	$.get(url,function(r){
	// 		if(0 == r.statusCode){
	// 			_notice.after(_this.clone());
	// 			_this.remove();
	// 			_notice.after(r.html);
	// 			_notice.html('为您推荐了'+r.total+'篇文章');
	// 			setTimeout(function(){
	// 				_notice.slideUp('fast');
	// 			},1000);
	// 		}
	// 		_this.data('enable',1);
	// 	});
	// });

	//更多游戏展示
	// $(document).on('click','.box1 a.more',function(){
	// 	var _this = $(this);
	// 	if(0 == _this.data('enable')){
	// 		return false;
	// 	}
	// 	_this.data('enable',0);
	// 	$('.tan').fadeIn(800);//缺少ajax请求等待效果。为防止网络差的情况下用户疑问，先展示灰色遮罩。
	// 	$.get('ajax_game/all',{},function(r){
	// 		if(0 == r.statusCode){
	// 			$('.tan-moregame').find('.tan-txt').append(r.html);
	// 			$('.tan-moregame').fadeIn(800);
	// 			$('.tan-moregame').find('b.close').one('click',function(){
	// 		   		$('.tan').fadeOut(800);
	// 		      	$('.tan-moregame').fadeOut(800);
	// 		    });
	// 		}else{
	// 			popup(r.message);
	// 		}
	// 		_this.data('enable',1);
	// 	});
	// });

	//游戏添加和删除
	/*$(document).on('click','.regame b',function(){
		var _this = $(this);
		if(!sso.isLogin()){
			sso.login();
			return false;
		}

		if(0 == _this.data('enable')){
			return false;
		}

		var gid = parseInt(_this.data('gid'));
		if(isNaN(gid)){
			return false;
		}
		var name = _this.data('name');

		_this.data('enable',0);
		var isAdd = _this.hasClass('add');

		var url = isAdd ? '/ajax_user/addgame' : '/ajax_user/delgame';

		$.post(url,{'gid':gid},function(r){
			 if(0 == r.statusCode){
				 if(isAdd){
					 $('.navContent').append('<li id="left_nav_game_'+gid+'"><a href="/index/game?gid='+gid+'"><b></b>'+name+'</a></li>');
					 _this.removeClass('add');
					 _this.addClass('del');
				 }else{
					 $('#left_nav_game_'+gid).remove();
					 _this.removeClass('del');
					 _this.addClass('add');
				 }
			 }else{
				popup(r.message);
			 }
			 _this.data('enable',1);
		 });
  	});*/

	//游戏的添加
	$(document).on('click','.regame b.add,.regame img',function(){
		var _this = $(this);
		if(!sso.isLogin()){
			sso.login();
			return false;
		}
		var gid = _this.parents('a').find('b').data('gid')+0;
		if(isNaN(gid)){
			return false;
		}
		var name = _this.parents('a').find('b').data('name');
		var isAdd = _this.parents('a').find('b').hasClass('add');
		if(isAdd){
			var url = '/ajax_user/addgame';
			$.post(url,{'gid':gid},function(re){
				if(re.statusCode == 0){
					//菜单栏中游戏的添加
					$('.navContent_game').append('<li id="left_nav_game_'+gid+'"><a href="/index/game?gid='+gid+'&g_name='+name+'"><b></b>'+name+'</a></li>');
					_this.parents('a').find('b').addClass('del');
					_this.parents('a').find('b').removeClass('add');
				}else{
					popup(re.message);
				}
			});
		}
	});

	//游戏的删除
	$(document).on('click','.regame b.del',function(){
		var _this = $(this);
		if(!sso.isLogin()){
			sso.login();
			return false;
		}
		var gid = _this.data('gid');
		if(isNaN(gid)){
			return false;
		}
		//var name = _this.data('name');
		var isDel = _this.hasClass('del');
		if(isDel) {
			var url = '/ajax_user/delgame';
			$.post(url,{'gid':gid},function(re){
				if(re.statusCode == 0){
					//菜单栏中游戏的删除
					$('#left_nav_game_'+gid).remove();
					_this.addClass('add');
					_this.removeClass('del');
				}else{
					popup(re.message);
				}
			});
		}
	});


	//小组成员显示更多
	$(document).on('click','.user_showmore',function(){
		tmpObj.page++;
		$.ajax({
			url  : '/ajax_group/getmoreuserlist',
			type : 'GET',
			dataType : 'json',
			data : {'g_g_id':tmpObj.g_g_id, 'page':tmpObj.page, 'pageSize':tmpObj.pageSize},
			success : function (dt) {
				if (dt.statusCode == 0) {
					$('.group_userlist').append(dt.html);
					if (tmpObj.page > +tmpObj.pageCount) {
						$('.user_showmore').text('加载完了');
					}
				}
			},
			error : function () {
				$('.user_showmore').text('加载完了');
			}
		});
	});

	//账户设置和账号安全菜单样式修改
	var currentUrl = window.location.href;
	var urlArr = currentUrl.split('=');
	if(+urlArr[1] == 1){
		$('.navContent li:eq(0)').removeClass('on');
		$('.navContent li:eq(1)').addClass('on');
	}else if(+urlArr[1] == 2){
		$('.navContent li:eq(0)').removeClass('on');
		$('.navContent li:eq(1)').removeClass('on');
		$('.navContent li:eq(2)').addClass('on');
		$('.navContent li:eq(2)').parents('.navContent').css('display','block');
		$('.navContent li:eq(0)').parents('.navContent').css('display','none');
	}
});
