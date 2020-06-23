 layui.define(['jquery','layer'], function (exports) {
        "use strict";
        var MOD_NAME = 'iframeTools',
        layer = layui.layer,
        $ = layui.jquery;
        
        var iframeTools = {
        /**
         * layer弹出iframe层
         * @param  string     title  标题
         * @param  string     url    iframe层url
         * @param  number     width  弹出层宽度
         * @param  number     height 弹出层高度
         */
        layer_iframe:function(title, url, width, height) {
                if (typeof width !== 'number' || width === 0) {
                    width = $(window).width() * 0.9;
                }
                if (typeof height !== 'number' || height === 0) {
                    height = $(window).height() - 100;
                }
            layer.open({
                    type: 2,
                    title: title,
                    fix: false,
                    maxmin: true,
                    area: [width + 'px', height + 'px'],
                    content: url
                });
            }
        };
	exports(MOD_NAME, iframeTools);
})
