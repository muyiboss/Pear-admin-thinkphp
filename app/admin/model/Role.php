<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;

/**
 * @mixin think\Model
 */
class Role extends Model
{
    protected $table = 'role';

    /**
     * 角色所有的权限
     * @return \think\model\relation\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany('Permission','role_permission','permission_id','role_id');
    }
}
