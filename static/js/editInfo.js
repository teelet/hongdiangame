/**
 * Created by zejun1 on 2016/12/6.
 */
$(function(){
    // 1、保存个人资料编辑
    $(document).on('click','#save',function(){
        var nickname = $('table input.nickname').val();
        if(nickname == ''){
            popup('昵称不能为空!');
            return ;
        }
        var summary = $('table textarea.summary').val();
        if(summary == ''){
            popup('个性签名不能为空!');
            return ;
        }
        var sex = $('table .xb span.cur').text() == '男' ? 1 : 2;

        var year = $('#menu .default-year').text();
        var month = $('#menu .default-month').text();
        var day = $('#menu .default-day').text();

        var province = $('#menu .province').text();
        if(province == '省份'){
            popup('请选择省份!');
            return ;
        }
        var city = $('#menu .city').text();
        if(city == '城市'){
            popup('请选择城市!');
            return ;
        }

        $.post(
            '/ajax_user/editUserInfo',
            {
                'nickname':nickname,
                'summary':summary,
                'sex':sex,
                'year':year,
                'month':month,
                'day':day,
                'province':province,
                'city':city
            },
            function(re){
                switch(re.statusCode){
                    case 0:
                        window.location.href = '/user/userinfo';
                        break;
                    case -1:
                        popup(re.message);
                        break;
                    case -2:
                        $('table tr').eq(0).find('.nick-err').text(re.message);
                        break;
                    case -3:
                        $('table tr').eq(1).find('.summary-err').text(re.message);
                        break;
                    case -4:
                        popup(re.message);
                        break;
                    case -5:
                        popup(re.message);
                        break;
                }
            }
        );
    });

    // 2、 重置个人资料编辑
    $(document).on('click','#reset',function(){
        window.location.href = window.location.href ;
    });
});