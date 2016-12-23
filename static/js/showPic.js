$(function(){
    var sWidth = $('.slider_box').width();
    var index = 0;
    var len = 0;

    //点击图片打开放大
    $(document).on('click','.needShow .smallPic a',function(){
        var _this = $(this);
        _this.parent('.smallPic').hide();
        index = _this.index();
        showPictures(_this,index);
        _this.parents('.needShow').find('#slider').show();

        //追加左右箭头
        var btn = "<a class='prev'>Prev</a><a class='next'>Next</a>";
        _this.parents('.needShow').find('.slider_box').append(btn);

        //获取大图个数
        len = _this.parents('.needShow').find('.slider_box .silder_panel').length;
        if(len <= 1){
            _this.parents('.needShow').find('.slider_box a.prev').remove();
            _this.parents('.needShow').find('.slider_box a.next').remove();
         }

    });

    //设置宽度,左右换图片时不显得突兀
    $('.slider_box .silder_con').css('width',sWidth*3);

    //showPictures
    function showPictures(obj,index){
        var leftWidth = -index * sWidth;
        obj.parents('.needShow').find('.slider_box .silder_con').stop(true,false).animate({'left':leftWidth},300);
        obj.parents('.needShow').find('.slider_box .silder_nav li').removeClass("current").eq(index).addClass("current");
        obj.parents('.needShow').find('.slider_box .silder_nav li').stop(true,false).animate({"opacity": "0.5"}, 300).eq(index).stop(true, false).animate({"opacity": "1"}, 300);
    }

    //点击大图关闭放大
    $(document).on('click','.silder_con li img',function(){
        $(this).parents('#slider').hide();
        $(this).parents('.needShow').find('.smallPic').show();
        return false;
    });

    //当鼠标指针穿过下面下图,轮播大图
    $('.slider_box .silder_nav li').css({"opacity": "0.6", "filter": "alpha(opacity=60)"});
    $(document).on('mouseenter','.slider_box .silder_nav li',function(){
        var _this = $(this);
         index = _this.index();
         showPictures(_this,index);
    });

    //Next
    $(document).on('click','.slider_box .next',function(){
        if(len > 1){
            var _this = $(this);
            index += 1;
            if(index == len){
                index = 0;
            }
            showPictures(_this,index);
        }
    });

    //Prev
    $(document).on('click','.slider_box .prev',function(){
        if(len > 1){
            var _this = $(this);
            index -= 1;
            if(index == -1){
                index = len - 1;
            }
            showPictures(_this,index);
        }
    });

});