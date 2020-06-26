<?php
declare (strict_types = 1);

namespace app\admin\listener;
use think\facade\Session;
class PermissionRm
{
    /**
     * 事件监听处理
     *
     * @return mixed
     */
    public function handle($event)
    {
        Session::delete('permission');
    }    
}
