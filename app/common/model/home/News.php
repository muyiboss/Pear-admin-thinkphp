<?php
namespace app\common\model\home;

use think\Model;

class News extends Model
{
    protected $table = 'home_news';

        protected $createTime = 'create_at';

        protected $updateTime = 'update_at';
}
