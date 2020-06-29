#### 简介

后端基于thinkphp6.0.3，前端后台Pear Admin Layui。开发的后台管理系统
2020/6/29 重大重塑  进一步优化系统逻辑
演示地址http://demo.jianla.cn/ 
账号admin 密码123456

#### 安装

* git clone https://gitee.com/muyiboss/Pear-Admin-Thinkphp.git
* composer update
* cp .example.env .env
* 导入数据库
* 修改配置信息
config/app.php 路由绑定应用
'domain_bind'      => [
    'admin' => 'admin',//后台 如子域名admin.xxx.com 
    'www' => 'index', //前台
    '*' => 'index' //前台
],
* 开启redis


#### 完成项目

* RABC权限    完成
* OSS上传     完成
* 邮件发送    完成
* 系统设置    完成
* 文件管理    完成

![输入图片说明](https://images.gitee.com/uploads/images/2020/0626/192920_2a718f1e_1302383.png "11.png")
![输入图片说明](https://images.gitee.com/uploads/images/2020/0625/213756_27cbdd83_1302383.png "2.png")
![输入图片说明](https://images.gitee.com/uploads/images/2020/0625/213802_e118467d_1302383.png "3.png")

如有疑问可以联系QQ 974992939 
### 基于 Pear Admin Layui 的项目应用

Pear-Admin-Boot : [开源地址](https://gitee.com/Jmysy/Pear-Admin-Boot) :+1: 

>特别感谢

ThinkPHP：https://github.com/top-think/framework

Pear-Admin-Layui:https://gitee.com/Jmysy/Pear-Admin-Layui
