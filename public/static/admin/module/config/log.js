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
                field: 'id',
                title: 'ID',
                unresize: true,
                align: 'center'
            }, {
                field: 'uid',
                title: '管理员ID',
                unresize: true,
                align: 'center'
            }, {
                field: 'url',
                title: '操作页面',
                unresize: true,
                align: 'center'
            }, {
                field: 'ip',
                title: '操作IP',
                unresize: true,
                align: 'center'
            }, {
                field: 'user_agent',
                title: 'User-Agent',
                unresize: true,
                align: 'center'
            }, {
                field: 'create_at',
                title: '创建时间',
                unresize: true,
                align: 'center'
            }]
        ],
        defaultToolbar: [{
            layEvent: 'refresh',
            icon: 'layui-icon-refresh',
        }, 'filter', 'print', 'exports']
    };
    window.tableIns = laytable.render(window.options);
    laytable.on('toolbar(dataTable)', function(obj){
            if(obj.event === 'refresh'){
                refresh();
            } 
    });
    layform.on('submit(query)', function(data){
        laytable.reload('dataTable',{where:data.field})
            return false;
    });
    refresh = function(param){
        laytable.reload('dataTable');
    }
});
