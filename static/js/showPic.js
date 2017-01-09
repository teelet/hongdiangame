$(function(){
    var sWidth = 0;
    var index = 0;
    var len = 0;

    //点击图片打开放大
    $(document).on('click','.needShow .smallPic a',function(){
        var _this = $(this);
        //隐藏需要放大的图片
        _this.parent('.smallPic').hide();

        //制作放大图片功能的盒子
        var img_src = _this.parent('.smallPic').data('img');
        var data_pics = _this.parent('.smallPic').data('pics');
        var data_style = _this.parent('.smallPic').data('style');
        var con_li='';
        var nav_li='';
        if(data_pics == 'morepic'){ //多图片
            if(data_style == 'blogcard'){ //blogcardlist
                for(var i=0;i<img_src.length;++i){
                    con_li += '<li class="silder_panel"><a href="javascript:;"><img src="'+ modifyPic(img_src[i]) +'"/></a></li>';
                }
                for(var j=0;j<img_src.length;++j){
                    nav_li += '<li><a href="javascript:;"><img src="'+ img_src[j] +'"/></a></li>';
                }
            }else{
                for(var i=0;i<img_src.length;++i){
                    con_li += '<li class="silder_panel"><a href="javascript:;"><img src="'+ modifyPic(img_src[i]['z_image']) +'"/></a></li>';
                }
                for(var j=0;j<img_src.length;++j){
                    nav_li += '<li><a href="javascript:;"><img src="'+ img_src[j]['z_image'] +'"/></a></li>';
                }
            }
        }else{ //单图片
            con_li = '<li class="silder_panel"><a href="javascript:;"><img src="'+modifyPic(img_src)+'"/></a></li>';
            nav_li = '<li><a href="javascript:;"><img src="'+img_src+'"/></a></li>';
        }
        var html = '<div id="slider">' +
                        '<div class="slider_box">' +
                            '<ul class="silder_con">'+con_li+'</ul>' +
                            '<ul class="silder_nav clearfix">'+nav_li+'</ul>' +
                        '</div>' +
                    '<div class="silderBox"></div>' +
                '</div>';
        _this.parent('.smallPic').after(html);

        //获取盒子宽度
        sWidth = _this.parents('.needShow').find('.slider_box').width();

        //获取需要放大的图片的索引,并放大图片
        index = _this.index();
        showPictures(_this,index);

        //显示盒子
        _this.parents('.needShow').find('#slider').show();

        //追加左右箭头
        var btn = "<a class='prev'>Prev</a><a class='next'>Next</a>";
        _this.parents('.needShow').find('.slider_box').append(btn);

        //获取大图个数,单图加左右箭头,多图去掉左右箭头
        len = _this.parents('.needShow').find('.slider_box .silder_panel').length;
        if(len <= 1){
            _this.parents('.needShow').find('.slider_box a.prev,.slider_box a.next').remove();
           // _this.parents('.needShow').find('.slider_box a.next').remove();
         }

        //设置宽度,左右换图片时不显得突兀
        _this.parents('.needShow').find('.slider_box .silder_con').css('width',sWidth*len);
    });

    //showPictures
    function showPictures(obj,index){
        var leftWidth = -index * sWidth;
        obj.parents('.needShow').find('.slider_box .silder_con').stop(true,false).animate({'left':leftWidth},300);
        obj.parents('.needShow').find('.slider_box .silder_nav li').removeClass("current").eq(index).addClass("current");
        obj.parents('.needShow').find('.slider_box .silder_nav li').stop(true,false).animate({"opacity": "0.5"}, 300).eq(index).stop(true, false).animate({"opacity": "1"}, 300);
    }

    //点击大图关闭放大
    $(document).on('click','.silder_con li img',function(){
        //$(this).parents('#slider').hide();
        $(this).parents('.needShow').find('.smallPic').show();
        $(this).parents('#slider').remove();
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

    //图片处理函数
    function modifyPic(img_src){
        var img_arr = img_src.split('?');
        var arr = img_arr[1].split('/');
        arr[3] = "660";
        arr[5] = "372";
        var arr1 = arr.join('/');
        var img = img_arr[0]+'?'+arr1;
        return img;
    }

});