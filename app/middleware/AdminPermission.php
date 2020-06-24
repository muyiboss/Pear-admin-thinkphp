<?php

namespace app\middleware;
use think\facade\Session;

class AdminPermission
{
    public function handle($request, \Closure $next)
    {
        //超级管理员不需要验证
        $admin = Session::get('key');
        if ($admin['id'] == 1){
            return $next($request);
        }
        //验证权限
        $rule = (array)$request->rule();
        $url = rtrim(str_replace('//','',strip_tags('/'.$rule["\0*\0rule"])),'/');
        $permissions = Session::get('permission');
        $tag = false;
        foreach ($permissions as $permission) {
            //排除验证路由
            $exp = ['','/main','/cache','/logout','/menu'.'/uploads'];
            if ($permission['href'] == $url || in_array($url,$exp)) {
                $tag = true;
                break;
            }
        }
        if ($tag === false) {
            return json(['code'=>0,'msg'=>'无权限访问']);
        }
        return $next($request);
    }
}
