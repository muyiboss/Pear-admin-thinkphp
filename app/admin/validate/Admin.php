<?php
declare (strict_types = 1);

namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
/**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
    protected $rule = [
        'username|用户名' => 'require',
        'password|密码' => 'require',
        'nickname|昵称' => 'require',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];

    /**
     * 登录
     */
    public function sceneLogin()
    {
        if(get_config('login_captcha')){
            return $this->only(['username','password','captcha'])->append('captcha|验证码', 'require|captcha');
        }else{
            return $this->only(['username','password']);
        }
    }

    /**
     * 添加
     */
    public function sceneAdd()
    {
        return $this->only(['username','password','nickname'])
            ->append('username', 'unique:admin_admin');
    }

    /**
     * 编辑
     */
    public function sceneEdit()
    {
        return $this->only(['username','nickname'])
        ->append('username', 'unique:admin_admin');
    }
}
