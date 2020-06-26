<?php
declare (strict_types = 1);

namespace app\admin\controller;

use think\facade\View;
use think\facade\Db;
use app\admin\model\Permission as PermissionModel;
use app\admin\validate\Permission as PermissionValidate;
class Permission extends Base
{
    public function index()
    {
        
        if ($this->request->isAjax()) {
            $list = PermissionModel::order('id','asc')->order('sort','desc')->select();
            $this->returnApi('', 0, $list->toArray(),['count' => $list->count()]);
        }
        return View::fetch();
    }

    public function add()
    {
        $permissions = get_tree(PermissionModel::order('sort','asc')->select()->toArray());
        if ($this->request->isAjax()){
            $data = $this->request->param();
            $validate = new PermissionValidate;
            if (!$validate->check($data)) {
                $this->returnApi($validate->getError(),0);
            }
            try{
                event('PermissionRm');
                PermissionModel::create($data);
            }catch (\Exception $e){
                $this->returnApi('新增失败',0);
            }
            $this->returnApi('新增成功');
        }
        return View::fetch('',[
            'permissions' => $permissions,
        ]);
    }

    public function edit()
    {
        $id = $this->request->param('id');
        $permission = PermissionModel::find($id);
        $permissions = get_tree(PermissionModel::order('sort','asc')->select()->toArray());
        if ($this->request->isAjax()){
            $data = $this->request->param();
            $validate = new PermissionValidate;
            if (!$validate->check($data)) {
                $this->returnApi($validate->getError(),0);
            }
            try{
                event('PermissionRm');
                $permission->save($data);
            }catch (\Exception $e){
                $this->returnApi('修改失败',0);
            }
            $this->returnApi('修改成功');
        }
        return View::fetch('',[
            'model' => $permission,
            'permissions' => $permissions,
        ]);
    }

    public function del()
    {
        $id = $this->request->param('id');
        $permission = PermissionModel::with('children')->find($id);
        if ($permission->isEmpty()){
            $this->returnApi('数据不存在',0);
        }
        if (isset($permission->child) && !$permission->child->isEmpty()){
            $this->returnApi('存在子权限，禁止删除',0);
        }
        //开启事务删除
        Db::startTrans();
        try{
            event('PermissionRm');
            Db::name('role_permission')->where('permission_id',$id)->delete();
            Db::name('admin_permission')->where('permission_id',$id)->delete();
            Db::name('permission')->delete($id);
            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            $this->returnApi('删除失败',0);
        }
        $this->returnApi('删除成功');
    }

}
