<?php
declare (strict_types = 1);

namespace app\admin\controller;

use think\Request;
use think\facade\View;
use think\facade\Db;
use app\admin\model\Multi;
class Gen extends Base
{

    private $tn;
    private $tn_cn;
    private $ext_php;
    private $multi;
    private $column_data;
    private $prefix        = 'admin_';

    /**
     * 列表
     */
    public function index()
    {
        if ($this->post) {
            $this->prefix = get_field('admin_multi', ['url' => $this->post['multi']], 'prefix');
        }
        $tables = array_column(Db::query('SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = "'.config('database.connections.mysql.database').'" AND TABLE_NAME NOT IN ("admin_admin","admin_admin_permission","admin_admin_role","admin_multi","admin_role","system_config","admin_permission","admin_role_permission","admin_log","system_file") AND TABLE_NAME LIKE "' . $this->prefix . '%"'), 'TABLE_NAME');
        if ($this->post) {
            $this->returnApi('', 0, ['tables' => $tables]);
        }
        return View::fetch('',[
            'multi' => Multi::order(['url'])->column('url'),
            'tables'  => $tables,
        ]);
    }

    public function preview()
    {
        // 完整表名
        $this->tn = $this->post['tn'];
        // 多级地址
        $this->multi = $this->post['multi'];
        // 表前缀
        $this->prefix = get_field('admin_multi', ['url' => $this->multi], 'prefix');
        // 表名注释
        $tn_cn_result = Db::query('SELECT TABLE_COMMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = "'.config('database.connections.mysql.database').'" AND TABLE_NAME = "' . $this->tn . '"');
        $this->tn_cn  = $tn_cn_result[0]['TABLE_COMMENT'];
        // 表字段数据
        $this->column_data = Db::query('SELECT COLUMN_NAME,IS_NULLABLE,DATA_TYPE,IF(COLUMN_COMMENT = "",COLUMN_NAME,COLUMN_COMMENT) COLUMN_COMMENT,COLUMN_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = "'.config('database.connections.mysql.database').'" AND TABLE_NAME = "' . $this->tn . '" AND COLUMN_NAME <> "id"');
        // 去除表名前缀
        $this->tn = str_replace($this->prefix, '', $this->tn);
        // 表名转驼峰
        $this->tn_hump = underline_hump($this->tn);
        // 控制器，模型，验证器文件名称
        $this->ext_php = '/' . $this->tn_hump . '.php';
        if (isset($this->post['preview']) && $this->post['preview'] === 'false') {
            $preview = false;
        } else {
            $preview = true;
        }
        $data = [
            $this->getTplController($preview), 
            $this->getTplModel($preview),
            $this->getTplIndexJs($preview),
            $this->getTplValidate($preview), 
            $this->getTplAddHtml($preview),
            $this->getTplEditHtml($preview), 
            $this->getTplIndexHtml($preview)
        ];
        if ($preview) {
            return View::fetch('',[
                'data'   => $data,
                'multi' => $this->multi,
                'tn'     => $this->prefix . $this->tn,
            ]);
        }
        foreach ($data as $v) {
            @mkdir(dirname($v[0]));
            @file_put_contents($v[0], $v[1]);
        }
        $this->returnApi('操作成功', 0);
    }

