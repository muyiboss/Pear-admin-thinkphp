<?php
declare (strict_types = 1);

namespace app\admin\controller;

use think\facade\View;
use think\facade\Db;
use app\admin\model\Permission as PermissionModel;
use app\admin\validate\Permission as PermissionValidate;
use app\admin\model\Multi;
class Permission extends Base
{
    /**
     * 列表
     */
    public function index()
    {
        if ($this->isAjax) {
            $list = PermissionModel::order('id','desc')->order('sort','desc')->select();
            $this->returnApi('', 0, $list->toArray(),['count' => $list->count()]);
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
            $validate = new PermissionValidate;
            if(!$validate->check($data)) $this->returnApi($validate->getError(),0);
            try {
                PermissionModel::create($data);
            }catch (\Exception $e){
                $this->returnApi('添加失败',0, $e->getMessage());
            }
            $this->returnApi('添加成功');
        }
        return View::fetch('', [
            'permissions' => get_tree(PermissionModel::order('sort','asc')->select()->toArray())
        ]);
    }

    /**
     * 编辑
     */
    public function edit()
    {
        $permission = PermissionModel::find($this->get['id']);
        if ($this->isAjax) {
            $data = $this->post;
            $validate = new PermissionValidate;
            if(!$validate->check($data)) $this->returnApi($validate->getError(),0);
            try {
                $permission->save($data);
            }catch (\Exception $e){
                $this->returnApi('更新失败',0, $e->getMessage());
            }
            $this->returnApi('更新成功');
        }
        return View::fetch('',[
            'model' => $permission,
            'permissions' => get_tree(PermissionModel::order('sort','asc')->select()->toArray())
        ]);
    }

    /**
     * 删除
     */
    public function del()
    {
        $id = $this->post['id'];
        $permission = PermissionModel::with('child')->find($id);
        if ($permission->isEmpty()){
            $this->returnApi('数据不存在',0);
        }
        if (isset($permission->child) && !$permission->child->isEmpty()){
            $this->returnApi('存在子权限，禁止删除',0);
        }
        //开启事务删除
        Db::startTrans();
        try{
            Db::name('admin_role_permission')->where('permission_id',$id)->delete();
            Db::name('admin_admin_permission')->where('permission_id',$id)->delete();
            Db::name('admin_permission')->delete($id);
            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            $this->returnApi('删除失败',0, $e->getMessage());
        }
        $this->returnApi('删除成功');
    }
   
}
