<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;

/**
 * @mixin think\Model
 */
class Permission extends Model
{
    protected $table = 'permission';

    /**
     * 子权限
     * @return \think\model\relation\HasMany
     */
    public function children()
    {
        return $this->hasMany('Permission','pid','id');
    }
    
}
