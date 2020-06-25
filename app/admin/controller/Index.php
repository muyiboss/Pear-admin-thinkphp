<?php
declare (strict_types = 1);

namespace app\admin\controller;

use think\facade\View;
use think\facade\Session;
use app\common\model\UploadFile;
class Index extends Base
{
    public function index(){
        return View::fetch();
    }

    public function main(){
        return View::fetch();
    }

    public function menu(){
        return json(Session::get('menu'));
    }

    public function upload()
    {
        $file = $this->request->file();

        try {
            $type = get_site('file-type');
            if($type==2){
                //阿里云上传
                validate(['image'=>'filesize:10240|fileExt:jpg|image:200,200,jpg'])
                ->check($file);
                $savename = [];
                foreach($file as $k) {
                    $res = alYunOSS($k, $k->extension());
                    $up = new UploadFile();
                    $up->add($k,$res['src'],2);
                    if ($res["code"] == 1) $savename = $res['src'];
                }
            }else{
                validate(['image'=>'filesize:10240|fileExt:jpg|image:200,200,jpg'])
                ->check($file);
                foreach($file as $k) {
                    $savename = '/'. \think\facade\Filesystem::disk('public')->putFile( 'topic', $k);
                    $up = new UploadFile();
                    $up->add($k,$savename,1);
                }
            }
            $this->returnApi('上传成功',1,$savename);
        } catch (think\exception\ValidateException $e) {
            $this->returnApi('上传失败',0,$e->getMessage());
        }
    }
    public function cache(){
        delete_dir_file(root_path().'runtime/');
        return redirect('/')->with('success','清理成功');
    }
    
}