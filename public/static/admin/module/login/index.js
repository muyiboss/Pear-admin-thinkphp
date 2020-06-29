$(function() {
    var $view = $('#pear-login');
    if (self !== top) {
        top.location.href = location.href;
    }
    initCode();
    //提交登录表单
    layform.on('submit(login-submit)', function (data) {
        _ajax({
            data: data.field,
            success: function(res) {
                if (res.code=='0') {
                    layer.msg(res.msg, {
                        icon: 2,
                    });
                    initCode();
                } else {
                    layer.msg(res.msg, {
                        icon: 1,
                    });
                    setTimeout(function() {
                        location.href = '/';
                    }, 333)
                }
            },
            error: function() {
                layer.msg('系统繁忙,请稍后重试', {
                    icon: 0,
                });
                initCode();
            }
        });
        return false;
    });
    function initCode() {
        $view.find('#codeimg').attr("src","/verify?data=" + new Date().getTime());
    }
    $view.find('#codeimg').on('click', function () {
        initCode();
    });
});
