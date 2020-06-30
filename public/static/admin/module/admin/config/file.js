$(function() {
    $('.layui-plus').click(function() {
        layer_iframe('添加图片', 'fileAdd');
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
        skin: 'line', 
		toolbar: '#lay-toolbar', 
        limits: [10, 25, 50, 100],
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
            }
            , {
                field: 'id',
                title: 'ID',
                unresize: true,
                align: 'center'
            }, {
                field: 'type',
                title: '存储位置',
                unresize: true,
                align: 'center'
            }, {
                field: 'name',
                title: '文件名称',
                unresize: true,
                align: 'center'
            }, {
                field: 'href',
                title: '图片',
                unresize: true,
                align: 'center',
                templet:function (d) {
                    return '<img src=" '+d.href+'"></i>';
                }
            }, {
                field: 'mime',
                title: 'mime类型',
                unresize: true,
                align: 'center'
            }, {
                field: 'size',
                title: '文件大小',
                unresize: true,
                align: 'center'
            }, {
                field: 'ext',
                title: '文件后缀',
                unresize: true,
                align: 'center'
            }, {
                field: 'create_at',
                title: '创建时间',
                unresize: true,
                align: 'center'
            }, {
                title: '操作',
                unresize: true,
                align: 'center',
                toolbar: '#options'
            }]
        ],
        defaultToolbar: [{
            layEvent: 'refresh',
            icon: 'layui-icon-refresh',
        }, 'filter', 'print', 'exports']

    };
    window.tableIns = laytable.render(window.options);
    laytable.on('tool(dataTable)', function(obj) {
        var data = obj.data //获得当前行数据
        , layEvent = obj.event; //获得 lay-event 对应的值
        if (layEvent === 'del') {
            layer.confirm('确认删除吗？', function (index) {
                layer.close(index);
                var load = layer.load();
                $.post("fileDel", {ids: [data.id]}, function (res) {
                    layer.close(load);
                    if (res.code == 1) {
                        layer.msg(res.msg, {icon: 1, time: 1500}, function () {
                            obj.del();
                        })
                    } else {
                        layer.msg(res.msg, {icon: 2, time: 1500})
                    }
                });
            });
        }
    });
    laytable.on('toolbar(dataTable)', function(obj){
        if(obj.event === 'add'){
            layer_iframe('新增', 'fileAdd');
        } else if(obj.event === 'refresh'){
            refresh();
        } else if(obj.event === 'batchRemove'){
            batchRemove(obj);
        }
    });
    refresh = function(param){
        laytable.reload('dataTable');
    }
    //按钮批量删除
    batchRemove = function(obj){
        var ids = []
        var hasCheck = laytable.checkStatus('dataTable')
        var hasCheckData = hasCheck.data
        if (hasCheckData.length > 0) {
            $.each(hasCheckData, function (index, layelem) {
                ids.push(layelem.id)
            })
        }
        if (ids.length > 0) {
            layer.confirm('确认删除吗？', function (index) {
                layer.close(index);
                var load = layer.load();
                $.post("fileDel", {ids: ids}, function (res) {
                    layer.close(load);
                    if (res.code == 1) {
                        layer.msg(res.msg, {icon: 1, time: 1500}, function () {
                            window.tableIns = laytable.render(window.options);
                        })
                    } else {
                        layer.msg(res.msg, {icon: 2, time: 1500})
                    }
                });
            })
        } else {
            layer.msg('请选择删除项', {icon: 2})
        }
    }
});
