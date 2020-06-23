<?php
// 应用公共文件
/**
 * 递归无限级分类权限
 * @param array $data
 * @param int $pid
 * @param string $field1 父级字段
 * @param string $field2 子级关联的父级字段
 * @param string $field3 子级键值
 * @return mixed
 */
function get_tree($data, $pid = 0, $field1 = 'id', $field2 = 'pid', $field3 = 'children')
{
    $arr = [];
    foreach ($data as $k => $v) {
        if ($v[$field2] == $pid) {
            $v[$field3] = get_tree($data, $v[$field1]);
            $arr[] = $v;
        }
    }
    return $arr;
}

/**
 * 循环删除目录和文件
 * @param string $dir_name
 * @return bool
 */
function delete_dir_file($dir_name) {
    $result = false;
    if(is_dir($dir_name)){
        if ($handle = opendir($dir_name)) {
            while (false !== ($item = readdir($handle))) {
                if ($item != '.' && $item != '..') {
                    if (is_dir($dir_name . '/' . $item)) {
                        delete_dir_file($dir_name . '/' . $item);
                    } else {
                        unlink($dir_name . '/' . $item);
                    }
                }
            }
            closedir($handle);
            if (rmdir($dir_name)) {
                $result = true;
            }
        }
    }
    return $result;
}

/**
 * 随机字符串
 * @param int $length 长度
 * @param int $type 类型(0：混合；1：纯数字)
 * @return string
 */
function random($length = 16, $type = 1) {
    $seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $type ? 10 : 35);
    $seed = $type ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
    if($type) {
        $hash = '';
    } else {
        $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
        $length--;
    }
    $max = strlen($seed) - 1;
    for($i = 0; $i < $length; $i++) {
        $hash .= $seed{mt_rand(0, $max)};
    }
    return $hash;
}
