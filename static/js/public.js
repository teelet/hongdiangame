// JavaScript Document
$(function(){
	$(".subNav").click(function(){
		$(this).toggleClass("currentDd").siblings(".subNav").removeClass("currentDd")
		$(this).toggleClass("currentDt").siblings(".subNav").removeClass("currentDt")
		// 修改数字控制速度， slideUp(500)控制卷起速度
		$(this).next(".navContent").slideToggle(500).siblings(".navContent").slideUp(500);
	})
	/*
	$('.pl-hf .hf-icon').click(function(){
		if($(this).parent('.zan').next('.hf-form').css('display') == 'block'){
			$(this).parent('.zan').next('.hf-form').hide();
		}else{
			$(this).parent('.zan').next('.hf-form').show();
		}
  	});
	*/
	$('.blog_comment_list, .user_comment_list, .message_list').on('click', '.pl-hf .hf-icon', function () {
		if($(this).parent('.zan').next('.hf-form').css('display') == 'block'){
			$(this).parent('.zan').next('.hf-form').hide();
		}else{
			$(this).parent('.zan').next('.hf-form').show();
		}
	});
	
	$('.del').click(function(){
      	$(this).parents('.pl-hf').remove();
  	});
	$('.tab_group_index a').click(function(){
      	$('.tab_group_index a').removeClass('on');
      	$('.tab_group_index a').eq($(this).index()).addClass('on');
  	})
	
	$(".tablebox .tablebox1").click(function(){
		$(this).next().show();
		//alert($(this).next().parent().html());
	});
	$(".tablebox2 li").click(function(){
		var value=$(this).html();
		// $(this).find('.tablebox1').html(value);
		$(this).parent().parent().find(".tablebox1").html(value)
		// alert()
		$(".tablebox1").next().hide();
	});
	$(document).on('click', function(e){
		if(e.target.parentNode.id != 'menu'){
			$(".tablebox1").next().hide();
		}
	});
	
	//点击搜索框

	/*$('.search .txt').click(function(){
      	$(this).next('.search-list').fadeIn(800);
		$(this).next('.search-list').css({'position':'relative', 'z-index':1000});
      	$('body').one('click',function(){
       		$('.search-list').fadeOut(800);
      	})
      	return false;
  	})*/
	$(".search-list").delegate('li','click',function(){
		var liText = $(this).text() ;
		$(this).parents('.search-list').siblings('.txt').val(liText) ;
		$(this).parents('.search-list').siblings('.txt').focus();
		$(this).parents('.search-list').css('display','none');
	}) ;
	
	/*$('.search .txt').focus(function () {
		$(this).parents('.search').css("border-color", "#ff4747");
	}).blur(function () {
		$(this).parents('.search').css("border-color", "#eeeff2");
	})*/

	$('.cpic').on('click', 'em .close', function () {
		$(this).parents('.cpic em').remove();
	})
	 
	$('.close').click(function(){
      	$(this).parents('.cpic em').remove();
  	});
})
var cookie = {
    set : function(n,v,e){
        if(typeof e == 'undefined'){
            e = {};
        }
        if(typeof e.expire == 'undefined'){
            e.expire = 14*24*60*60*1000;
        }
        if(typeof e.domain == 'undefined'){
            e.domain = document.location.host;
        }
        if(typeof e.path == 'undefined'){
            e.path = '/';
        }
        var exp = new Date(); 
        exp.setTime(exp.getTime() + e.expire); 
        document.cookie = n + "="+ escape (v) + ";expires=" + exp.toGMTString() + ";domain=" + e.domain +";p=" + e.path;
    },
    get : function(n){
        var arr,reg=new RegExp("(^| )"+n+"=([^;]*)(;|$)");
        if(arr=document.cookie.match(reg))
            return unescape(arr[2]); 
        else 
            return null; 
    },
    del : function(n){
        var exp = new Date(); 
        exp.setTime(exp.getTime() - 1); 
        document.cookie= n + "=;expires="+exp.toGMTString(); 
    }
};

