$(function() {
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
                field: 'name',
                title: '角色名称',
                unresize: true,
                align: 'center'
            }, {
                field: 'desc',
                title: '描述',
                unresize: true,
                align: 'center',
            }, {
                title: '操作',
                unresize: true,
                align: 'center',
                toolbar: '#options',
                width: 250
            }]
        ],
    };
    window.tableIns = laytable.render(window.options);
    laytable.on('tool(dataTable)', function(obj) {
        if (obj.event === 'edit') {
            layer_iframe('编辑角色', 'edit?id=' + obj.data.id);
        }else if (obj.event === 'permission') {
            layer_iframe('分配权限', 'permission?id=' + obj.data.id);
        }else if (obj.event === 'del') {
            _ajax({
                url: 'del',
                data: {
                    id:obj.data.id
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
                    }
                    window.tableIns = laytable.render(window.options);
                }
            });
        } 
    });
    laytable.on('toolbar(dataTable)', function(obj){
            if(obj.event === 'add'){
                layer_iframe('新增', 'add');
            } 
    });
});
