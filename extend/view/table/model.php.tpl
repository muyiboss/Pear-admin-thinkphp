<?php
namespace app\common\model\{{$multi}};

use think\Model;

class {{$tn_hump}} extends Model
{
    protected $table = '{{$multi}}_{{$tn}}';
{{create_at}}
{{update_at}}
}
