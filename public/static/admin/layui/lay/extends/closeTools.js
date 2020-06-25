 layui.define(['layer', 'jquery'], function (exports) {
        "use strict";
        var MOD_NAME = 'closeTools',
        layer = layui.layer,
        $ = layui.jquery;
        
        var closeTools = {
        /**
         * layer弹出iframe层
         * @param  string     title  标题
         * @param  string     url    iframe层url
         * @param  number     width  弹出层宽度
         * @param  number     height 弹出层高度
         */
        layer_iframe_close:function(time, refresh) {
            if (typeof time !== 'number') {
                time = 0;
            }
            setTimeout(function() {
                if (refresh) {
                    if (parent.window.tableIns) {
                        parent.window.tableIns.reload(parent.window.options);
                    } else {
                        parent.location.reload();
                    }
                }
                parent.layer.close(parent.layer.getFrameIndex(window.name));
            }, time);
        }
    };
	exports(MOD_NAME, closeTools);
})
