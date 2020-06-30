<?php
use think\facade\Route;
Route::group('/', function(){
    // +----------------------------------------------
    // |                     CMS管理                  |
    // +----------------------------------------------
    //新闻
    Route::get('home/news/index','home.news/index');//列表
    Route::rule('home/news/add','home.news/add','GET|POST');//添加 
    Route::rule('home/news/edit','home.news/edit','GET|POST');//编辑 
})->middleware(['AdminCheck','AdminPermission']);