/**
 * Created by zejun1 on 2016/11/28.
 */
$(function(){

    //1、发送验证码
    var btn = true ;
    $('#send').click(function (){
        if(btn){
            btn = false;
            var time = 300;
            sendVer();
            function sendVer(){
                if(time == 0){
                    btn = true;
                    $('#send').text('发送验证码');
                }else{
                    $('#send').css('width','140px');
                    $('#send').text(time + '秒后可以重新发送');
                    time--;
                    setTimeout(function(){sendVer()},1000);
                }
            }

            //ajax 获取验证码模块
            var phone = '18656314359';
            var uid = cookie.get('uid');
            $.ajax({
                url : '/ajax_user/sendVerify',
                type : 'POST',
                dataType : 'json',
                data : {'uid':uid,'phone':phone},
                // success : function (re){ 
                //     console.log(JSON.stringify(re)); 
                //     return ; 
                // } 
            });
        }
    });


    //2、保存修改密码
    $(document).on('click','#save',function(){
        var uid = cookie.get('uid'); 
        var phone = '18656314359'; 
        var oldpass = $('.table .oldpass').val(); 
        var newpass = $('.table .newpass').val(); 
        var renewpass =$('.table .renewpass').val(); 
        var smscode =  $('.table .smscode').val();

        if(!sso.checkPassword(oldpass)){
            $('.table .error').text('旧密码格式不正确');
            return false;
        }

        if($.md5(newpass) != $.md5(renewpass)){
            $('.table .error').text('两次输入密码不一致');
            return false;
        }

        // if(!/^[\d]{6}$/.test(smscode)){
        //     $('.table .error').text('短信验证码错误');
        //     return false;
        // }

        oldpass =$.md5(oldpass);
        newpass = $.md5(newpass);
        renewpass = $.md5(renewpass);

        $.post(
            '/ajax_user/changePassword',
            {'uid':uid,'phone':phone,'oldpass':oldpass,'newpass':newpass,'renewpass':renewpass,'smscode':smscode},
            function(re){
                if(re.statusCode != 0){
                    $('.table .error').text(re.message);
                }else{
                    $('.table .error').text(re.message);
                    if(sso.isLogin()){
                        sso.logout();
                    }
                }
        });

    });

    //3、重置
    $(document).on('click','#reset',function(){
        window.location.href = window.location.href;
    });
})
