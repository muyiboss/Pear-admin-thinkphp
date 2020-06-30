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
// 加载多级路由
include app_path().'/route/multi.php';
Route::miss(function() {
    return '404';
});

//后台路由
Route::group('/', function(){
    Route::rule('login','login/index');//后台登录页面   
    Route::get('verify/[:config]','login/verify');//获取验证码
});

// +----------------------------------------------
// |                     后台路由                 |
// +----------------------------------------------
Route::group('/', function(){
    Route::rule('login/index','login/index','GET|POST');//后台登录页面   
    Route::get('login/verify','login/verify');//获取验证码
    Route::post('login/logout','login/logout');//退出登录
});

// +----------------------------------------------
// |                     后台中间                 |
// +----------------------------------------------
Route::group('/', function(){
    Route::get('/','index/index');//后台页面   
    Route::get('menu','index/menu');//菜单   
    Route::get('index/index','index/index');//后台页面 
    Route::get('index/home','index/home');//欢迎页  
    Route::rule('index/pass','index/pass','GET|POST');//修改密码
    Route::post('index/cache','index/cache','POST');//清理缓存
})->middleware("AdminCheck");

// +----------------------------------------------
// |                     通用                     |
// +----------------------------------------------
Route::group('/', function(){
    Route::post('api/update','api/update');//通用更新 
    Route::post('api/remove','api/remove');//通用删除   
    Route::post('api/removes','api/removes');//通用批量删除   
    Route::post('api/upload','api/upload');//通用上传 
})->middleware("AdminCheck");

// +----------------------------------------------
// |                     系统                     |
// +----------------------------------------------
Route::group('/', function(){
    // 管理员
    Route::get('admin/index','admin/index');//列表
    Route::rule('admin/add','admin/add','GET|POST');//添加 
    Route::rule('admin/edit','admin/edit','GET|POST');//编辑 
    Route::rule('admin/role','admin/role','GET|POST');//分配角色
    Route::rule('admin/permission','admin/permission','GET|POST');//直接权限
    Route::post('admin/del','admin/del');//删除
    // 角色管理  
    Route::get('role/index','role/index');//列表
    Route::rule('role/add','role/add','GET|POST');//添加 
    Route::rule('role/edit','role/edit','GET|POST');//编辑 
    Route::rule('role/permission','role/permission','GET|POST');//分配权限 
    Route::post('role/del','role/del');//删除
    // 菜单权限 
    Route::get('permission/index','permission/index');//列表
    Route::rule('permission/add','permission/add','GET|POST');//添加 
    Route::rule('permission/edit','permission/edit','GET|POST');//编辑 
    Route::post('permission/del','permission/del');//删除
    // 系统管理
    Route::rule('config/log','config/log','GET|POST');//列表
    Route::rule('config/index','config/index','GET|POST');//列表
    Route::get('config/file','config/file');//文件管理
    Route::post("config/fileDel",'config/fileDel');//删除
    Route::get("config/fileAdd",'config/fileAdd');//添加 
    // 多级控制
    Route::get('multi/index','multi/index');//列表
    Route::rule('multi/add','multi/add','GET|POST');//添加 
    Route::rule('multi/edit','multi/edit','GET|POST');//编辑 
    Route::post('multi/del','multi/del');//删除
    // 代码生成
    Route::rule('gen/index','gen/index','GET|POST');//列表
    Route::rule('gen/preview','gen/preview','GET|POST');//预览
})->middleware(['AdminCheck','AdminPermission']);