<?php
declare (strict_types = 1);

namespace app\admin\controller;

use think\facade\View;
use think\facade\Db;
use app\admin\model\Role as RoleModel;
use app\admin\validate\Role as RoleValidate;
use app\admin\model\Permission;
class Role extends Base
{
    /**
     * 列表
     */
    public function index()
    {
        if ($this->isAjax) {
            $list = RoleModel::order('id','desc')->paginate($this->get['limit']);
            $this->returnApi('', 0, $list->items(), ['count' => $list->total(), 'limit' => $this->get['limit']]);
        }
        return View::fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->isAjax) {
            $data = $this->post;
            $validate = new RoleValidate;
            if(!$validate->check($data)) $this->returnApi($validate->getError(),0);
            try {
                RoleModel::create($data);
            }catch (\Exception $e){
                $this->returnApi('添加失败',0, $e->getMessage());
            }
            $this->returnApi('添加成功');
        }
        return View::fetch();
    }

    /**
     * 编辑
     */
    public function edit()
    {
        $role = RoleModel::find($this->get['id']);
        if ($this->isAjax) {
            $data = $this->post;
            $validate = new RoleValidate;
            if(!$validate->check($data)) $this->returnApi($validate->getError(),0);
            try {
                $role->name = $data['name'];
                $role->desc = $data['desc'];
                $role->save();
            }catch (\Exception $e){
                $this->returnApi('更新失败',0, $e->getMessage());
            }
            $this->returnApi('更新成功');
        }
        return View::fetch('',[
            'data' => $role
        ]);
    }

    /**
     * 删除
     */
    public function del()
    {
        $id = $this->post['id'];
        $role = RoleModel::find($id);
        if($role){
            Db::startTrans();
            try{
                Db::name('admin_admin_role')->where('role_id',$id)->delete();
                Db::name('admin_role_permission')->where('role_id',$id)->delete();
                $role->delete();
                Db::commit();
            }catch (\Exception $e){
                Db::rollback();
                $this->returnApi('删除失败',0, $e->getMessage());
            }
            $this->returnApi('删除成功');
        }
    }

    /**
     * 分配权限
     */
    public function permission()
    {
        $role = RoleModel::with('permissions')->find($this->get['id']);
        $permissions = Permission::order('sort','asc')->select();
        foreach ($permissions as $permission){
            if (isset($role->permissions) && !$role->permissions->isEmpty()){
                foreach ($role->permissions as $p){
                    if ($permission->id==$p->id){
                        $permission->own = true;
                    }
                }
            }
        }
        $permissions = get_tree($permissions->toArray());
        if ($this->isAjax){
            if(!isset($this->post['permissions'])) $this->returnApi('至少选择一项',0);
            Db::startTrans();
            try{
                //清除角色的原有权限
                Db::name('admin_role_permission')->where('role_id',$this->get['id'])->delete();
                //添加角色权限
                foreach ($this->post['permissions'] as $v){
                    Db::name('admin_role_permission')->insert([
                        'role_id' => $this->get['id'],
                        'permission_id' => $v,
                    ]);
                }
                Db::commit();
            }catch (\Exception $e){
                Db::rollback();
                $this->returnApi('更新失败',0, $e->getMessage());
            }
            $this->returnApi('更新成功');
        }
        return View::fetch('',[
            'permissions' => $permissions,
            'role' => $role,
        ]);
    }
   
}
