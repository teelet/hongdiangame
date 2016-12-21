$(function(){
    //翻到页底获取下一页评论
	//$(window).scroll(function () {
    $(document).on('scroll',window,function(){
        if ($(document).scrollTop() + $(window).height() >= $(document).height()) {
            var page = $('.pinglun').data('page');
            var aid = $('.pinglun').data('aid');
            var pageCount = $('.pinglun').data('pagecount');
        	if(pageCount > page) {
	            $.get("/ajax_comment/commentlist?aid="+aid+"&page="+(page+1),function(r){
	            	if(0 == r.statusCode){
                        if('' != r.result){
                            $('.pinglun').append(r.html);
                            $('.pinglun').data('page',page+1);
                            pageCount = r.pageCount;
                        }else{
                            return false;
                        }
	            	}else{
                        popup(r.message);
                        return false;
                    }
	            });
	        }else{
	        	return false;
	        }
        }  
    });

    //展开评论框
    $(document).on('focus','#txt',function(){
        $(this).animate({"height":"100px"});
    });
    //收起评论框
    $(document).on('blur','#txt',function(){
        $(this).animate({"height":"20px"});
    })

    //发表评论
    $(document).on('click','#f_submit',function(){
        var _this = $(this);
        if(0 == _this.data('enable')){
            return false;
        }

        if(!sso.isLogin()){
            sso.login();
            return false;
        }
    	var txt = $('#f_comment #txt').val();
    	if('' == $.trim(txt)){
            popup('评论内容不能为空');
    		return false;
    	}
        _this.data('enable',0);
    	$.post('/ajax_comment/comment',$('#f_comment').serialize(),function(r){
    			if(0 == r.statusCode){
                    if('' != r.result){
                        $('.pinglun').prepend(r.html);
                        var num_c = $('.all-comment > h3:first > b');
                        num_c.text($.isNumeric(num_c.text()) ? parseInt(num_c.text()) + 1 : 1);
                        $('#f_comment #txt').val('')
                    }
            	}else{
                    popup(r.message);
                }
                _this.data('enable',1);
    		});
    });

    //评论点赞
    $(document).on("click",".zan-icon", function(){
        var _this = $(this);
        if(0 == _this.data('enable')){
            return false;
        }

        if(!sso.isLogin()){
            sso.login();
            return false;
        }
        var c_id = _this.data('id');
        _this.data('enable',0);

        $.post('/ajax_comment/doFavor',{'c_id':c_id},function(r){
                if(0 == r.statusCode){
                    _this.text($.isNumeric(_this.text()) ? parseInt(_this.text()) + 1 : 1);
                }else{
                    popup(r.message);
                }
                _this.data('enable',1);
            });
    });

    //展开回复评论框
    $(document).on('click','.hf-icon',function(){
        if(!sso.isLogin()){
            sso.login();
            return false;
        }
        $('#f_reply_'+$(this).data('id')).toggleClass('hf-formhide');
    });

    //回复评论
    $(document).on('click','.reply',function(){
        var _this = $(this);
        if(0 == _this.data('enable')){
            return false;
        }

        if(!sso.isLogin()){
            sso.login();
            return false;
        }
        var f_replay = _this.parent().parent();
        if('' == $(f_replay).find('textarea').val()){
            popup('回复内容不能为空');
            return false;
        }
        _this.data('enable',0);
        
        $.post('/ajax_comment/reply',$(f_replay).serialize(),function(r){
            if(0 == r.statusCode){
                if('' != r.html){
                    _this.parents('dd.fr').after(r.html);
                    var num_c = _this.parents('div.pl-name').find('a.hf-icon');
                    num_c.text($.isNumeric(num_c.text()) ? parseInt(num_c.text()) + 1 : 1);
                    $(f_replay).find('textarea').val('')
                }
            }else{
                popup(r.message);
            }
            _this.data('enable',1);
        });
    });

    //更多回复
    $(document).on('click','.reply_more',function(){
        var _this = $(this);
        if(0 == _this.data('enable')){
            return false;
        }

        var page = _this.data('page');
        var cid = _this.data('cid');
        if(!$.isNumeric(cid) || cid < 1){
            popup('没有回复了');
            _this.parent().remove();
            return false;
        }
        _this.data('enable',0);
        
        $.get('/ajax_comment/replylist',{'cid':cid,'page':page},function(r){
            if(0 == r.statusCode){
                if('' != r.result){
                    _this.parent().before(r.html);
                    _this.data('page',page+1);
                    if(page >= r.pageCount){
                        _this.parent().remove()
                    }
                }else{
                    _this.parent().remove();
                }
            }else{
                popup(r.message);
            }
            _this.data('enable',1);
        });
    });

    $(document).on('click','#report',function(){
        var _this = $(this);
        if(0 == _this.data('enable')){
            return false;
        }

        if(!sso.isLogin()){
            sso.login();
            return false;
        }
        _this.data('enable',0);

        $.post('/ajax_article/report',{'aid':_this.data('aid')},function(r){
            popup('举报成功');
            _this.data('enable',1);
        });
    });

	//初始化分享
	share.init('share');
});
