<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Role extends Model
{
    protected $table = 'admin_role';
    /**
     * 角色所有的权限
     */
    public function permissions()
    {
        return $this->belongsToMany('Permission','admin_role_permission','permission_id','role_id');
    }

}