    private function getTplController($preview)
    {
        $file = app_path().'controller/'.$this->multi.$this->ext_php;
        if ($preview) {
            $file = str_replace(root_path(), '', $file);
        }
        $list = '
            foreach ($list as $k => $v) {';
        foreach ($this->column_data as $column) {
            if (in_array($column['COLUMN_NAME'], ['create_at', 'update_at'])) {
                $list .= '
                $list[$k][\'' . $column['COLUMN_NAME'] . '\'] = date(\'Y-m-d H:i:s\', $v[\'' . $column['COLUMN_NAME'] . '\']);';
            } else if ($column['COLUMN_TYPE'] === 'varchar(233)') {
                $list .= '
                $list[$k][\'' . $column['COLUMN_NAME'] . '\'] = \'<img class="upload-image show-image" src="\' . $v[\'' . $column['COLUMN_NAME'] . '\'] . \'"/>\';';
            }
        }
        $list .= '
            }';
        $content = str_replace(['{{$multi}}', '{{$tn_hump}}', '{{$tn}}', '{{$list}}'], [$this->multi, $this->tn_hump, $this->tn, $list], file_get_contents(root_path().'extend/view/table/controller.php.tpl'));
        return [$file, $content];
    }
    private function getTplModel($preview)
    {
        $create_at = '';
        if (deep_in_array('create_at', $this->column_data)) {
            $create_at = '
        protected $createTime = \'create_at\';';
        }
        $update_at = '';
        if (deep_in_array('update_at', $this->column_data)) {
            $update_at = '
        protected $updateTime = \'update_at\';';
        }
        $file = root_path().'app/common/model/'.$this->multi.$this->ext_php;
        if ($preview) {
            $file = str_replace(root_path(), '', $file);
        }
        $content = str_replace(['{{$multi}}', '{{$tn_hump}}', '{{$tn}}','{{create_at}}', '{{update_at}}'], [$this->multi, $this->tn_hump, $this->tn, $create_at, $update_at], file_get_contents(root_path().'extend/view/table/model.php.tpl'));
        return [$file, $content];
    }
    private function getTplIndexJs($preview)
    {
        $file = root_path().'public/static/admin/module/'.$this->multi.'/'.$this->tn.'/'.'index.js';
        if ($preview) {
            $file = str_replace(root_path(), '', $file);
        }
        $columns = '';
        foreach ($this->column_data as $column) {
            $columns .= '{
                field: \'' . $column['COLUMN_NAME'] . '\',
                title: \'' . $column['COLUMN_COMMENT'] . '\',
                unresize: true,
                align: \'center\'
            }, ';
        }
        $content = str_replace(['{{$tn_cn}}', '{{$columns}}', '{{$tn}}', '{{$multi}}'], [$this->tn_cn, $columns, $this->tn, $this->multi], file_get_contents(root_path().'extend/view/table/module.index.js.tpl'));
        return [$file, $content];
    }
    private function getTplValidate($preview)
    {
        $rule    = '';
        $message = '';
        $scene   = '';
        foreach ($this->column_data as $column) {
            if (!in_array($column['COLUMN_NAME'], ['create_at', 'update_at'])) {
                if ($column['IS_NULLABLE'] === 'NO') {
                    $rule .= '
        \'' . $column['COLUMN_NAME'] . '\' => \'require';
                    $message .= '
        \'' . $column['COLUMN_NAME'] . '.require\' => \'' . $column['COLUMN_COMMENT'] . '为必填项\',';
                    if (in_array($column['DATA_TYPE'], ['int', 'decimal', 'float', 'double'])) {
                        $rule .= '|number';
                        $message .= '
        \'' . $column['COLUMN_NAME'] . '.number\' => \'' . $column['COLUMN_COMMENT'] . '需为数字\',';
                    }
                    $rule .= '\',';
                    $scene .= '
            \'' . $column['COLUMN_NAME'] . '\',';
                }
            }
        }
        $file = root_path().'app/common/validate/'.$this->multi.$this->ext_php;
        if ($preview) {
            $file = str_replace(root_path(), '', $file);
        }
        $content = str_replace(['{{$multi}}', '{{$tn_hump}}', '{{$rule}}', '{{$message}}', '{{$scene}}'], [$this->multi, $this->tn_hump, $rule, $message, $scene], file_get_contents(root_path().'extend/view/table/validate.php.tpl'));
        return [$file, $content];
    }
    private function getTplAddHtml($preview)
    {
        $columns = '';
        foreach ($this->column_data as $column) {
            if (!in_array($column['COLUMN_NAME'], ['create_at', 'update_at'])) {
                $columns .= '
    <div class="layui-form-item">
        <label class="layui-form-label">
            ' . $column['COLUMN_COMMENT'] . '
        </label>
        <div class="layui-input-block">
            ';
                $lay_verify = '';
                switch ($column['COLUMN_TYPE']) {
                    case 'varchar(233)':
                        if ($column['IS_NULLABLE'] === 'NO') {
                            $lay_verify = ' lay-verify="uploadimg"';
                        }
                        $columns .= '<button class="layui-btn layui-btn-sm upload-image" type="button">
                            <i class="fa fa-image">
                            </i>
                            ' . $column['COLUMN_COMMENT'] . '
                        </button>
                        <input' . $lay_verify . ' name="' . $column['COLUMN_NAME'] . '" type="hidden"/>
                        <div class="upload-image">
                            <span>
                            </span>
                            <img class="upload-image" src=""/>
                        </div>';
                        break;
                        case 'text':
                            $columns .= '<textarea id="content" name="' . $column['COLUMN_NAME'] . '" type="text/plain" style="width:100%;margin-bottom:20px;"/></textarea>';
                        break;
                    default:
                    if ($column['IS_NULLABLE'] === 'NO') {
                        $lay_verify = ' lay-verify="required';
                        if (in_array($column['DATA_TYPE'], ['int', 'decimal', 'float', 'double'])) {
                            $lay_verify .= '|number';
                        }
                        $lay_verify .= '"';
                    }
                    $columns .= '<input autocomplete="off" class="layui-input"' . $lay_verify . ' name="' . $column['COLUMN_NAME'] . '" type="text"/>';
                        break;
                }
                $columns .= '
        </div>
    </div>';
            }
        }
        $file = root_path().'view/admin/'.$this->multi.'/'.$this->tn.'/'. 'add.html';
        if ($preview) {
            $file = str_replace(root_path(), '', $file);
        }
        $content = str_replace(['{{$columns}}'], [$columns], file_get_contents(root_path().'extend/view/table/view.add.html.tpl'));
        return [$file, $content];
    }
    private function getTplEditHtml($preview)
    {
        $columns = '';
        foreach ($this->column_data as $column) {
            if (!in_array($column['COLUMN_NAME'], ['create_at', 'update_at'])) {
                $columns .= '
    <div class="layui-form-item">
        <label class="layui-form-label">
            ' . $column['COLUMN_COMMENT'] . '
        </label>
        <div class="layui-input-block">
            ';
                $lay_verify = '';
                switch ($column['COLUMN_TYPE']) {
                    case 'varchar(233)':
                        if ($column['IS_NULLABLE'] === 'NO') {
                            $lay_verify = ' lay-verify="uploadimg"';
                        }
                        $columns .= '<button class="layui-btn layui-btn-sm upload-image" type="button">
                            <i class="fa fa-image">
                            </i>
                            ' . $column['COLUMN_COMMENT'] . '
                        </button>
                        <input' . $lay_verify . ' name="' . $column['COLUMN_NAME'] . '" type="hidden" value="{$data[\'' . $column['COLUMN_NAME'] . '\']}"/>
                        <div class="upload-image">
                            <span>
                            </span>
                            <img class="upload-image" src="{$data[\'' . $column['COLUMN_NAME'] . '\']}"/>
                        </div>';
                        break;
                        case 'text':
                            $columns .= '<textarea id="content" name="' . $column['COLUMN_NAME'] . '" type="text/plain" style="width:100%;margin-bottom:20px;"/>{$data[\'' . $column['COLUMN_NAME'] . '\']}</textarea>';
                        break;
                    default:
                    if ($column['IS_NULLABLE'] === 'NO') {
                        $lay_verify = ' lay-verify="required';
                        if (in_array($column['DATA_TYPE'], ['int', 'decimal', 'float', 'double'])) {
                            $lay_verify .= '|number';
                        }
                        $lay_verify .= '"';
                    }
                    $columns .= '<input autocomplete="off" class="layui-input"' . $lay_verify . ' name="' . $column['COLUMN_NAME'] . '" type="text" value="{$data[\'' . $column['COLUMN_NAME'] . '\']}"/>';
                        break;
                }
                $columns .= '
        </div>
    </div>';
            }
        }
        $file = root_path().'view/admin/'.$this->multi.'/'.$this->tn.'/'. 'edit.html';
        if ($preview) {
            $file = str_replace(root_path(), '', $file);
        }
        $content = str_replace(['{{$columns}}'], [$columns], file_get_contents(root_path().'extend/view/table/view.edit.html.tpl'));
        return [$file, $content];
    }
    private function getTplIndexHtml($preview)
    {
        $file = root_path().'view/admin/'.$this->multi.'/'.$this->tn.'/'. 'index.html';
        if ($preview) {
            $file = str_replace(root_path(), '', $file);
        }
        $content = str_replace(['{{$tn_cn}}', '{{$tn}}', '{{$multi}}'], [$this->tn_cn, $this->tn, $this->multi], file_get_contents(root_path().'extend/view/table/view.index.html.tpl'));
        return [$file, $content];
    }

   
}
