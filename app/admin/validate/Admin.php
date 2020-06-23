<?php
declare (strict_types = 1);

namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'username|用户名' => 'require|unique:admin',
        'password|密码' => 'require',
        'nickname|昵称' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [];

    /**
     * 登录场景
     * @return Admin
     */
    public function sceneLogin()
    {
        return $this->append('captcha|验证码', 'require|captcha')
            ->remove('nickname',true)
            ->remove('username',true);
    }

    /**
     * 编辑场景
     * @return Admin
     */
    public function sceneEdit()
    {
            return $this->only(['username','nickname'])
            ->remove('username', 'unique:admin')
            ->append('username', 'unique:admin,id^id');
    }
}

