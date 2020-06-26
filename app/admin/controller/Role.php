<?php
declare (strict_types = 1);

namespace app\admin\controller;

use think\facade\View;
use think\facade\Db;
use app\admin\model\Permission;
use app\admin\model\Role as RoleModel;
use app\admin\validate\Role as RoleValidate;
class Role extends Base
{
    public function index()
    {
        if ($this->request->isAjax()) {
            $list = RoleModel::order('id','desc')->paginate($this->request->get('limit'));
            $this->returnApi('', 0, $list->items(),['count' => $list->total(), 'limit' => $this->request->get('limit')]);
        }
        return View::fetch();
    }

    public function add()
    {
        if ($this->request->isAjax()){
            $data = $this->request->param();
            $validate = new RoleValidate;
            if (!$validate->check($data)) {
                $this->returnApi($validate->getError(),0);
            }
            try{
                RoleModel::create($data);
            }catch (\Exception $e){
                $this->returnApi('新增失败',0);
            }
            $this->returnApi('新增成功');
        }
        return View::fetch();
    }

    public function edit()
    {
        $id = $this->request->param('id');
        $role = RoleModel::find($id);
        if ($this->request->isAjax()){
            $data = $this->request->param();
            $validate = new RoleValidate;
            if (!$validate->check($data)) {
                $this->returnApi($validate->getError(),0);
            }
            try{
                $role->name = $data['name'];
                $role->desc = $data['desc'];
                $role->save();
            }catch (\Exception $e){
                $this->returnApi('修改失败',0);
            }
            $this->returnApi('修改成功');
        }
        return View::fetch('',[
            'model' => $role,
        ]);
    }

    public function del()
    {
        $id = $this->request->param('id');
        $role = RoleModel::find($id);
        if ($role->isEmpty()){
            $this->returnApi('角色不存在',0);
        }
        Db::startTrans();
        try{
            event('PermissionRm');
            Db::name('role_permission')->where('role_id',$role['id'])->delete();
            $role->delete();
            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            $this->returnApi('删除失败',0);
        }
        $this->returnApi('删除成功');
    }

    public function permission()
    {
        $id = $this->request->param('id');
        $role = RoleModel::with('permissions')->find($id);
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
        if ($this->request->isAjax()){
            $data = $this->request->param('permissions',[]);
            Db::startTrans();
            try{
                event('PermissionRm');
                //清除角色的原有权限
                Db::name('role_permission')->where('role_id',$id)->delete();
                //添加角色权限
                foreach ($data as $v){
                    Db::name('role_permission')->insert([
                        'role_id' => $id,
                        'permission_id' => $v,
                    ]);
                }
                Db::commit();
            }catch (\Exception $e){
                Db::rollback();
                $this->returnApi('修改失败',0);
            }
            $this->returnApi('修改成功');
        }
        return View::fetch('',[
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

}
