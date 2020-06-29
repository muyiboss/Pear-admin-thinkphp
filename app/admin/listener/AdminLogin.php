<?php

namespace app\admin\listener;

use app\admin\model\AdminLog;

class AdminLogin
{
    public function handle()
    {
        // 事件监听处理
        AdminLog::record();
    }
}