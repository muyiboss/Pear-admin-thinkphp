<?php
namespace app\common\validate\{{$multi}};

use think\Validate;

class {{$tn_hump}} extends Validate
{
    protected $rule = [{{$rule}}
    ];
    protected $message = [{{$message}}
    ];
    protected $scene = [
        'edit' => [{{$scene}}
        ],
    ];
}
