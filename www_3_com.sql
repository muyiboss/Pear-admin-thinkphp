-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2020-06-25 21:21:56
-- 服务器版本： 5.7.26
-- PHP 版本： 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `www.3.com`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` char(30) NOT NULL COMMENT '用户名，登陆使用',
  `password` char(50) NOT NULL COMMENT '用户密码',
  `nickname` char(50) NOT NULL COMMENT '用户昵称',
  `salt` char(30) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户状态：1正常，0禁用，默认1',
  `create_time` timestamp NULL DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理表';

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nickname`, `salt`, `status`, `create_time`, `update_time`) VALUES
(1, 'admin', '04f2ee7178b2cee169247b499794d092', '超级管理员', 'LEYYSqqpsOYOeiOH', 1, '2020-02-27 03:26:23', '2020-06-23 19:22:37');

-- --------------------------------------------------------

--
-- 表的结构 `admin_log`
--

CREATE TABLE `admin_log` (
  `id` int(11) NOT NULL,
  `username` char(50) NOT NULL COMMENT '用户名',
  `ip` char(20) NOT NULL COMMENT 'IP地址',
  `user_agent` varchar(255) NOT NULL COMMENT 'User-Agent',
  `remark` char(50) NOT NULL COMMENT '备注信息',
  `create_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='登录日志表';

--
-- 转存表中的数据 `admin_log`
--

INSERT INTO `admin_log` (`id`, `username`, `ip`, `user_agent`, `remark`, `create_time`) VALUES
(1, 'admin', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36', '登录成功', '2020-06-25 13:20:54');

-- --------------------------------------------------------

--
-- 表的结构 `admin_permission`
--

CREATE TABLE `admin_permission` (
  `id` int(10) UNSIGNED NOT NULL,
  `admin_id` int(11) NOT NULL COMMENT '管理ID',
  `permission_id` int(11) NOT NULL COMMENT '权限ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户-权限中间表';

-- --------------------------------------------------------

--
-- 表的结构 `admin_role`
--

CREATE TABLE `admin_role` (
  `id` int(10) UNSIGNED NOT NULL,
  `admin_id` int(11) NOT NULL COMMENT '管理ID',
  `role_id` int(11) NOT NULL COMMENT '角色ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理-角色中间表';

-- --------------------------------------------------------

--
-- 表的结构 `permission`
--

CREATE TABLE `permission` (
  `id` int(11) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级ID',
  `title` char(50) NOT NULL COMMENT '权限名称',
  `href` varchar(255) NOT NULL COMMENT '路由url',
  `icon` char(50) NOT NULL DEFAULT '' COMMENT '图标',
  `type` tinyint(1) DEFAULT NULL COMMENT '1:目录2：菜单',
  `sort` tinyint(2) NOT NULL DEFAULT '99' COMMENT '排序'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表';

--
-- 转存表中的数据 `permission`
--

INSERT INTO `permission` (`id`, `pid`, `title`, `href`, `icon`, `type`, `sort`) VALUES
(1, 0, '首页', '/main', 'layui-icon layui-icon-home', 1, 1),
(2, 0, '后台管理', '/', 'layui-icon layui-icon-username', 0, 99),
(3, 2, '管理员', '/admin/index', '', 1, 1),
(4, 3, '添加', '/admin/add', '', 1, 99),
(5, 3, '编辑', '/admin/edit', '', 3, 99),
(6, 3, '删除', '/admin/del', '', 4, 99),
(7, 3, '分配角色', '/admin/role', '', 1, 99),
(8, 3, '分配权限', '/admin/permission', '', 1, 99),
(9, 2, '角色管理', '/role/index', '', 1, 2),
(10, 9, '添加', '/role/add', '', 1, 99),
(11, 9, '编辑', '/role/edit', '', 1, 99),
(12, 9, '删除', '/role/del', '', 1, 99),
(13, 9, '分配权限', '/role/permission', '', 1, 99),
(14, 2, '权限管理', '/permission/index', '', 1, 3),
(15, 14, '添加', '/permission/add', '', 2, 99),
(16, 14, '编辑', '/permission/edit', '', 3, 99),
(17, 14, '删除', '/permission/del', '', 4, 99),
(18, 0, '系统管理', '/', 'layui-icon layui-icon-set', 0, 99),
(19, 18, '网站管理', '/site/index', '', 1, 99),
(20, 0, '项目管理', '/', 'layui-icon layui-icon-water', 0, 99),
(21, 2, '管理日志', '/admin/log', '', 1, 4),
(22, 18, '文件管理', '/site/upload', '', 1, 99);

-- --------------------------------------------------------

--
-- 表的结构 `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` char(50) NOT NULL COMMENT '角色名称',
  `desc` varchar(255) NOT NULL COMMENT '角色描述',
  `create_time` timestamp NULL DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色表';

--
-- 转存表中的数据 `role`
--

INSERT INTO `role` (`id`, `name`, `desc`, `create_time`, `update_time`) VALUES
(1, '超级管理员', '用于所有管理权限!', '2020-02-28 06:00:39', '2020-02-28 06:00:50'),
(2, '123', '123', '2020-06-24 08:46:24', '2020-06-24 08:46:24');

-- --------------------------------------------------------

--
-- 表的结构 `role_permission`
--

CREATE TABLE `role_permission` (
  `id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(11) NOT NULL COMMENT '权限ID',
  `role_id` int(11) NOT NULL COMMENT '角色ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色-权限中间表';

-- --------------------------------------------------------

--
-- 表的结构 `site`
--

CREATE TABLE `site` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(50) NOT NULL COMMENT '名称',
  `value` varchar(255) DEFAULT NULL COMMENT '值'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='网站设置';

--
-- 转存表中的数据 `site`
--

INSERT INTO `site` (`id`, `name`, `value`) VALUES
(1, 'title', 'pear admin think'),
(2, 'key', 'pear admin think'),
(3, 'desc', 'pear admin think'),
(4, 'smtp-user', 'muyiboss@qq.com'),
(5, 'smtp-pass', ''),
(6, 'smtp-port', '465'),
(7, 'smtp-host', 'smtp.qq.com'),
(8, 'file-type', '1'),
(9, 'file-endpoint', 'img.jianla.cn'),
(10, 'file-OssName', 'jianla-img'),
(11, 'file-accessKeyId', '123123'),
(12, 'file-accessKeySecret', 'asdfasdfasdfsadfasdf'),
(13, 'logo', '');

-- --------------------------------------------------------

--
-- 表的结构 `upload_file`
--

CREATE TABLE `upload_file` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` char(50) NOT NULL COMMENT '文件名称',
  `href` varchar(255) DEFAULT NULL COMMENT '文件路径',
  `mime` char(50) NOT NULL COMMENT 'mime类型',
  `size` char(30) NOT NULL COMMENT '大小',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1本地2阿里云',
  `ext` char(10) DEFAULT NULL COMMENT '文件后缀',
  `create_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文件表';

--
-- 转存表中的数据 `upload_file`
--

INSERT INTO `upload_file` (`id`, `name`, `href`, `mime`, `size`, `type`, `ext`, `create_time`) VALUES
(1, '一群小狗狗.gif', '/topic/20200625/3cf011e69ae692ececef8655d642853c.gif', 'image/gif', '378818', 1, 'gif', '2020-06-25 08:06:21'),
(2, '一群小狗狗.gif', '/topic/20200625/11c507fca92ec7a07aea088388309721.gif', 'image/gif', '378818', 1, 'gif', '2020-06-25 08:06:31'),
(3, '水印1120.png', '/topic/20200625/8ee0789fd306af9a102e8aa14808852b.png', 'image/png', '66325', 1, 'png', '2020-06-25 08:06:37');

--
-- 转储表的索引
--

--
-- 表的索引 `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- 表的索引 `admin_log`
--
ALTER TABLE `admin_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- 表的索引 `admin_permission`
--
ALTER TABLE `admin_permission`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `admin_role`
--
ALTER TABLE `admin_role`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `site`
--
ALTER TABLE `site`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `upload_file`
--
ALTER TABLE `upload_file`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `admin_log`
--
ALTER TABLE `admin_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `admin_permission`
--
ALTER TABLE `admin_permission`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `admin_role`
--
ALTER TABLE `admin_role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- 使用表AUTO_INCREMENT `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `role_permission`
--
ALTER TABLE `role_permission`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `site`
--
ALTER TABLE `site`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- 使用表AUTO_INCREMENT `upload_file`
--
ALTER TABLE `upload_file`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
