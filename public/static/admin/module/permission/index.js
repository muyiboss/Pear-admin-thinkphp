$(function() {
layui.use(['table','treetable'],function () {
    let table = layui.table;
    let treetable = layui.treetable;
    window.render = function(){
        treetable.render({
            treeColIndex: 0,
            treeSpid: 0,
            treeIdName: 'id',
            treePidName: 'pid',
            skin:'line',
            method:'post',
            treeDefaultClose: true,
            toolbar:'#power-toolbar',
            elem: '#power-table',
            url: 'index',
            page: false,
            cols: [
                [
                {field: 'title', minWidth: 200, title: '权限名称'},
                {field: 'icon',title: '图标', unresize: true, align: 'center',
                    templet:function (d) {
                        return '<i class=" '+d.icon+'"></i>';
                    }
                }, 
                {field: 'type', title: '权限类型',templet:'#power-type'},
                {field: 'sort', title: '排序'},
                {title: '操作',templet: '#power-bar', width: 150, align: 'center'}
                ]
            ]
        });
    }
    render();
    table.on('tool(power-table)',function(obj){
        if (obj.event === 'remove') {
            window.remove(obj);
        } else if (obj.event === 'edit') {
            window.edit(obj);
        }
    })
    table.on('toolbar(power-table)', function(obj){
        if(obj.event === 'add'){
            layer_iframe('新增', 'add');
        } else if(obj.event === 'refresh'){
            window.refresh();
        } 
    });
    window.edit = function(obj){
        layer_iframe('修改', 'edit?id=' + obj.data.id);
    }
    window.remove = function(obj){
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
                    layer.msg(res.msg, {icon: 1, time: 1500}, function () {
                        obj.del();
                    })
                }
                window.tableIns = laytable.render(window.options);
            }
        });
    }
})
});