<?php
declare (strict_types = 1);

namespace app\admin\controller;

use think\facade\View;
use think\facade\Session;
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

    public function cache(){
        delete_dir_file(root_path().'runtime/');
        return redirect('/')->with('success','清理成功');
    }
    
}
