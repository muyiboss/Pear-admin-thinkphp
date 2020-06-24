-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2020-06-24 11:27:10
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
-- 数据库： `www.2.com`
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
(1, 'admin', '04f2ee7178b2cee169247b499794d092', '超级管理员', 'LEYYSqqpsOYOeiOH', 1, '2020-02-27 11:26:23', '2020-06-24 03:22:37'),
(30, '123', '9761d3a519ae9722d8440981c1e12c90', '123', 'mQbwE8QAQEl00SEE', 1, '2020-06-23 13:21:08', '2020-06-23 14:51:38'),
(31, '333', 'a3c238873e17bd5e40a7466f1ad391a5', '33', 'JatxXxto7x6Zr8DN', 1, '2020-06-23 13:21:31', '2020-06-24 00:15:34');

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

-- --------------------------------------------------------

--
-- 表的结构 `admin_permission`
--

CREATE TABLE `admin_permission` (
  `id` int(10) UNSIGNED NOT NULL,
  `admin_id` int(11) NOT NULL COMMENT '管理ID',
  `permission_id` int(11) NOT NULL COMMENT '权限ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户-权限中间表';

--
-- 转存表中的数据 `admin_permission`
--

INSERT INTO `admin_permission` (`id`, `admin_id`, `permission_id`) VALUES
(17, 31, 1),
(18, 31, 2),
(19, 31, 3),
(20, 31, 4),
(21, 31, 5),
(22, 31, 6),
(23, 31, 7),
(24, 31, 8),
(25, 31, 9),
(26, 31, 10),
(27, 31, 11),
(28, 31, 12),
(29, 31, 13),
(30, 31, 14),
(31, 31, 15),
(32, 31, 16),
(33, 31, 31);

-- --------------------------------------------------------

--
-- 表的结构 `admin_role`
--

CREATE TABLE `admin_role` (
  `id` int(10) UNSIGNED NOT NULL,
  `admin_id` int(11) NOT NULL COMMENT '管理ID',
  `role_id` int(11) NOT NULL COMMENT '角色ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理-角色中间表';

--
-- 转存表中的数据 `admin_role`
--

INSERT INTO `admin_role` (`id`, `admin_id`, `role_id`) VALUES
(1, 25, 2),
(2, 25, 2),
(4, 30, 1);

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
(1, 0, '后台管理', '/', 'layui-icon layui-icon-username', 0, 99),
(2, 1, '管理员', '/admin/index', '', 1, 1),
(3, 2, '添加', '/admin/add', '', 1, 99),
(4, 2, '编辑', '/admin/edit', '', 3, 99),
(5, 2, '删除', '/admin/del', '', 4, 99),
(6, 2, '分配角色', '/admin/role', '', 1, 99),
(7, 2, '分配权限', '/admin/permission', '', 1, 99),
(8, 1, '角色管理', '/role/index', '', 1, 2),
(9, 8, '添加', '/role/add', '', 1, 99),
(10, 8, '编辑', '/role/edit', '', 1, 99),
(11, 8, '删除', '/role/del', '', 1, 99),
(12, 8, '分配权限', '/role/permission', '', 1, 99),
(13, 1, '权限管理', '/permission/index', '', 1, 3),
(14, 13, '添加', '/permission/add', '', 2, 99),
(15, 13, '编辑', '/permission/edit', '', 3, 99),
(16, 13, '删除', '/permission/del', '', 4, 99),
(17, 0, '系统管理', '/', 'layui-icon layui-icon-set', 0, 99),
(18, 17, '网站管理', '/site/index', '', 1, 99),
(19, 0, '项目管理', '/', 'layui-icon layui-icon-app', 0, 99),
(31, 1, '管理日志', '/admin/log', '', 1, 4);

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
(1, '超级管理员', '用于所有管理权限!', '2020-02-28 06:00:39', '2020-02-28 06:00:50');

-- --------------------------------------------------------

--
-- 表的结构 `role_permission`
--

CREATE TABLE `role_permission` (
  `id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(11) NOT NULL COMMENT '权限ID',
  `role_id` int(11) NOT NULL COMMENT '角色ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色-权限中间表';

--
-- 转存表中的数据 `role_permission`
--

INSERT INTO `role_permission` (`id`, `permission_id`, `role_id`) VALUES
(33, 1, 1),
(34, 2, 1),
(35, 3, 1),
(36, 4, 1),
(37, 5, 1),
(38, 6, 1),
(39, 7, 1),
(40, 8, 1),
(41, 9, 1),
(42, 10, 1),
(43, 11, 1),
(44, 12, 1),
(45, 13, 1),
(46, 14, 1),
(47, 15, 1),
(48, 16, 1);

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
(8, 'file-type', '2'),
(9, 'file-endpoint', ''),
(10, 'file-OssName', ''),
(11, 'file-accessKeyId', ''),
(12, 'file-accessKeySecret', ''),
(13, 'logo', '');

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
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- 使用表AUTO_INCREMENT `admin_log`
--
ALTER TABLE `admin_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `admin_permission`
--
ALTER TABLE `admin_permission`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- 使用表AUTO_INCREMENT `admin_role`
--
ALTER TABLE `admin_role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- 使用表AUTO_INCREMENT `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `role_permission`
--
ALTER TABLE `role_permission`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- 使用表AUTO_INCREMENT `site`
--
ALTER TABLE `site`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
