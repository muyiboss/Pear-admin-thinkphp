<?php
declare (strict_types = 1);

namespace app\middleware;
use app\admin\model\Admin;
use app\admin\model\AdminLog;
class AdminCheck
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
            return redirect('/login/index');
         }
         AdminLog::record();
         return $next($request);
    }
}
