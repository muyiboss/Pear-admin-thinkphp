<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;
use think\facade\Session;
use think\facade\Cookie;
/**
 * @mixin \think\Model
 */
class Admin extends Model
{
    protected $table = 'admin_admin';
    // 定义时间戳字段名
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    /**
     * 用户登录验证
     * @param string $username
     * @param string $password
     * @param bool $remember
     */
    public function login($username = '', $password = '', $remember = '')
    {
        $data = [
           'username' => trim($username),
           'password' => set_password(trim($password)),
           'status' => 1
        ];
        //验证用户
        $admin = self::where($data)->find();
        if (!$admin) return false;
        if ($remember==1) Cookie::set('token', simple_encrypt($admin->id . '@@@' . $admin->password), 30 * 86400);
        // 缓存登录信息
        $data = [
            'id' => $admin->id,
            'username' => $admin->username,
            'nickname' => $admin->nickname,
        ];
        Session::set('admin', $data);
        Session::set('sign', $this->dataSign($data));
        //缓存权限
        $permissions = $this->permissions($admin->id);
        Session::set('key', $permissions);
        // 触发登录成功事件
        event('AdminLogin');
        return true;
    }
    
    /**
     * 判断是否登录
     * @return bool|array
     */
    public function isLogin()
    {
        $admin = Session::get('admin');
        $token = Cookie::get('token');
        if (!$admin && !$token) return false;
        $isToken = explode('@@@', simple_decrypt($token?$token:'-'));
        if (!$admin) {
            $info = self::field(true)->where(['id'=>$isToken[0],'status'=>1])->find();
            if(!$info) return false;
            // 缓存登录信息
            $data = [
                'id' => $info->id,
                'username' => $info->username,
                'nickname' => $info->nickname,
            ];
            Session::set('admin', $data);
            Session::set('sign', $this->dataSign($data));
            //缓存权限
            $permissions = $this->permissions($info->id);
            Session::set('key', $permissions);
            return true;
         }
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
        Session::delete('admin');
        Session::delete('sign');
        Session::delete('key');
        Cookie::delete('token');
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
     * 用户拥有的角色
     */
    public function roles()
    {
        return $this->belongsToMany('Role', 'admin_admin_role', 'role_id', 'admin_id');
    }

    /**
     * 用户的直接权限
     */
    public function directPermissions()
    {
        return $this->belongsToMany('Permission', 'admin_admin_permission', 'permission_id', 'admin_id');
    }

    /**
     * 用户的所有权限
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
