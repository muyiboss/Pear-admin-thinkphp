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
                checkbox: true,
                fixed: true
            }
            , {
                field: 'id',
                 title: 'ID', 
                 sort: true, 
                 align: 'center',
                 width: 80
            },{
                field: 'username',
                title: '账号',
                unresize: true,
                align: 'center'
            }, {
                field: 'nickname',
                title: '昵称',
                unresize: true,
                align: 'center'
            }, {
                field: 'create_at',
                title: '创建时间',
                unresize: true,
                align: 'center'
            }, {
                title: '状态',
                unresize: true,
                align: 'center',
                toolbar: '#status'
            }, {
                title: '操作',
                unresize: true,
                align: 'center',
                toolbar: '#options',
                width: 300
            }]
        ],
        defaultToolbar: [{
            layEvent: 'refresh',
            icon: 'layui-icon-refresh',
        }, 'filter', 'print', 'exports']
    };
    window.tableIns = laytable.render(window.options);
    laytable.on('tool(dataTable)', function(obj) {
        if (obj.event === 'edit') {
            layer_iframe('编辑管理员', 'edit?id=' + obj.data.id);
        } else if (obj.event === 'del') {
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
        } else if (obj.event === 'disabled') {
            _ajax_update('确定禁用该管理员吗?', 'admin_admin', obj.data.id, {
                status: 1
            });
        } else if (obj.event === 'enabled') {
            _ajax_update('确定启用该管理员吗?', 'admin_admin', obj.data.id, {
                status: 0
            });
        }else if (obj.event === 'role') {
            layer_iframe('分配角色', 'role?id=' + obj.data.id);
        }else if (obj.event === 'permission') {
            layer_iframe('分配直接权限', 'permission?id=' + obj.data.id);
        }
    });
    laytable.on('toolbar(dataTable)', function(obj){
            if(obj.event === 'add'){
                layer_iframe('新增', 'add');
            } else if(obj.event === 'refresh'){
                refresh();
            } else if(obj.event === 'batchRemove'){
                batchRemove(obj);
            }
    });
    //按钮批量删除
    batchRemove = function(obj){
        var ids = []
        var hasCheck = laytable.checkStatus('dataTable')
        var hasCheckData = hasCheck.data
        if (hasCheckData.length > 0) {
            $.each(hasCheckData, function (index, element) {
                ids.push(element.id)
            })
        }
        if (ids.length > 0) {
            _ajax_removes('确定删除吗?', 'admin_admin', ids)
        } else {
            layer.msg('请选择删除项', {icon: 2})
        }
    };
    layform.on('submit(query)', function(data){
        laytable.reload('dataTable',{where:data.field})
            return false;
    });
    refresh = function(param){
        laytable.reload('dataTable');
    }
});
