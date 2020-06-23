<?php
declare (strict_types = 1);

namespace app\middleware;
use app\admin\model\Admin;
class AdminAuth
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
        //验证登录
        if ((new Admin())->isLogin()==false) {
                return redirect('/login');
        }
        return $next($request);
    }
}
