<?php
namespace app\admin\controller\{{$multi}};
use app\common\model\{{$multi}}\{{$tn_hump}} as {{$tn_hump}}Model;
use app\common\validate\{{$multi}}\{{$tn_hump}} as {{$tn_hump}}Validate;
use think\facade\View;
class {{$tn_hump}} extends Base
{
    /**
     * 列表
     */
    public function index()
    {
        if ($this->isAjax) {
            $list = {{$tn_hump}}Model::order('id','desc')->paginate($this->get['limit']);
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
            $validate = new {{$tn_hump}}Validate();
            if(!$validate->check($data)) $this->returnApi($validate->getError(),0);
            try {
                {{$tn_hump}}Model::create($data);
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
        ${{$tn}} = {{$tn_hump}}Model::find($this->get['id']);
        if ($this->isAjax) {
            $data = $this->post;
            //验证
            $validate = new {{$tn_hump}}Validate();
            if(!$validate->check($data)) $this->returnApi($validate->getError(),0);
            try {
                ${{$tn}}->save($data);
            }catch (\Exception $e){
                $this->returnApi('更新失败',0, $e->getMessage());
            }
            $this->returnApi('更新成功');
        }
        return View::fetch('',[
            'data' => ${{$tn}}
        ]);
    }
}