var util = {
	isPhone : function(s){
		return /^1[3|4|5|7|8][0-9]{9}$/.test(s); 
	}
};

var sso = {
	key13 : function(){
		$('.tan-con :text,.tan-con :password').on('keydown',function(e){
			if(13 == e.keyCode){
				$(this).parents('.tan-con').find(':button').click();
			}
		})
	},
	gErr : function(s){
		var errArr = {
			'e_01' : '手机号或密码错误',
			'e_02' : '手机号格式错误，请输入正确的手机号',
			'e_03' : '密码格式错误，请输入6~20个英文+数字，区分大小写',
			'e_04' : '昵称格式错误，请输入汉字、英文或数字的组合',
			'e_05' : '短信验证码错误',
			'e_99' : '系统繁忙，请稍后重试'
		};
		return errArr['e_'+s];
	},
	checkPhone : function(s){
		return util.isPhone(s);
	},
	checkPassword : function(s){
		return /^[a-zA-Z0-9]{6,20}$/.test(s);
	},
	checkNickname : function(s){
		return /^[a-zA-Z0-9\u4E00-\u9FA5]+$/.test(s);
	},
	checkSms : function(s){
		return /^[\d]{6}$/.test(s);
	},
	isLogin : function(){//只简单验证cookie中是否有sid，不判真伪
		return cookie.get('sid') ? true : false;
	},
	panel : function(cur){
		$('.tan').fadeIn(800); 
		$.each(['login','regist','activate','verify','modify'],function(i,v){
			if(cur == v){
				$('.tan-'+v).fadeIn(800);
			}else{
				$('.tan-'+v).fadeOut(800);
			}
		});
      	$('b.close').one('click',function(){
       		$('.tan').fadeOut(800);
          	$('.tan-'+cur).fadeOut(800);
      	});
      	return false;
	},
	logout : function(){
		if(sso.isLogin()){
			$.get('/ajax_user/logout',function(r){
				if(0 == r.statusCode){
					window.location = '/';
				}else{
					popup(r.message);
				}
			});
		}else{
			window.location.reload();
		}
	},
	login : function(){
		if(!sso.isLogin()){
	      	sso.panel('login');
	      	sso.key13();
	    }else{
	    	window.location.reload();
	    }
	},
	dologin : function(){
		var phone = $('#login_phone').val();
		var password = $('#login_password').val();
		if(!sso.checkPhone(phone) || !sso.checkPassword(password)){
			$('#login_err').text(sso.gErr('01'));
			return false;
		}
		password = $.md5(password);
		$.post(
			'/ajax_user/login',
			{'phone':phone,'password':password},
			function(r){
				if(0 == r.statusCode){
					window.location.reload();
				}else if(202 == r.statusCode){
					$('#activate_phone').val(r.phone);
					$('#activate_uid').val(r.uid);
					$('#activate_code').val(r.code);
					sso.activate();
				}else{
					$('#login_err').text(r.message);
				}
			});
	},
	regist : function(){
		sso.panel('regist');
		sso.key13();
	},
	doregist : function(){
		var phone = $('#regist_phone').val();
		var password = $('#regist_password').val();
		var nickname = $('#regist_nickname').val();
		if(!sso.checkNickname(nickname)){
			$('#regist_err').text(sso.gErr('04'));
			return false;
		}
		if(!sso.checkPhone(phone)){
			$('#regist_err').text(sso.gErr('02'));
			return false;
		}
		if(!sso.checkPassword(password)){
			$('#regist_err').text(sso.gErr('03'));
			return false;
		}
		password = $.md5(password);
		$.post('/ajax_user/regist',{'phone':phone,'nickname':nickname,'password':password},function(r){
			if(0 == r.statusCode){
				$('#activate_phone').val(r.phone);
				$('#activate_uid').val(r.uid);
				$('#activate_code').val(r.code);
				sso.activate();
			}else{
				$('#regist_err').text(r.message);
				return false;
			}
		});
	},
	activate : function(){
		sso.panel('activate');
		sso.key13();
	},
	doactivate : function(){
		var phone = $('#activate_phone').val();
		var smscode = $('#activate_smscode').val();
		var uid = $('#activate_uid').val();
		var code = $('#activate_code').val();

		if(!sso.checkSms(smscode)){
			$('#activate_err').text(sso.gErr('05'));
		}

		if('' == phone || '' == uid || '' == code){
			$('#activate_err').text(sso.gErr('99'));
		}

		$.post('/ajax_user/activate',{'phone':phone,'smscode':smscode,'uid':uid,'code':code},function(r){
			if(0 == r.statusCode){
				sso.login();
			}else{
				$('#activate_err').text(r.message);
			}
		});
	},
	sms : function(panel){
		var yzm_btn = $('.tan-'+panel).find('.yzm-btn');
		if(1 != yzm_btn.data('cando')){
			return false;
		}
		yzm_btn.data('cando',0);
		var wait = 60;
		yzm_btn.find('i').text('('+wait+')');
		var count = setInterval(function(){
			yzm_btn.find('i').text('(' + --wait + ')');
			if(wait == 0){
				yzm_btn.find('i').text('');
				yzm_btn.data('cando',1);
				clearInterval(count);
			}
		},1000);
		return true;
	},
	dosms : function(panel){
		if(!sso.sms(panel)){
			return false;
		}
		var phone = $('#'+panel+'_phone').val();
		var uid = $('#'+panel+'_uid').val();
		var code = $('#'+panel+'_code').val();

		if('' == phone || '' == uid || '' == code){
			$('#'+panel+'_err').text(sso.gErr('99'));
		}

		$.post('/ajax_user/sms',{'phone':phone,'uid':uid,'code':code},function(r){
			if(0 == r.statusCode){
				$('#'+panel+'_err').text(r.message);
			}else{
				$('#'+panel+'_err').text(r.message);
			}
		});
	},
	verify : function(){
		sso.panel('verify');
		sso.key13();
	},
	doverify : function(){
		var phone = $('#verify_phone').val();
		
		if(!sso.checkPhone(phone)){
			$('#verify_err').text(sso.gErr('02'));
			return false;
		}
		$.post('/ajax_user/verify',{'phone':phone},function(r){
			if(0 == r.statusCode){
				$('#modify_phone').val(r.phone);
				$('#modify_uid').val(r.uid);
				$('#modify_code').val(r.code);
				sso.modify();
			}else{
				$('#verify_err').text(r.message);
				return false;
			}
		});
	},
	modify : function(){
		sso.panel('modify');
		sso.key13();
	},
	domodify : function(){
		var phone = $('#modify_phone').val();
		var password = $('#modify_password').val();
		var smscode = $('#modify_smscode').val();
		var uid = $('#modify_uid').val();
		var code = $('#modify_code').val();

		if(!sso.checkPassword(password)){
			$('#modify_err').text(sso.gErr('03'));
			return false;
		}

		if(!sso.checkSms(smscode)){
			$('#modify_err').text(sso.gErr('05'));
		}

		if('' == phone || '' == uid || '' == code){
			$('#modify_err').text(sso.gErr('99'));
		}
		password = $.md5(password);
		$.post('/ajax_user/modify',{'phone':phone,'password':password,'smscode':smscode,'uid':uid,'code':code},function(r){
			if(0 == r.statusCode){
				popup(r.message);
				sso.login();
			}else{
				$('#modify_err').text(r.message);
			}
		});
	}
}

