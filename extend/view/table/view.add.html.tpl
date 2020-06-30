{extend name="/base"}
{block name="body"}
    <form class="layui-form  edit-form" action="" >
        <div class="mainBox">
            <div class="main-container">
                <div class="main-container">
                {{$columns}}
                </div>
            </div>
        </div>
        <div class="bottom">
            <div class="button-container">
                <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="" lay-filter="apiform">
                    <i class="layui-icon layui-icon-ok"></i>
                    提交
                </button>
                <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">
                    <i class="layui-icon layui-icon-refresh"></i>
                    重置
                </button>
            </div>
        </div>
    </form>
    {/block}