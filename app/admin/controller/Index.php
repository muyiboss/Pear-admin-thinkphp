<?php
declare (strict_types = 1);

namespace app\admin\controller;

use think\facade\View;
use think\facade\Db;
use think\facade\Session;
use app\admin\model\Admin;
class Index extends Base
{
    public function index()
    {
        return View::fetch();
    }

    public function home(){
        return View::fetch('',[
            'os'=>PHP_OS,
            'space'=>round((disk_free_space('.')/(1024*1024)),2).'M',
            'addr'=>$this->request->server('SERVER_ADDR'),
            'run'=> $this->request->server('SERVER_SOFTWARE'),
            'php'=>PHP_VERSION,
            'php_run'=> php_sapi_name(),
            'mysql'=> function_exists('mysql_get_server_info')?mysql_get_server_info():Db::query('SELECT VERSION() as mysql_version')[0]['mysql_version'],
            'think'=> $this->app->version(),
            'upload'=>ini_get('upload_max_filesize'),
            'max'=>ini_get('max_execution_time').'秒',
        ]);
    }

    public function menu(){
        return json(get_tree(Session::get('key')));
    }

    public function pass()
    {
        if ($this->post){
            Admin::where('id',Session::get('admin.id'))->update(['password' => set_password(trim($this->post['password']))]);
            $this->returnApi('修改成功');
        }
        return View::fetch();
    }

    public function cache()
    {        
        $cache =  root_path().'runtime';
        delete_dir($cache);
        Session::clear();
        $this->returnApi('清理成功');  
    }

}
