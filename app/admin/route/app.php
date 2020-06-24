<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::miss(function() {
    return '404 Not Found!';
});

//后台路由
Route::group('/', function(){
    Route::rule('login','login/index');//后台登录页面   
    Route::get('verify/[:config]','login/verify');//获取验证码
});

//后台路由中间件
Route::group('/', function(){
    Route::get('/','index/index');//首页
    Route::get('/menu','index/menu');//菜单
    Route::get('main','index/main');//控制台
    Route::get('cache','index/cache');//清理缓存
    Route::get('logout','login/logout');//退出登录
    Route::post('uploads','index/upload');//通用上传

    // +----------------------------------------------------------------------
    // | 管理员
    // +----------------------------------------------------------------------
    Route::get("admin/index",'admin/index');//管理列表
    Route::post("admin/status",'admin/status');//禁用，启用 
    Route::post("admin/del",'admin/del');//删除
    Route::rule("admin/add",'admin/add');//新增
    Route::rule("admin/edit/[:id]",'admin/edit');//修改
    Route::rule("admin/role/[:id]",'admin/role');//角色
    Route::rule("admin/permission/[:id]",'admin/permission');//直接权限
    Route::get("admin/log",'admin/log');//管理日志

    // +----------------------------------------------------------------------
    // | 角色
    // +----------------------------------------------------------------------
    Route::get("role/index",'role/index');//角色列表
    Route::post("role/del",'role/del');//删除
    Route::rule("role/add",'role/add');//新增
    Route::rule("role/edit/[:id]",'role/edit');//修改
    Route::rule("role/permission/[:id]",'role/permission');//分配权限

    // +----------------------------------------------------------------------
    // | 权限
    // +----------------------------------------------------------------------
    Route::get("permission/index",'permission/index');//权限列表
    Route::post("permission/del",'permission/del');//删除
    Route::rule("permission/add",'permission/add');//新增
    Route::rule("permission/edit/[:id]",'permission/edit');//编辑
    
    // +----------------------------------------------------------------------
    // | 系统管理
    // +----------------------------------------------------------------------
    Route::rule('site/index','site/index','GET|POST');//列表
})->middleware(['AdminAuth','AdminPermission']);