<?php
namespace app\admin\controller\home;
use app\common\model\home\News as NewsModel;
use app\common\validate\home\News as NewsValidate;
use think\facade\View;
class News extends Base
{
    /**
     * 列表
     */
    public function index()
    {
        if ($this->isAjax) {
            $list = NewsModel::order('id','desc')->paginate($this->get['limit']);
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
            $validate = new NewsValidate();
            if(!$validate->check($data)) $this->returnApi($validate->getError(),0);
            try {
                NewsModel::create($data);
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
        $news = NewsModel::find($this->get['id']);
        if ($this->isAjax) {
            $data = $this->post;
            //验证
            $validate = new NewsValidate();
            if(!$validate->check($data)) $this->returnApi($validate->getError(),0);
            try {
                $news->save($data);
            }catch (\Exception $e){
                $this->returnApi('更新失败',0, $e->getMessage());
            }
            $this->returnApi('更新成功');
        }
        return View::fetch('',[
            'data' => $news
        ]);
    }
}
