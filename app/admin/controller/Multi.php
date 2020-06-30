<?php
declare (strict_types = 1);

namespace app\admin\controller;

use think\facade\View;
use think\facade\Db;
use app\admin\model\Multi as MultiModel;
class Multi extends Base
{
    /**
     * 列表
     */
    public function index()
    {
        if ($this->isAjax) {
            $list = MultiModel::order('id','desc')->where('id','>','1')->paginate($this->get['limit']);
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
            try {
                MultiModel::create($data);
                $info = self::multiPath($data['url']);
                foreach($info as $k=>$v){
                    @mkdir($v);
                }
                //基础控制器
                @file_put_contents($info['controller'].'/Base.php', str_replace(['{{$multi}}'], [$data['url']], file_get_contents(self::multiBase())));
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
        $multi = MultiModel::find($this->get['id']);
        if ($this->isAjax) {
            $data = $this->post;
            try {
                $multi->save($data);
            }catch (\Exception $e){
                $this->returnApi('更新失败',0, $e->getMessage());
            }
            $this->returnApi('更新成功');
        }
        return View::fetch('',[
            'data' => $multi
        ]);
    }

    /**
     * 删除
     */
    public function del()
    {
        $id = $this->post['id'];
        $multi = MultiModel::find($id);
        if($multi){
            try{
                foreach(self::multiPath($multi->url) as $k=>$v){
                    delete_dir($v);
                }
                $multi->delete();
            }catch (\Exception $e){
                $this->returnApi('删除失败',0, $e->getMessage());
            }
            $this->returnApi('删除成功');
        }
    }

    private function multiPath($url)
    {
        $data = [
            'controller' => root_path().'app/admin/controller/'.$url,
            'view' => root_path().'view/admin/'.$url,
            'model' => root_path().'app/common/model/'.$url,
            'validate' => root_path().'app/common/validate/'.$url,
            'js' => root_path().'public/static/admin/module/'.$url,
        ];
        return $data;

    }

    private function multiBase()
    {
      return root_path().'extend/view/multi/base.php.tpl';
    }

   
}
