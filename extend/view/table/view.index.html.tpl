{extend name="/base"}
{block name="body"}
<div class="layui-card">
    <div class="layui-card-body">
        <table id="dataTable" lay-filter="dataTable"></table>
    </div>
</div>
<script type="text/html" id="lay-toolbar">
    <button class="pear-btn pear-btn-primary pear-btn-md" lay-event="add"> 
        <i class="layui-icon layui-icon-add-1"></i> 
        新增 
    </button>
</script>

<script type="text/html" id="options">
    <a class="pear-btn pear-btn-primary pear-btn-sm" lay-event="edit">编辑</a>
    <a class="pear-btn pear-btn-danger pear-btn-sm" lay-event="del">删除</a>
</script>
{/block}
{block name="js"}
<script src="__ADMIN__/module/{{$multi}}/{{$tn}}/index.js"></script>
{/block}
