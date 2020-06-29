<?php
declare (strict_types = 1);

namespace app\admin\model;
use think\facade\Request;
use think\facade\Session;
use think\Model;

/**
 * @mixin \think\Model
 */
class AdminLog extends Model
{

    protected $table = 'admin_log';
    // 定义时间戳字段名
    protected $createTime = 'create_at';

    public function log()
    {
        return $this->belongsTo('Admin','uid','id');
    }

    // 管理员日志记录
    public static function record()
    {
        $info = [
           'uid'       => Session::get('admin.id'),
           'url'      => Request::url(),
           'ip'       => Request::ip(),
           'user_agent'=> Request::server('HTTP_USER_AGENT'),

        ];
        $res = self::where('uid',$info['uid'])
            ->order('id', 'desc')
            ->find();
        if (isset($res['url'])!==$info['url']) {
            self::create($info);
        }
    }
}
