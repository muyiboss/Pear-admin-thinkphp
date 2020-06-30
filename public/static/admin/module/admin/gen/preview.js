$(function() {
    $('.layui-back').click(function() {
        history.back();
    });
    layform.on('submit(preview)', function(data) {
        data.field.preview = 'false';
        _ajax({
            data: data.field,
            success: function(res) {
                layer.msg(res.msg, {
                    icon: 1
                });
                setTimeout(function() {
                    history.back();
                }, 333);
            },
            error: function() {
                layer.msg('操作失败', {
                    icon: 2
                });
            }
        });
        return false;
    });
    layui.code({
        elem: 'pre',
        about: false,
        height: '300px'
    });
    layelem.render('collapse');
});
