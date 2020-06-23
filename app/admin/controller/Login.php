<?php
declare (strict_types = 1);

namespace app\admin\controller;

use think\facade\View;
use app\admin\model\Admin;
use think\captcha\facade\Captcha;
use app\admin\validate\Admin as AdminValidate;
class Login extends Base
{
    public function index()
    {
        $admin = new Admin();
        //是否已经登录
        if ($admin->isLogin()){
            return redirect('/');
        }
        if ($this->request->isAjax()){
            //获取数据
            $data = $this->request->post();
            //验证
            $validate = new AdminValidate;
            if (!$validate->scene('login')->check($data)) {
                $this->returnApi($validate->getError(),0);
            }
            //判断登录
            if (true == $admin->login($data['username'],$data['password'])){
                $this->returnApi('登录成功');
            }
            $this->returnApi('用户名或密码错误',0);
        }
        return View::fetch();
    }

    public function verify(){
        return Captcha::create(); 
    }

    public function logout(){
        (new Admin())->logout();
        return redirect('/login')->with('success','成功退出');
    }
}
