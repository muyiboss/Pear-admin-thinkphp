$(function() {
    layui.use(['pearAdmin', 'jquery', 'pearSocial', 'layer','element'], function() {
        var pearAdmin = layui.pearAdmin;
        var $ = layui.jquery;
        var layer = layui.layer;
        var layelem = layui.element;
        var config = {
            muiltTab: true, // 是 否 开 启 多 标 签 页 true 开启 false 关闭
            control: false, // 是 否 开 启 多 系 统 菜 单 true 开启 false 关闭
            theme: "dark-theme", // 默 认 主 题 样 式 dark-theme 默认主题 light-theme 亮主题
            index: '/index/home', // 默 认 加 载 主 页
            data: '/menu', // 菜 单 数 据 加 载 地 址
            select: '0',                 // 默 认 选 中 菜 单 项
        };
        pearAdmin.render(config);
        layelem.on('nav(layui_nav_right)', function(elem) {
            if ($(elem).hasClass('logout')) {
                layer.confirm('确定退出登录吗?', function(index) {
                    layer.close(index);
                    _ajax({
                        url: '/login/logout',
                        success: function(res) {
                            if (res.code==1) {
                                layer.msg(res.msg, {
                                    icon: 1
                                });
                                setTimeout(function() {
                                    location.href = '/login/index';
                                }, 333)
                            }
                        }
                    });
                });
            }else if ($(elem).hasClass('password')) {
                layer_iframe('修改密码', '/index/pass',300,300);
            }else if ($(elem).hasClass('cache')) {
                $.post('/index/cache',
                function(data){
                    layer.msg(data.msg, {time: 1500});
                     location.reload()
                });
    
            }
    
        });
    })
});