-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2020-06-30 15:06:45
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
-- 表的结构 `admin_admin`
--

CREATE TABLE `admin_admin` (
  `id` int(11) NOT NULL,
  `username` char(50) DEFAULT NULL COMMENT '用户名',
  `nickname` char(50) DEFAULT NULL COMMENT '昵称',
  `password` char(50) CHARACTER SET utf8 COLLATE utf8_estonian_ci DEFAULT NULL COMMENT '密码',
  `status` tinyint(1) DEFAULT '1' COMMENT '是否禁用',
  `create_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` timestamp NULL DEFAULT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理表';

--
-- 转存表中的数据 `admin_admin`
--

INSERT INTO `admin_admin` (`id`, `username`, `nickname`, `password`, `status`, `create_at`, `update_at`) VALUES
(1, 'admin', 'oks', 'cb962ac59075b964b07152d234', 1, NULL, '2020-03-31 09:19:06'),
(6, '3', '12312', 'bc87e4b5ce2fe28308fd9f2a7b', 1, '2020-06-29 15:23:37', '2020-06-29 15:23:37'),
(7, '123', '231', 'abeea535938c496a261b3b39c0', 1, '2020-06-30 06:37:45', '2020-06-30 06:37:45');

-- --------------------------------------------------------

--
-- 表的结构 `admin_admin_permission`
--

CREATE TABLE `admin_admin_permission` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `admin_id` int(11) DEFAULT NULL COMMENT '管理ID',
  `permission_id` int(11) DEFAULT NULL COMMENT '权限ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理-权限中间表';

--
-- 转存表中的数据 `admin_admin_permission`
--

INSERT INTO `admin_admin_permission` (`id`, `admin_id`, `permission_id`) VALUES
(16, 6, 1);

-- --------------------------------------------------------

--
-- 表的结构 `admin_admin_role`
--

CREATE TABLE `admin_admin_role` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `admin_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `role_id` int(11) DEFAULT NULL COMMENT '角色ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理-角色中间表';

--
-- 转存表中的数据 `admin_admin_role`
--

INSERT INTO `admin_admin_role` (`id`, `admin_id`, `role_id`) VALUES
(3, 4, 1),
(6, 6, 1);

-- --------------------------------------------------------

--
-- 表的结构 `admin_log`
--

CREATE TABLE `admin_log` (
  `id` int(8) UNSIGNED NOT NULL,
  `uid` int(11) DEFAULT NULL COMMENT '管理员ID',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '操作页面	',
  `ip` varchar(20) NOT NULL DEFAULT '' COMMENT '操作IP',
  `user_agent` varchar(255) NOT NULL COMMENT 'User-Agent',
  `create_at` timestamp NULL DEFAULT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员日志';

-- --------------------------------------------------------

--
-- 表的结构 `admin_multi`
--

CREATE TABLE `admin_multi` (
  `id` int(11) NOT NULL,
  `url` char(50) NOT NULL COMMENT '地址',
  `prefix` char(50) NOT NULL COMMENT '库表前缀',
  `name` char(50) NOT NULL COMMENT '多级名称'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='多级控制';

--
-- 转存表中的数据 `admin_multi`
--

INSERT INTO `admin_multi` (`id`, `url`, `prefix`, `name`) VALUES
(1, 'admin', 'admin_', '后台管理'),
(10, 'home', 'home_', '前台管理');

-- --------------------------------------------------------

--
-- 表的结构 `admin_permission`
--

CREATE TABLE `admin_permission` (
  `id` int(10) UNSIGNED NOT NULL,
  `mid` int(11) NOT NULL COMMENT '多级ID',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `title` char(50) DEFAULT NULL COMMENT '名称',
  `href` char(50) NOT NULL COMMENT '地址',
  `icon` char(50) DEFAULT NULL COMMENT '图标',
  `sort` tinyint(2) NOT NULL DEFAULT '99' COMMENT '排序',
  `type` tinyint(1) DEFAULT '1' COMMENT '菜单'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表';

--
-- 转存表中的数据 `admin_permission`
--

INSERT INTO `admin_permission` (`id`, `mid`, `pid`, `title`, `href`, `icon`, `sort`, `type`) VALUES
(1, 0, 0, '首页', '/index/home', 'layui-icon layui-icon-home', 1, 1),
(2, 0, 0, '后台权限', '', 'layui-icon layui-icon-username', 1, 0),
(3, 0, 2, '管理员', '/admin/index', '', 1, 1),
(4, 0, 2, '角色管理', '/role/index', '', 99, 1),
(5, 0, 2, '菜单权限', '/permission/index', '', 99, 1),
(6, 0, 0, '系统管理', '', 'layui-icon layui-icon-set', 3, 0),
(7, 0, 6, '后台日志', '/config/log', '', 2, 1),
(8, 0, 6, '系统设置', '/config/index', '', 1, 1),
(9, 0, 6, '文件管理', '/config/file', '', 2, 1),
(16, 10, 0, 'CMS管理', '/', 'layui-icon layui-icon-release', 2, 0),
(17, 10, 16, '新闻管理', '/home/news/index', '', 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `admin_role`
--

CREATE TABLE `admin_role` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(50) DEFAULT NULL COMMENT '名称',
  `desc` varchar(255) DEFAULT NULL COMMENT '描述',
  `create_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  `update_at` timestamp NULL DEFAULT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色表';

--
-- 转存表中的数据 `admin_role`
--

INSERT INTO `admin_role` (`id`, `name`, `desc`, `create_at`, `update_at`) VALUES
(1, '超级管理员', '拥有所有管理权限', NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `admin_role_permission`
--

CREATE TABLE `admin_role_permission` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `permission_id` int(11) DEFAULT NULL COMMENT '权限ID',
  `role_id` int(11) DEFAULT NULL COMMENT '角色ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色-权限中间表';

--
-- 转存表中的数据 `admin_role_permission`
--

INSERT INTO `admin_role_permission` (`id`, `permission_id`, `role_id`) VALUES
(11, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `home_news`
--

CREATE TABLE `home_news` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL COMMENT '标题',
  `desc` varchar(255) DEFAULT NULL COMMENT '描述',
  `content` text COMMENT '内容',
  `img` varchar(233) NOT NULL COMMENT '缩略图',
  `create_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` timestamp NULL DEFAULT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='新闻';

-- --------------------------------------------------------

--
-- 表的结构 `system_config`
--

CREATE TABLE `system_config` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(50) NOT NULL COMMENT '名称',
  `value` varchar(255) DEFAULT NULL COMMENT '值'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统设置';

--
-- 转存表中的数据 `system_config`
--

INSERT INTO `system_config` (`id`, `name`, `value`) VALUES
(1, 'login_captcha', '1'),
(4, 'smtp-user', 'muyiboss@qq.com'),
(5, 'smtp-pass', '234'),
(6, 'smtp-port', '465'),
(7, 'smtp-host', 'smtp.qq.com'),
(8, 'file-type', '1'),
(9, 'file-endpoint', 'img.jianla.cn'),
(10, 'file-OssName', 'jianla-img'),
(11, 'file-accessKeyId', '123123s'),
(12, 'file-accessKeySecret', 'asdfasdfasdfsadfasdf');

-- --------------------------------------------------------

--
-- 表的结构 `system_file`
--

CREATE TABLE `system_file` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` char(50) NOT NULL COMMENT '文件名称',
  `href` varchar(255) DEFAULT NULL COMMENT '文件路径',
  `mime` char(50) NOT NULL COMMENT 'mime类型',
  `size` char(30) NOT NULL COMMENT '大小',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1本地2阿里云',
  `ext` char(10) DEFAULT NULL COMMENT '文件后缀',
  `create_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文件表';

--
-- 转储表的索引
--

--
-- 表的索引 `admin_admin`
--
ALTER TABLE `admin_admin`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `admin_admin_permission`
--
ALTER TABLE `admin_admin_permission`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `admin_admin_role`
--
ALTER TABLE `admin_admin_role`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `admin_log`
--
ALTER TABLE `admin_log`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `admin_multi`
--
ALTER TABLE `admin_multi`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `admin_permission`
--
ALTER TABLE `admin_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`);

--
-- 表的索引 `admin_role`
--
ALTER TABLE `admin_role`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `admin_role_permission`
--
ALTER TABLE `admin_role_permission`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `home_news`
--
ALTER TABLE `home_news`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `system_config`
--
ALTER TABLE `system_config`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `system_file`
--
ALTER TABLE `system_file`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `admin_admin`
--
ALTER TABLE `admin_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `admin_admin_permission`
--
ALTER TABLE `admin_admin_permission`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=17;

--
-- 使用表AUTO_INCREMENT `admin_admin_role`
--
ALTER TABLE `admin_admin_role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `admin_log`
--
ALTER TABLE `admin_log`
  MODIFY `id` int(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `admin_multi`
--
ALTER TABLE `admin_multi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用表AUTO_INCREMENT `admin_permission`
--
ALTER TABLE `admin_permission`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- 使用表AUTO_INCREMENT `admin_role`
--
ALTER TABLE `admin_role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `admin_role_permission`
--
ALTER TABLE `admin_role_permission`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=12;

--
-- 使用表AUTO_INCREMENT `home_news`
--
ALTER TABLE `home_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `system_config`
--
ALTER TABLE `system_config`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 使用表AUTO_INCREMENT `system_file`
--
ALTER TABLE `system_file`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