//公用弹框
function popup(content,cb){
	var _popup = $('.tan-popup');
	_popup.find('.tan-txt > p').text(content);
	$('.tan').fadeIn(800);
	_popup.fadeIn(800);

	_popup.find('b.close').one('click',function(){
   		$('.tan').fadeOut(800);
      	$('.tan-popup').fadeOut(800);
      	if($.isFunction(cb)){
			cb();
		}
  	});

  	setTimeout(function(){
		$('.tan').fadeOut(800);
		_popup.fadeOut(800);
		if($.isFunction(cb)){
			cb();
		}
	},3000);
}

var share = {
	content : {},
	init : function(id){
		var o = $('#'+id);
		//默认当前页地址
		share.content.url = encodeURIComponent(o.data('url') == '' ? window.location.href : o.data('url'));
		//默认网站名称
		share.content.title = encodeURIComponent(o.data('title') == '' ? '红心游戏' : o.data('title'));
		//默认网站简介
		share.content.desc = encodeURIComponent(o.data('desc') == '' ? '红心游戏,desc' : o.data('desc'));
		//此处默认图最好是方图，尺寸不小于300x300
		share.content.image = encodeURIComponent(o.data('image') == '' ? 'http://branches.alexgame.cn/static/images/img15.jpg' : o.data('image'));

		o.children('.qq').on('click',share.qq);
		o.children('.wb').on('click',share.wb);
		o.children('.wx').on('click',share.wx);
		o.children('.qqzone').on('click',share.qqzone);
	},
	qq : function(){
		var _QQ_share_url = 'http://connect.qq.com/widget/shareqq/index.html?url='+share.content.url+'&showcount=0&desc='+share.content.desc+'&summary='+share.content.desc+'&title='+share.content.title+'&site=&pics='+share.content.image;
		window.open(_QQ_share_url);
	},
	wb : function(){
		var _sina_url = 'http://service.weibo.com/share/share.php?title='+share.content.title+'&url='+share.content.url+'&source=&appkey=&pic='+share.content.image+'&relateUid=';
		window.open(_sina_url);
	},
	wx : function(){
		var l = ($(window).width() - 360) / 2;
		var t = ($(window).height() - 360 - 60) / 3;
		if($('#wxcode_bg').length == 0){
			var html = '<div id="wxcode_bg" style="width:100%; height:100%; position:fixed; top:0; left:0; background-color:#000; filter:alpha(opacity=40); opacity:0.4; z-index:999; display:none;"></div><div id="wxcode" style="position:fixed; width:340px; height:340px; top:50%; margin-top:-170px; left:50%; margin-left:-170px; background:#fff; display:none; z-index:9999;"><img style="width:300px; height:300px; margin:20px;" src="http://s.jiathis.com/qrcode.php?url='+share.content.url+'" /></div>';
			$('body').append(html);
		}
		$('#wxcode_bg').fadeIn(800);
		$('#wxcode').fadeIn(800);
		$('#wxcode_bg').on('click',function(){
			$('#wxcode_bg').fadeOut(800);
			$('#wxcode').fadeOut(800);
		});
	},
	qqzone : function(){
		var _Qzone_share_url = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+ share.content.url +'&title='+ share.content.title +'&pics='+ share.content.image +'&summary='+ share.content.desc;
		window.open(_Qzone_share_url);
	}
}

