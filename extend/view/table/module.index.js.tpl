$(function() {
    $('.layui-plus').click(function() {
        layer_iframe('添加{{$tn_cn}}', 'add');
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
            [{{$columns}}{
                title: '操作',
                unresize: true,
                align: 'center',
                toolbar: '#options'
            }]
        ],
    };
    window.tableIns = laytable.render(window.options);
     laytable.on('tool(dataTable)', function(obj) {
        if (obj.event === 'edit') {
            layer_iframe('编辑{{$tn_cn}}', 'edit?id=' + obj.data.id);
        } else if (obj.event === 'del') {
            _ajax_remove('确定删除该{{$tn_cn}}吗?', '{{$multi}}_{{$tn}}', obj.data.id);
        }
    });

  laytable.on('toolbar(dataTable)', function(obj){
        if(obj.event === 'add'){
            layer_iframe('新增{{$tn_cn}}', 'add');
        } 
    });
});
