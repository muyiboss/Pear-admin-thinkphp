<?php
// 应用公共文件
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use think\facade\Db;
use OSS\OssClient;
use OSS\Core\OssException;

/**
 *阿里云上传
 */
function alYunOSS($filePath,$Extension){
    $data = Db::name('site')->column('value', 'name');
    $accessKeyId =  $data['file-accessKeyId']; 
    $accessKeySecret = $data['file-accessKeySecret']; 
    $endpoint = $data['file-endpoint'];
    $bucket= $data['file-OssName'];    
    $object = 'upload/'.date("Ymd") .'/'.time() .rand(10000,99999) ."." .$Extension;    // 文件名称
    try{
        $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint,true);
        $rel = $ossClient->uploadFile($bucket, $object, $filePath);
            return  ['code' => 1,'src' => $rel["info"]["url"]];
    } catch(OssException $e) {
            return ['code' => 0,'src' => $e->getMessage()];
    }
}

 /**
 * 获取指定表指定行指定字段
 * @param  string       $tn      完整表名
 * @param  string|array $where   参数数组或者id值
 * @param  string       $field   字段名,默认'name'
 * @param  string       $default 获取失败的默认值,默认''
 * @param  array        $order   排序数组
 * @return string                获取到的内容
 */
function get_field($tn, $where, $field = 'name', $default = '', $order = ['id' => 'desc'])
{
    if (!is_array($where)) {
        $where = ['id' => $where];
    }
    $row = Db::name($tn)->field([$field])->where($where)->order($order)->find();
    return $row === null ? $default : $row[$field];
}

/**
 * 获取系统设置
 * @param  string $name 系统设置名称
 * @return string       系统设置内容
 */
function get_site($name)
{
    return get_field('site', ['name' => $name], 'value');
}

/**
 * 发送邮箱
 * @param array $data
 * @param string $addr 地址
 * @param string $title 标题
 * @param string $content 内容
 * @return mixed
 */
function go_mail($addr,$title,$content)
{
    $mail = new PHPMailer(true);
    $data = Db::name('site')->column('value', 'name');
    try {
        $mail->SMTPDebug = 0;                    
        $mail->CharSet = 'utf-8';          
        $mail->isSMTP();                                     
        $mail->Host = $data['smtp-host'];  
        $mail->SMTPAuth = true;                          
        $mail->Username =  $data['smtp-user'];             
        $mail->Password =  $data['smtp-pass'];                  
        $mail->SMTPSecure = 'ssl';                            
        $mail->Port =  $data['smtp-port'];                                
        $mail->setFrom($data['smtp-user'], $data['title']);
        $mail->addAddress($addr);    
        $mail->isHTML(true);                                 
        $mail->Subject = $title;
        $mail->Body    = $content;
    return $mail->send();
        echo 'Message has been sent';
	} catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}

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