function changeName()
{
	var f = document.getElementById("filename");
	f.value = document.getElementById("infile").value;
}
function openFile()
{
	document.getElementById("infile").click();
}
$(function(){
	//获取未读消息数，1分钟获取一次。随时可改
	if(sso.isLogin()){
		//var msgnum = setInterval(function(){
			$.get('/ajax_user/msgnum',function(r){
				if(0 == r.statusCode){
					if(r.result > 0){
						$('#header_msg_num').append('<i>'+r.result+'</i>');
					}
				}
			});
		//},60000);
	}
	//顶导搜索
	var keywords = '';
	var index = 0;
	var first = 1;
	$('.search .keywords').on('keyup', function (e) {
		var key = e.keyCode;
		switch (key){
			case 13 : //回车
				go_search_result();
				break;
			case 38 : //上
				index--;
				if(index < 0){
					index = $('.search-list li').size() - 1;
				}
				$('.search-list li').removeClass('search-list-li').eq(index).addClass('search-list-li');
				break;
			case 40 : //下
				if(first == 1){
					index = 0;
					first = 0;
				}else{
					index++;
				}
				if(index > ($('.search-list li').size() - 1)){
					index = 0;
				}
				$('.search-list li').removeClass('search-list-li').eq(index).addClass('search-list-li');

				break;
			default : //输入内容
				doSearch();
		}
	});

	$('.search .but').click(function () {
		go_search_result();
	});

	function go_search_result() {
		keywords = $('.search .txt').val().trim();
		if(keywords.length == 0){
			$('.search .txt').focus();
			return false;
		}
		var url = '/search/result?keywords=' + keywords;
		window.location.href = url;
	}

	function doSearch() {
		index = 0;
		first = 1;
		keywords = $('.search .txt').val().trim();
		if(keywords.length == 0){
			$('.search-list').css('display','none');
			return;
		}
		$.ajax({
			url  : '/ajax_search/doSearch',
			type : 'POST',
			dataType : 'json',
			data : {'keywords':keywords},
			success : function (dt) {
				if(dt.statusCode == 0){
					if(keywords.length == 0){
						$('.search-list').css('display','none');
						return;
					}else{
						$('.search-list').html(dt.html);
						$('.search-list').fadeIn();
					}
				}
			}
		});
	}

	$('.search-list').on('mouseover', 'li', function () {
		index = $(this).index();
		$('.search-list li').removeClass('search-list-li');
		$(this).addClass('search-list-li');
	});

	$('.search-tab').on('click', 'a', function () {
		$(this).siblings('a').removeClass('on');
		$(this).addClass('on');
		tmpObj.tab = $(this).index();
		tmpObj.page = 0;
		$('.search-result .tab2').html('');
		$('.search-result .feed_more').click();
	});

	//搜索结果 加载更多
	$('.search-result .feed_more').click(function () {
		if(tmpObj.keywords == ''){
			$('.search .keywords').focus();
			return false;
		}
		tmpObj.page++;
		//console.log({'keywords':tmpObj.keywords, 'tab':tmpObj.tab, 'page':tmpObj.page, 'page_size':tmpObj.page_size});
		$.ajax({
			url  : '/ajax_search/searchResult',
			type : 'GET',
			dataType : 'json',
			data : {'keywords':tmpObj.keywords, 'tab':tmpObj.tab, 'page':tmpObj.page, 'page_size':tmpObj.page_size},
			success : function (dt) {
				if(dt.statusCode == 0 && dt.html != ''){
					$('.search-result .tab2').append(dt.html);
				}else{
					$('.search-result .feed_more').text('无更多内容');
				}
			}
		});
	});

	//加入小组、退出小组
	$('.recommend_group, .search-result').on('click', '.add_group', function () {
		if(!sso.isLogin()){
			sso.panel('login');
			sso.key13();
			return false;
		}
		var thisObj = $(this);
		var g_g_id = thisObj.attr('data-ggid'); //小组id
		var is = thisObj.attr('data-is'); //是否已经加入小组了  0 否   1 是
		if(is == 0){ //执行加入操作
			$.ajax({
				url  : '/ajax_group/addgroup',
				type : 'POST',
				dataType : 'json',
				data : {'g_g_id':g_g_id},
				success : function (dt) {
					if(dt.statusCode == 0){
						if($('.subNavBox ul').length > 0){
							var g_g_name = thisObj.siblings('a').text();
							var str = '<li id="left_nav_game_'+ g_g_id +'"><a href="/group?g_g_id='+ g_g_id +'"><b></b>'+ g_g_name +'</a></li>';
							$('.subNavBox ul').eq(1).append(str);
						}
						thisObj.addClass('yadd');
						thisObj.text('√已加入');
						thisObj.attr('data-is', 1);
					}
				},
				error : function () {
				}
			});
		}else{ //执行退出操作
			$.ajax({
				url  : '/ajax_group/deletegroup',
				type : 'POST',
				dataType : 'json',
				data : {'g_g_id':g_g_id},
				success : function (dt) {
					if(dt.statusCode == 0){
						if($('.subNavBox ul').length > 0){
							var id = '#left_nav_game_' + g_g_id;
							$(id).remove();
						}
						thisObj.removeClass('yadd');
						thisObj.text('加入小组');
						thisObj.attr('data-is', 0);
					}
				},
				error : function () {
				}
			});
		}
	});

});























