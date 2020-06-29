<?php
declare (strict_types = 1);

namespace app\admin\controller;

use think\facade\View;
use think\facade\Db;
use app\admin\model\Admin as AdminModel;
use app\admin\validate\Admin as AdminValidate;
use app\admin\model\Role;
use app\admin\model\Permission;
class Admin extends Base
{
    public function index()
    {
        if ($this->isAjax) {
            $where = [];
            //按用户名
            if ($search = input('get.username')) {
               $where[] = ['username', 'like', "%" . $search . "%"];
            }
            $list = AdminModel::order('id','desc')->where('id','>','1')->where($where)->paginate($this->get['limit']);
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
            //验证
            $validate = new AdminValidate();
            if(!$validate->scene('add')->check($data)) $this->returnApi($validate->getError(),0);
            try {
                $password =  set_password($data['password']);
                AdminModel::create(array_merge($data, [
                    'password' => $password,
                ]));
            }catch (\Exception $e){
                $this->returnApi('添加失败',0, $exception->getMessage());
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
        $admin = AdminModel::find($this->get['id']);
        if ($this->isAjax) {
            $data = $this->post;
            $data['id'] = $admin['id'];
            //验证
            $validate = new AdminValidate();
            if(!$validate->scene('edit')->check($data)) $this->returnApi($validate->getError(),0);
            //是否需要修改密码
            if ($data['password']) $admin->password = set_password($data['password']);
            $admin->username = $data['username'];
            $admin->nickname = $data['nickname'];
            try {
                $admin->save();
            }catch (\Exception $e){
                $this->returnApi('更新失败',0, $e->getMessage());
            }
            $this->returnApi('更新成功');
        }
        return View::fetch('',[
            'data' => $admin
        ]);
    }

    /**
     * 用户分配角色
     */
    public function role()
    {
        $id = $this->get['id'];
        $admin = AdminModel::with('roles')->where('id',$id)->find();
        $roles = Role::select();
        foreach ($roles as $k=>$role){
            if (isset($admin->roles) && !$admin->roles->isEmpty()){
                foreach ($admin->roles as $v){
                    if ($role['id']==$v['id']){
                        $roles[$k]['own'] = true;
                    }
                }
            }
        }
        if ($this->isAjax){
            if(!isset($this->post['roles']))  $this->returnApi('至少选择一项',0);
            Db::startTrans();
            try{
                //清除原先的角色
                Db::name('admin_admin_role')->where('admin_id',$id)->delete();
                //添加新的角色
                foreach ($this->post['roles'] as $v){
                    Db::name('admin_admin_role')->insert([
                        'admin_id' => $admin['id'],
                        'role_id' => $v,
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
            'admin' => $admin,
            'roles' => $roles,
        ]);
    }

    public function permission()
    {
        $id = $this->get['id'];
        $admin = AdminModel::with('directPermissions')->find($id);
        $permissions = Permission::order('sort','asc')->select();
        foreach ($permissions as $permission){
            foreach ($admin->direct_permissions as $p){
                if ($permission->id==$p['id']){
                    $permission->own = true;
                }
            }
        }
        $permissions = get_tree($permissions->toArray());
        if ($this->isAjax){
            if(!isset($this->post['permissions']))  $this->returnApi('至少选择一项',0);
            Db::startTrans();
            try{
                //清除原有的直接权限
                Db::name('admin_admin_permission')->where('admin_id',$id)->delete();
                //填充新的直接权限
                foreach ($this->post['permissions'] as $p){
                    Db::name('admin_admin_permission')->insert([
                        'admin_id' => $id,
                        'permission_id' => $p,
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
            'admin' => $admin,
            'permissions' => $permissions,
        ]);
    }

    public function del()
    {
        $id = $this->post['id'];
        $admin = AdminModel::find($id);
        if($admin){
            Db::startTrans();
            try{
                Db::name('admin_admin_permission')->where('admin_id',$admin['id'])->delete();
                $admin->delete();
                Db::commit();
            }catch (\Exception $e){
                Db::rollback();
                $this->returnApi('删除失败',0, $e->getMessage());
            }
            $this->returnApi('删除成功');
        }
    }
}
