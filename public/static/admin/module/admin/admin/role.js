$(function() {
    layform.on('submit(trueform)', function(data) {
        _ajax({
            url: 'role?id='+$('#id').val(),
            data: data.field,
            success: function(res) {
                if (res.code!=1) {
                    layer.msg(res.msg, {
                        icon: 2
                    });
                } else {
                    layer.msg(res.msg, {
                        icon: 1
                    });
                    layer_iframe_close(333, true);
                }
            }
        });
        return false;
    });
});
