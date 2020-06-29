/**
 * layer 弹出图片层
 * @param  string src 图片路径
 */
function show_big_image(src) {
    var width, height,
        max_width = $(document).width() * 0.8,
        max_height = $(document).height() * 0.8,
        img = new Image();
    img.src = src;
    if (img.complete) {
        width = img.width;
        height = img.height;
        while (width >= max_width || height >= max_height) {
            width *= 0.8;
            height *= 0.8;
        }
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            shadeClose: true,
            area: [width + 'px', height + 'px'],
            content: '<img class="show-big-image" src="' + src + '">'
        });
    }
}
/**
 * layer iframe层关闭自身
 * @param  number     time    延迟操作时间
 * @param  boolean    refresh 是否刷新父页面
 */
function layer_iframe_close(time, refresh) {
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
/**
 * layer弹出iframe层
 * @param  string     title  标题
 * @param  string     url    iframe层url
 * @param  number     width  弹出层宽度
 * @param  number     height 弹出层高度
 * @param  boolean    full   是否弹出即全屏,默认弹出,任意!参数阻止
 */
function layer_iframe(title, url, width, height, full) {
    if (typeof width !== 'number' || width === 0) {
        width = $(window).width() * 0.8;
    }
    if (typeof height !== 'number' || height === 0) {
        height = $(window).height() - 20;
    }
    var index = layer.open({
        type: 2,
        title: title,
        fix: false,
        maxmin: true,
        area: [width + 'px', height + 'px'],
        content: url
    });
    if (full) {
        layer.full(index);
    }
}
/**
 * 更新数据库内容
 * @param  string         confirm 确认提示信息
 * @param  string         tn      表名
 * @param  string|object  where   条件数组或id值
 * @param  object         update  更新内容
 * @param  function       success 成功回调
 */
function _ajax_update(confirm, tn, where, update, success) {
    layer.confirm(confirm, function(index) {
        _ajax({
            url: '/api/update',
            data: {
                tn: tn,
                where: where,
                update: update
            },
            success: function(res) {
                layer.close(index);
                if (typeof success === 'function') {
                    success(res);
                } else {
                    if (res.code) {
                        layer.msg(res.msg, {
                            icon: 2
                        });
                    } else {
                        layer.msg(res.msg, {
                            icon: 1
                        });
                        setTimeout(function() {
                            window.tableIns.reload(window.options);
                        }, 333);
                    }
                }
            },
            error: function() {
                layer.close(index);
            }
        });
    });
}

function _update(confirm, url, param, success) {
    layer.confirm(confirm, function(index) {
        _ajax({
            url: url,
            data: param,
            success: function(res) {
                layer.close(index);
                if (typeof success === 'function') {
                    success(res);
                } else {
                    if (res.code) {
                        layer.msg(res.msg, {
                            icon: 2
                        });
                    } else {
                        layer.msg(res.msg, {
                            icon: 1
                        });
                        setTimeout(function() {
                            window.tableIns.reload(window.options);
                        }, 333);
                    }
                }
            },
            error: function() {
                layer.close(index);
            }
        });
    });
}
/**
 * 删除数据库内容
 * @param  string         confirm 确认提示信息
 * @param  string         tn      表名
 * @param  string|object  where   条件条件数组或id值
 * @param  function       success 成功回调
 */
function _ajax_remove(confirm, tn, where, success) {
    layer.confirm(confirm, function(index) {
        _ajax({
            url: '/api/remove',
            data: {
                tn: tn,
                where: where
            },
            success: function(res) {
                layer.close(index);
                if (typeof success === 'function') {
                    success(res);
                } else {
                    if (res.code) {
                        layer.msg(res.msg, {
                            icon: 2
                        });
                    } else {
                        layer.msg(res.msg, {
                            icon: 1
                        });
                        setTimeout(function() {
                            window.tableIns.reload(window.options);
                        }, 333);
                    }
                }
            },
            error: function() {
                layer.close(index);
            }
        });
    });
}

/**
 * 批量删除数据库内容
 * @param  string         confirm 确认提示信息
 * @param  string         tn      表名
 * @param  string|object  where   条件条件数组或id值
 * @param  function       success 成功回调
 */
function _ajax_removes(confirm, tn, where, success) {
    layer.confirm(confirm, function(index) {
        _ajax({
            url: '/api/removes',
            data: {
                tn: tn,
                where: where
            },
            success: function(res) {
                layer.close(index);
                if (typeof success === 'function') {
                    success(res);
                } else {
                    if (res.code) {
                        layer.msg(res.msg, {
                            icon: 2
                        });
                    } else {
                        layer.msg(res.msg, {
                            icon: 1
                        });
                        setTimeout(function() {
                            window.tableIns.reload(window.options);
                        }, 333);
                    }
                }
            },
            error: function() {
                layer.close(index);
            }
        });
    });
}
/**
 * ajax上传文件
 * @param  string elem    绑定元素,必传
 * @param  object options 参数(form:将要生成的form的id,name:文件字段名,accept:文件类型限制,path:上传路径,data:额外参数,success:成功回调,error:失败回调)
 */
function _ajax_upload(elem, options) {
    if (typeof elem === 'undefined') {
        return;
    }
    if (typeof options === 'undefined') {
        options = {};
    }
    var form, name = 'file',
        accept = 'image/*',
        path = 'images',
        url = '/api/upload';
    if (typeof options.name === 'string' && options.name !== '') {
        name = options.name;
        delete options.name;
    }
    if (typeof options.accept === 'string') {
        accept = options.accept;
        delete options.accept;
    }
    if (typeof options.path === 'string' && options.path !== '') {
        path = options.path;
        delete options.path;
    }
    if (typeof options.url === 'string' && options.url !== '') {
        url = options.url;
        delete options.url;
    }
    if (typeof options.form === 'string' && options.form !== '') {
        form = options.form;
        delete options.form;
    } else {
        form = Math.random().toString(36).substr(2);
    }
    var input = '<input accept="' + accept + '" name="' + name + '" type="file"/>';
    $('body').append('<form enctype="multipart/form-data" id="' + form + '" style="display: none;">' + input + '</form>');
    $('body').on('change', '#' + form + ' input', function() {
        var _this = $(this);
        var data = new FormData();
        data.append(name, _this[0].files[0]);
        data.append('name', name);
        data.append('path', path);
        if (options.data) {
            for (var i in options.data) {
                data.append(i, options.data[i]);
            }
            delete options.data;
        }
        $.extend(options, {
            contentType: false,
            data: data,
            processData: false,
            url: url
        });
        _ajax(options);
        _this.remove();
        $('#' + form).append(input);
    });
    $(elem).click(function() {
        $('#' + form + ' input').click();
    });
}
/**
 * jQuery ajax简化方法
 * @param  object options 非默认参数
 * @param  mixed  load    是否弹出layer加载层
 */
function _ajax(options, load) {
    if (typeof options === 'undefined') {
        options = {};
    }
    if (typeof load === 'undefined') {
        load = true;
    }
    var successfun, errorfun,
        load_index = null,
        settings = {
            async: true,
            cache: false,
            complete: function(xhr) {
                xhr = null;
            },
            data: null,
            dataType: 'json',
            beforeSend: function() {
                if (load) {
                    if (typeof layer_load === 'function') {
                        load_index = layer_load();
                    } else if (layer) {
                        load_index = layer.load();
                    }
                }
            },
            error: function(xhr, status, error) {
                if (load_index !== null) {
                    layer.close(load_index);
                }
            },
            success: function(result, status, xhr) {
                if (load_index !== null) {
                    layer.close(load_index);
                }
            },
            type: 'post',
            url: location.href
        };
    if (options.success) {
        successfun = function(result, status, xhr) {
            if (load_index !== null) {
                layer.close(load_index);
            }
            if (result.code === -10000) {
                if (layer) {
                    layer.msg(result.msg, {
                        icon: 2
                    });
                } else {
                    alert(result.msg);
                }
                setTimeout(function() {
                    location.href = '/admin/login/login';
                }, 666);
            } else {
                options.success(result, status, xhr);
            }
        }
    }
    if (options.error) {
        errorfun = function(xhr, status, error) {
            if (load_index !== null) {
                layer.close(load_index);
            }
            options.error(xhr, status, error);
        }
    }
    $.extend(settings, options);
    if (typeof successfun === 'function') {
        $.extend(settings, {
            success: successfun
        });
    }
    if (typeof errorfun === 'function') {
        $.extend(settings, {
            error: errorfun
        });
    }
    $.ajax(settings);
}
/**
 * 阻止冒泡
 */
function stopPropagation(e) {
    if (e.stopPropagation) {
        e.stopPropagation();
    } else {
        e.cancelBubble = true;
    }
}
