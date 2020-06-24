<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\common\model\Site as SiteModel;
use app\common\model\UploadFile;
use think\facade\View;
class Site extends Base
{
    public function index()
    {
        if ($this->request->isAjax()) {
            foreach ($this->request->post() as $k => $v) {
                SiteModel::where('name', $k)->update(['value'=> $v]);
            }
            $this->returnApi('保存成功', 1);
        }
        return View::fetch('', [
            'data' => SiteModel::column('value', 'name')
        ]);
    }

    public function upload()
    {
        if ($this->request->isAjax()) {
            $list = UploadFile::order('id','desc')->paginate($this->request->get('limit'));
            $this->returnApi('', 0, $list->items(),['count' => $list->total(), 'limit' => $this->request->get('limit')]);
        }
        return View::fetch();
    }

    public function uploadAdd()
    {
        return View::fetch();
    }

    public function uploadDel()
    {
        $ids = $this->request->param('ids');
        if (!is_array($ids)){
            $this->returnApi('参数错误',0);
        }
        try{
            foreach ($ids as $k) {
                $data = UploadFile::where('id',$k)->find();
                if($data['type']=='阿里云'){
                    alYunDel($data['href']);
                }else{
                    //删除本地文件
                    $path = '../public'.$data['href'];
                    if (file_exists($path)) unlink($path);
                }
                $data->delete();
            }
        }catch (\Exception $e){
            $this->returnApi('删除失败',0);
        }
        $this->returnApi('删除成功');
    }
}
