<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;

/**
 * @mixin think\Model
 */
class AdminLog extends Model
{
    protected $table = 'admin_log';
}
