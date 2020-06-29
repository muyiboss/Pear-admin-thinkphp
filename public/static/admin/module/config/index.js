$(function() {
    layform.on('switch(login_captcha)', function(data) {
        if (data.elem.checked) {
            $(data.elem).val('1');
        } else {
            $(data.elem).val('0');
        }
    });
    layform.on('submit(configform)', function(data) {
        if (!data.field.login_captcha) {
            data.field.login_captcha = '0';
        }
        _ajax({
            data: data.field,
            success: function(res) {
                if (!res.code) {
                    layer.msg(res.msg, {
                        icon: 1
                    });
                }
            }
        });
        return false;
    });
    layform.on('radio(type)', function(data){
        if(data.value==2){
            $("#oss").show();
        }else{
            $("#oss").hide();
        }
    });  
});
