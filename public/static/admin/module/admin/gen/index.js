$(function() {
    layform.on('select(multi)', function(data) {
        _ajax({
            data: {
                multi: data.value
            },
            success: function(res) {
                var select = $('.layui-form-item').eq(1).find('select');
                select.empty();
                select.append('<option value="">选择一个表</option>');
                res.data.tables.forEach(function(value) {
                    select.append('<option value="' + value + '">' + value + '</option>');
                });
                layform.render('select');
            }
        }, false);
    });
});
