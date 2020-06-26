<?php
declare (strict_types = 1);

namespace app\admin\controller;

use think\facade\View;
use think\facade\Db;
use app\admin\model\Admin as AdminModel;
use app\admin\validate\Admin as AdminValidate;
use app\admin\model\AdminLog as AdminLog;
use app\admin\model\Role;
use app\admin\model\Permission;
class Admin extends Base
{
    public function index()
    {
        if ($this->request->isAjax()) {
            $where = [];
            //按用户名
            if ($search = input('get.username')) {
               $where[] = ['username', 'like', "%" . $search . "%"];
            }
            $list = AdminModel::order('id','desc')->withoutField('password,salt')->where($where)->paginate($this->request->get('limit'));
            $this->returnApi('', 0, $list->items(),['count' => $list->total(), 'limit' => $this->request->get('limit')]);
        }
        return View::fetch();
    }

    public function add()
    {
        if ($this->request->isAjax()) {
            $data = $this->request->post();
            $validate = new AdminValidate;
            if (!$validate->check($data)) {
                $this->returnApi($validate->getError(),0);
            }
            try {
                $salt = random(16, 0);
                $password = md5(md5($data['password']) . $salt);
                AdminModel::create(array_merge($data, [
                    'password' => $password,
                    'salt' => $salt,
                ]));
            } catch (\Exception $e) {
                $this->returnApi('新增失败',0);
            }
            $this->returnApi('新增成功');
        }
        return View::fetch();
    }

    public function edit()
    {
        //查询当前用户，不存在则抛出异常
        $id = $this->request->param('id');
        $admin = AdminModel::find($id);
        if ($this->request->isAjax()) {
            $data = $this->request->post();
            $data['id'] = $admin['id'];
            $validate = new AdminValidate;
            if (!$validate->scene('edit')->check($data)) {
                $this->returnApi($validate->getError(),0);
            }
            //是否需要修改密码
            if ($data['password']) {
                $admin->salt = random(16, 0);
                $admin->password = md5(md5($data['password']) . $admin->salt);
            }
            $admin->username = $data['username'];
            $admin->nickname = $data['nickname'];
            try {
                $admin->save();
            } catch (\Exception $e) {
                $this->returnApi('修改失败',0);
            }
            $this->returnApi('修改成功');
        }
        return View::fetch('',[
            'model' => $admin,
        ]);
    }

    public function del()
    {
        $ids = $this->request->param('ids');
        if (!is_array($ids)){
            $this->returnApi('参数错误',0);
        }
        try{
            AdminModel::destroy($ids);
        }catch (\Exception $e){
            $this->returnApi('删除失败',0);
        }
        $this->returnApi('删除成功');
    }

    public function status()
    {
        $id = $this->request->param('id');
        $status = $this->request->param('status');
        if (!in_array($status,[0,1])){
            $this->returnApi('参数错误',0);
        }
        $admin = AdminModel::find($id);
        if ($admin->isEmpty()){
            $this->returnApi('数据不存在',0);
        }
        try{
            $admin->status=$status;
            $admin->save();
        }catch (\Exception $e){
            $this->returnApi('修改失败',0);
        }
        $this->returnApi('修改成功');
    }

    public function role()
    {
        $id = $this->request->param('id');
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
        if ($this->request->isAjax()){
            $data = $this->request->param('roles',[]);
            Db::startTrans();
            try{
                event('PermissionRm');
                //清除原先的角色
                Db::name('admin_role')->where('admin_id',$id)->delete();
                //添加新的角色
                foreach ($data as $v){
                    Db::name('admin_role')->insert([
                        'admin_id' => $id,
                        'role_id' => $v,
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
            'admin' => $admin,
            'roles' => $roles,
        ]);
    }

    public function permission()
    {
        $id = $this->request->param('id');
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

        if ($this->request->isAjax()){
            $data = $this->request->param('permissions',[]);
            Db::startTrans();
            try{
                event('PermissionRm');
                //清除原有的直接权限
                Db::name('admin_permission')->where('admin_id',$id)->delete();
                //填充新的直接权限
                foreach ($data as $p){
                    Db::name('admin_permission')->insert([
                        'admin_id' => $id,
                        'permission_id' => $p,
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
            'admin' => $admin,
            'permissions' => $permissions,
        ]);
    }

    public function log()
    {
        if ($this->request->isAjax()) {
            $where = [];
            //按名称
            if ($search = input('get.username')) {
               $where[] = ['username', 'like', "%" . $search . "%"];
            }
            $list = AdminLog::order('id','desc')->where($where)->paginate($this->request->get('limit', 30));
            $this->returnApi('', 0, $list->items(),['count' => $list->total(), 'limit' => $this->request->get('limit', 30)]);
        }
        return View::fetch();
    }

}