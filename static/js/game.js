$(function(){

	$(document).on('click','.games .like-list a b,.games .like-list a img,.mygame a b',function(){
		var _this = $(this);
		if(!sso.isLogin()){
			sso.login();
			return false;
		}

		if(0 == _this.data('enable')){
			return false;
		}

		var gid = parseInt(_this.parent('a').children('b').data('gid'));
		if(isNaN(gid)){
			return false;
		}
		var name = _this.parent('a').children('b').data('name');
		_this.data('enable',0);
		var isAdd = _this.parent('a').children('b').hasClass('add');
		var url = isAdd ? '/ajax_user/addgame' : '/ajax_user/delgame';
		$.post(url,{'gid':gid},function(r){
			if(0 == r.statusCode){
				var n = _this.parent().clone();
				if(isAdd){
					//左侧菜单
					$('.navContent_game').append('<li id="left_nav_game_'+gid+'"><a href="/index/game?gid='+gid+'&g_name='+name+'"><b></b>'+name+'</a></li>');
					//我的游戏
					n.find('b').removeClass('add');
					n.find('b').addClass('del');
					$('.mygame > .like-list').append(n);
				}else{
					//左侧菜单
					$('#left_nav_game_'+gid).remove();
					//全部游戏
					n.find('b').removeClass('del');
					n.find('b').addClass('add');
					$('.game_'+_this.data('gtid')+' > .like-list').append(n);
				}
				_this.parent().remove();
			}else{
				popup(r.message);
			}
			_this.data('enable',1);
		});
  	});

	//点击选中状态
	// $(document).on('click','.like-list a',function(){
	// 	if(1 != $(this).data('haveClicked')){
	// 		$(this).find('b').css({"display":"block"});
	// 		$(this).data('haveClicked',1);
	// 	}else{
	// 		$(this).find('b').toggle();
	// 	}
	// });

	// 批量添加、删除游戏
	// $(document).on('click','#save_mygame',function(){
	// 	if(!sso.isLogin()){
	// 		sso.login();
	// 		return false;
	// 	}

	// 	var _this = $(this);
	// 	if(0 == _this.data('enable')){
	// 		return false;
	// 	}

	// 	var delArr = [];
	// 	var addArr = [];
	// 	$('.like-list').find('b:visible').each(function(){
	// 		var _t = $(this);
	// 		if(_t.hasClass('del')){
	// 			delArr.push(_t.data('gid'));
	// 		}

	// 		if(_t.hasClass('add')){
	// 			addArr.push(_t.data('gid'));
	// 		}
	// 	});
	// 	//如果没有选中任何一个，直接返回
	// 	if(delArr.length < 1 && addArr.length < 1){
	// 		popup('请选择要关注或取消关注的游戏');
	// 		return false;
	// 	}

	// 	//缺少ajax请求等待效果。为防止网络差的情况下用户疑问，先展示灰色遮罩。
	// 	$('.tan').fadeIn(800);
	// 	_this.data('enable',0);
	// 	var url = '/ajax_user/editgame';
	// 	$.post(url,{'dgid':delArr.join(','),'agid':addArr.join(',')},function(r){
	// 		if(0 == r.statusCode){
	// 			window.location.reload();
	// 		}else{
	// 			popup(r.message);
	// 		}
	// 		_this.data('enable',1);
	// 	});
	// });
});
