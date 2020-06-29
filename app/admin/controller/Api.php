<?php
declare (strict_types = 1);

namespace app\admin\controller;

use think\facade\Db;
use app\admin\model\ConfigFile;
class Api extends Base
{
    /**
     *通用状态修改
     */
    public function update()
    {
        $where = $this->post['where'];
        if (!is_array($where)) {
            $where = ['id' => $where];
        }
        $result = Db::table($this->post['tn'])->where($where)->update($this->post['update']);
        if ($result) {
            $this->returnApi('操作成功', 0);
        }
        $this->returnApi('操作失败,请稍后重试');
    }

    /**
     * 通用删除
     */
    public function remove()
    {
        $where = $this->post['where'];
        if (!is_array($where)) {
            $where = ['id' => $where];
        }
        $result = Db::table($this->post['tn'])->where($where)->delete();
        if ($result) {
            $this->returnApi('删除成功', 0);
        }
        $this->returnApi('操作失败,请稍后重试');
    }

    /**
     * 通用批量
     */
    public function removes()
    {
        $ids = $this->post['where'];
        $result = Db::table($this->post['tn'])->delete($ids);
        if ($result) {
            $this->returnApi('删除成功', 0);
        }
        $this->returnApi('操作失败,请稍后重试');
    }

    /**
     * 通用上传
     */
    public function upload()
    {
        $file = $this->request->file();
        try {
            $type = get_config('file-type');
            if($type==2){
                //阿里云上传
                validate(['image'=>'filesize:10240|fileExt:jpg|image:200,200,jpg'])
                ->check($file);
                $savename = [];
                foreach($file as $k) {
                    $res = alYunOSS($k, $k->extension());
                    $up = new ConfigFile();
                    $up->add($k,$res['src'],2);
                    if ($res["code"] == 1) $savename = $res['src'];
                }
            }else{
                validate(['image'=>'filesize:10240|fileExt:jpg|image:200,200,jpg'])
                ->check($file);
                foreach($file as $k) {
                    $savename = '/'. \think\facade\Filesystem::disk('public')->putFile( 'topic', $k);
                    $up = new ConfigFile();
                    $savename = str_replace("\\","/",$savename);
                    $up->add($k,$savename,1);
                }
            }
            $this->returnApi('上传成功', 0, ['src'=>$savename,'thumb'=>$savename]);
        } catch (think\exception\ValidateException $e) {
            $this->returnApi('上传失败',1,$e->getMessage());
        }
    }

}
