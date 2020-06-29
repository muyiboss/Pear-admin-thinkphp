<?php
declare (strict_types = 1);

namespace app\admin\controller;

use think\facade\View;
use app\admin\model\Config as ConfigModel;
use app\admin\model\ConfigFile;
use app\admin\model\AdminLog;
class Config  extends Base
{
    public function index()
    {
        if ($this->isAjax) {
            foreach ($this->post as $k => $v) {
                ConfigModel::where('name', $k)->update(['value'=> $v]);
            }
            $this->returnApi('保存成功',0);
        }
        return View::fetch('', [
            'data' => ConfigModel::column('value', 'name')
            ]);
    }

    public function file()
    {
        if ($this->isAjax) {
            $list = ConfigFile::order('id','desc')->paginate($this->get['limit']);
            $this->returnApi('', 0, $list->items(),['count' => $list->total(), 'limit' => $this->get['limit']]);
        }
        return View::fetch();
    }

    public function fileAdd()
    {
        return View::fetch();
    }

    public function fileDel()
    {
        $ids = $this->post['ids'];
        if (!is_array($ids)){
            $this->returnApi('参数错误',0);
        }
        try{
            foreach ($ids as $k) {
                $data = ConfigFile::where('id',$k)->find();
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

    public function log()
    {
        if ($this->isAjax) {
            $where = [];
            if ($search = input('get.uid')) {
               $where[] = ['uid', '=',$search];
            }
            $list = AdminLog::with('log')->order('id','desc')->where($where)->paginate($this->get['limit']);
            $this->returnApi('', 0, $list->items(), ['count' => $list->total(), 'limit' => $this->get['limit']]);
        }
        return View::fetch();
    }

}
