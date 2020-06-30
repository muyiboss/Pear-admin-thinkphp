$(function() {
    $('.layui-plus').click(function() {
        layer_iframe('添加多级', 'add');
    });
    window.page = 1;
    window.limit = 10;
    window.options = {
        id: 'dataTable',
        elem: '#dataTable',
        url: location.pathname + location.search,
        where: {},
        page: {
            curr: 1
        },
        limits: [10, 25, 50, 100],
        skin: 'line',
        toolbar: '#lay-toolbar',
        done: function(res, curr, first) {
            if (!res.data && curr > 1) {
                window.options.page.curr = curr - 1;
                window.tableIns.reload(window.options);
            }
            window.options.page.curr = curr;
            window.page = curr;
            window.limit = res.limit;
        },
        cols: [
            [{
                field: 'url',
                title: '多级地址',
                unresize: true,
                align: 'center'
            }, {
                field: 'prefix',
                title: '库表前缀',
                unresize: true,
                align: 'center'
            }, {
                field: 'name',
                title: '多级名称',
                unresize: true,
                align: 'center'
            }, {
                title: '操作',
                unresize: true,
                align: 'center',
                toolbar: '#options',
                width: 150
            }]
        ],
    };
    window.tableIns = laytable.render(window.options);
    laytable.on('tool(dataTable)', function(obj) {
        if (obj.event === 'edit') {
            layer_iframe('编辑多级', 'edit?id=' + obj.data.id);
        } else if (obj.event === 'del') {
            layer.confirm('删除多级将删除多级下所有内容,确定删除吗?', function(index) {
                layer.close(index);
                _ajax({
                    url: 'del',
                    data: {
                        id: obj.data.id
                    },
                    success: function(res) {
                        if (res.code!=1) {
                            layer.msg(res.msg, {
                                icon: 2
                            });
                        } else {
                            layer.msg(res.msg, {
                                icon: 1
                            });
                            window.tableIns.reload(window.options);
                        }
                    }
                });
            });
        }
    });
    laytable.on('toolbar(dataTable)', function(obj){
        if(obj.event === 'add'){
            layer_iframe('新增', 'add');
        } 
});
});
