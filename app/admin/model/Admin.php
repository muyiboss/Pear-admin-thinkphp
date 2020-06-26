<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\facade\Session;
use think\facade\Request;
use think\Model;

class Admin extends Model
{
    protected $table = 'admin';

    /**
     * 用户登录验证
     * @param string $adminname
     * @param string $password
     */
    public function login($adminname = '', $password = '')
    {
        $adminname = trim($adminname);
        $password = trim($password);
        //验证用户
        $admin = self::where('username', $adminname)->where('status', 1)->find();
        if ($admin == null) {
            $this->log(['username' => $adminname, 'remark' => '用户不存在，或被禁用']);
            return false;
        };
        // 密码校验
        if ($admin->password != md5(md5($password) . $admin->salt)) {
            $this->log(['username' => $adminname, 'remark' => '密码错误']);
            return false;
        };
        //写入登录日志
        $this->log(['username' => $adminname, 'remark' => '登录成功']);
        // 缓存登录信息
        $data = [
            'id' => $admin->id,
            'username' => $admin->username,
            'nickname' => $admin->nickname,
        ];
        Session::set('key', $data);
        Session::set('sign', $this->dataSign($data));
        //缓存权限
        $permissions = $this->permissions($admin->id);
        Session::set('permission', $permissions);
        return true;
    }

    /**
     * 写入登录日志
     * @param $data
     * @return bool
     */
    public function log($data)
    {
        $data['ip'] = Request::ip();
        $data['user_agent'] = Request::header('User-Agent');
        AdminLog::create($data);
    }

    /**
     * 判断是否登录
     * @return bool|array
     */
    public function isLogin()
    {
        $admin = Session::get('key');
        if (isset($admin['id'])) {
            return Session::get('sign') == $this->dataSign($admin) ? true : false;
        }
        return false;
    }

    /**
     * 退出登陆
     * @return bool
     */
    public function logout()
    {
        Session::delete('key');
        Session::delete('sign');
        Session::delete('permission');
        return true;
    }

    /**
     * 数据签名认证
     * @param array $data 被认证的数据
     * @return string 签名
     */
    public function dataSign($data = [])
    {
        if (!is_array($data)) {
            $data = (array)$data;
        }
        ksort($data);
        $code = http_build_query($data);
        $sign = sha1($code);
        return $sign;
    }

    /**
     * 拥有的角色
     * @return \think\model\relation\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('Role', 'admin_role', 'role_id', 'admin_id');
    }

    /**
     * 直接权限
     * @return \think\model\relation\BelongsToMany
     */
    public function directPermissions()
    {
        return $this->belongsToMany('Permission', 'admin_permission', 'permission_id', 'admin_id');
    }

    /**
     * 用户的所有权限
     * @param $id
     * @return array
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function permissions($id)
    {
        $admin = self::with(['roles.permissions', 'directPermissions'])->find($id);
        $permissions = [];
        //超级管理员缓存所有权限
        if ($admin['id'] == 1){
            $perms = Permission::order('sort','asc')->select();
            foreach ($perms as $p){
                $permissions[$p['id']] = [
                    'id' => $p['id'],
                    'pid' => $p['pid'],
                    'title' => $p['title'],
                    'href' => $p['href'],
                    'icon' => $p['icon'],
                    'type' => $p['type'],
                ];
            }
        }else{
            //处理权限
            if (isset($admin['roles']) && !empty($admin['roles'])) {
                foreach ($admin['roles'] as $r) {
                    if (isset($r['permissions']) && !empty($r['permissions'])) {
                        foreach ($r['permissions'] as $p) {
                            $permissions[$p['id']] = [
                                'id' => $p['id'],
                                'pid' => $p['pid'],
                                'title' => $p['title'],
                                'href' => $p['href'],
                                'icon' => $p['icon'],
                                'type' => $p['type'],
                            ];
                        }
                    }
                }
            }
            //处理直接权限
            if (isset($admin->direct_permissions) && !$admin->direct_permissions->isEmpty()) {
                foreach ($admin->direct_permissions as $p) {
                    $permissions[$p['id']] = [
                        'id' => $p['id'],
                        'pid' => $p['pid'],
                        'title' => $p['title'],
                        'href' => $p['href'],
                        'icon' => $p['icon'],
                        'type' => $p['type'],
                    ];
                }
            }
        }
        //合并权限为用户的最终权限
        return $permissions;
    }

}