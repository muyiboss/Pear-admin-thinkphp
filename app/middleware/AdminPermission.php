<?php
declare (strict_types = 1);

namespace app\middleware;
use think\facade\Session;
class AdminPermission
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        //超级管理员不需要验证
        $admin = Session::get('admin');
        if ($admin['id'] == 1){
            return $next($request);
        }
        //验证权限
        $rule = (array)$request->rule(); 
        $url = rtrim(str_replace('//','',strip_tags('/'.$rule["\0*\0rule"])),'/');
        $permissions = Session::get('key');
        $tag = false;
        foreach ($permissions as $permission) {
            //验证路由
            if ($permission['url'] == $url) {
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
