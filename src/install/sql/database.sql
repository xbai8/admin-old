/*
 Navicat Premium Data Transfer

 Source Server         : HPAdmin8787
 Source Server Type    : MySQL
 Source Server Version : 50734
 Source Host           : 192.168.41.135:3306
 Source Schema         : hpadmin8787_com

 Target Server Type    : MySQL
 Target Server Version : 50734
 File Encoding         : 65001

 Date: 17/11/2022 08:03:58
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for php_system_admin
-- ----------------------------
DROP TABLE IF EXISTS `php_system_admin`;
CREATE TABLE `php_system_admin`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `role_id` int(11) NULL DEFAULT NULL COMMENT '所属部门',
  `pid` int(11) NULL DEFAULT NULL COMMENT '上级管理员ID',
  `username` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '登录账户',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户密码',
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '用户状态：0禁用，1启用',
  `nickname` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '用户昵称',
  `last_login_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` datetime NULL DEFAULT NULL COMMENT '最后登录时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间，删除则有数据',
  `headimg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '用户头像',
  `is_system` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否系统：0否，1是',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-管理员' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for php_system_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `php_system_admin_log`;
CREATE TABLE `php_system_admin_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `admin_id` int(11) NULL DEFAULT NULL COMMENT '管理员',
  `role_id` int(11) NULL DEFAULT NULL COMMENT '管理员角色',
  `action_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '本次操作菜单名称',
  `action_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '登录IP',
  `city_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '城市名',
  `isp_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '网络运营商',
  `action_type` enum('0','1','2','3') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '操作类型：0登录，1新增，2修改，3删除',
  `path` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '操作路由',
  `params` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '操作日志JSON格式',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-操作日志' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_admin_log
-- ----------------------------

-- ----------------------------
-- Table structure for php_system_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `php_system_admin_role`;
CREATE TABLE `php_system_admin_role`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `pid` int(11) NULL DEFAULT 0 COMMENT '上级角色，0顶级',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '部门名称',
  `rule` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '部门权限',
  `is_system` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否系统：0不是系统，1是系统',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-角色管理' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_admin_role
-- ----------------------------
INSERT INTO `php_system_admin_role` VALUES (1, '2022-10-28 07:11:51', '2022-11-16 11:30:39', 1, '系统管理员', 'Index/tabs,Index/index,Publics/login,Publics/site,Index/user,Index/menus,SystemConfigGroup/add,Publics/loginout', '1');

-- ----------------------------
-- Table structure for php_system_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `php_system_auth_rule`;
CREATE TABLE `php_system_auth_rule`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `update_at` datetime NULL DEFAULT NULL,
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'admin' COMMENT '模块名称',
  `path` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '请求地址：控制器/操作方法',
  `namespace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '命名空间',
  `pid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '父级菜单地址',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '菜单名称',
  `sort` int(11) NULL DEFAULT 0 COMMENT '菜单排序，值越大越靠后',
  `method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'GET' COMMENT '请求类型：GET,POST,PUT,DELETE',
  `auth_rule` enum('layouts/index','','form/index','table/index','remote/index') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'layouts/index' COMMENT '规则类型：layouts/index一级目录，\'\' 二级目录',
  `auth_type` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否接口：0否，1是',
  `auth_params` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '附带参数：remote/index，填写远程组件路径名称',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '图标类名',
  `show` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '是否显示：0隐藏，1显示（仅针对1-2级菜单）',
  `is_login` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '是否需要登录：0否，1是',
  `is_system` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否系统：0否，1是',
  `is_default` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '默认权限：0否，1是',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `path`(`path`) USING BTREE COMMENT '唯一索引'
) ENGINE = InnoDB AUTO_INCREMENT = 52 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-权限规则' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_auth_rule
-- ----------------------------
INSERT INTO `php_system_auth_rule` VALUES (1, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'hpadmin', 'Index/tabs', '\\Hangpu8\\Admin\\controller\\', '', '首页', 0, 'GET', 'layouts/index', '0', '', 'hp-packaging', '1', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (2, '2022-10-27 17:22:51', '2022-11-15 08:44:40', 'hpadmin', 'Index/index', '\\Hangpu8\\Admin\\controller\\', 'Index/tabs', '看板', 0, 'GET', 'remote/index', '0', '', '', '1', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (3, '2022-10-27 17:22:51', '2022-11-15 09:07:27', 'hpadmin', 'Publics/login', '\\Hangpu8\\Admin\\controller\\', 'Index/tabs', '系统登录', 0, 'POST', '', '1', '', '', '0', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (4, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'hpadmin', 'Publics/site', '\\Hangpu8\\Admin\\controller\\', 'Index/tabs', '获取应用信息', 0, 'GET', '', '1', '', '', '0', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (5, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'hpadmin', 'Index/user', '\\Hangpu8\\Admin\\controller\\', 'Index/tabs', '获取管理员信息', 0, 'GET', '', '1', '', '', '0', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (6, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'hpadmin', 'Index/menus', '\\Hangpu8\\Admin\\controller\\', 'Index/tabs', '获取菜单信息', 0, 'GET', '', '1', '', '', '0', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (7, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'hpadmin', 'System/tabs', '\\Hangpu8\\Admin\\controller\\', '', '系统', 0, 'GET', 'layouts/index', '0', '', 'hp-packaging', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (8, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'hpadmin', 'Webconfig/tabs', '\\Hangpu8\\Admin\\controller\\', 'System/tabs', '配置项', 0, 'GET', '', '0', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (9, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'hpadmin', 'Auth/tabs', '\\Hangpu8\\Admin\\controller\\', 'System/tabs', '权限管理', 0, 'GET', '', '0', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (10, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'hpadmin', 'SystemWebconfig/form', '\\Hangpu8\\Admin\\controller\\', 'Webconfig/tabs', '系统配置', 0, 'GET', 'form/index', '0', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (11, '2022-10-27 17:22:51', '2022-11-16 15:31:41', 'hpadmin', 'SystemConfigGroup/index', '\\Hangpu8\\Admin\\controller\\', 'Webconfig/tabs', '配置分组', 0, 'GET', 'table/index', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (12, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'hpadmin', 'SystemAuthRule/index', '\\Hangpu8\\Admin\\controller\\', 'Auth/tabs', '菜单管理', 0, 'GET', 'table/index', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (13, '2022-10-27 17:22:51', '2022-11-15 14:10:10', 'hpadmin', 'SystemAdminRole/index', '\\Hangpu8\\Admin\\controller\\', 'Auth/tabs', '部门管理', 0, 'GET', 'table/index', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (14, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'hpadmin', 'SystemAdmin/index', '\\Hangpu8\\Admin\\controller\\', 'Auth/tabs', '账户管理', 0, 'GET', 'table/index', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (15, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'hpadmin', 'Plugin/tabs', '\\Hangpu8\\Admin\\controller\\', '', '功能', 0, 'GET', 'layouts/index', '0', '', 'hp-packaging', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (16, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'hpadmin', 'PluginApp/tabs', '\\Hangpu8\\Admin\\controller\\', 'Plugin/tabs', '应用插件', 0, 'GET', '', '0', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (17, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'hpadmin', 'Modules/tabs', '\\Hangpu8\\Admin\\controller\\', 'Plugin/tabs', '功能模块', 0, 'GET', '', '0', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (18, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'hpadmin', 'Plugin/index', '\\Hangpu8\\Admin\\controller\\', 'PluginApp/tabs', '插件中心', 0, 'GET', 'table/index', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (20, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'hpadmin', 'Modules/index', '\\Hangpu8\\Admin\\controller\\', 'Modules/tabs', '一键功能', 0, 'GET', 'table/index', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (21, '2022-10-27 17:22:51', '2022-11-16 15:34:52', 'hpadmin', 'SystemConfigGroup/add', '\\Hangpu8\\Admin\\controller\\', 'SystemConfigGroup/index', '添加配置分组', 0, 'GET,POST', 'form/index', '1', '', '', '0', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (22, '2022-10-27 17:22:51', '2022-11-16 15:35:00', 'hpadmin', 'SystemConfigGroup/edit', '\\Hangpu8\\Admin\\controller\\', 'SystemConfigGroup/index', '修改配置分组', 0, 'GET,PUT', 'form/index', '1', '', '', '0', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (23, '2022-10-27 17:22:51', '2022-11-16 15:35:07', 'hpadmin', 'SystemConfigGroup/del', '\\Hangpu8\\Admin\\controller\\', 'SystemConfigGroup/index', '删除配置分组', 0, 'DELETE', '', '1', '', '', '0', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (24, '2022-10-27 17:22:51', '2022-11-16 15:21:49', 'hpadmin', 'SystemWebconfig/index', '\\Hangpu8\\Admin\\controller\\', 'Webconfig/tabs', '配置项列表', 0, 'GET', 'table/index', '1', '', '', '0', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (28, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'hpadmin', 'SystemAuthRule/add', '\\Hangpu8\\Admin\\controller\\', 'SystemAuthRule/index', '添加权限菜单', 0, 'GET,POST', 'form/index', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (29, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'hpadmin', 'SystemAuthRule/edit', '\\Hangpu8\\Admin\\controller\\', 'SystemAuthRule/index', '修改权限菜单', 0, 'GET,PUT', 'form/index', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (30, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'hpadmin', 'SystemAuthRule/del', '\\Hangpu8\\Admin\\controller\\', 'SystemAuthRule/index', '删除权限菜单', 0, 'DELETE', 'form/index', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (32, '2022-11-15 02:42:33', '2022-11-15 14:10:00', 'hpadmin', 'SystemAdminRole/add', '\\Hangpu8\\Admin\\controller\\', 'SystemAdminRole/index', '添加部门', 0, 'GET,POST', 'form/index', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (33, '2022-11-15 02:44:31', '2022-11-15 14:09:56', 'hpadmin', 'SystemAdminRole/edit', '\\Hangpu8\\Admin\\controller\\', 'SystemAdminRole/index', '修改部门', 0, 'GET,PUT', 'form/index', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (34, '2022-11-15 02:46:04', '2022-11-15 14:09:50', 'hpadmin', 'SystemAdminRole/del', '\\Hangpu8\\Admin\\controller\\', 'SystemAdminRole/index', '删除部门', 0, 'DELETE', '', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (35, '2022-11-15 02:48:09', '2022-11-15 02:48:09', 'hpadmin', 'SystemAdmin/add', '\\Hangpu8\\Admin\\controller\\', 'SystemAdmin/index', '添加账户', 0, 'GET,POST', 'form/index', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (36, '2022-11-15 02:49:00', '2022-11-15 02:49:00', 'hpadmin', 'SystemAdmin/edit', '\\Hangpu8\\Admin\\controller\\', 'SystemAdmin/index', '修改账户', 0, 'GET,PUT', 'form/index', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (37, '2022-11-15 02:49:46', '2022-11-15 02:49:46', 'hpadmin', 'SystemAdmin/del', '\\Hangpu8\\Admin\\controller\\', 'SystemAdmin/index', '删除账户', 0, 'DELETE', '', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (38, '2022-11-15 09:23:53', '2022-11-15 14:09:44', 'hpadmin', 'SystemAdminRole/auth', '\\Hangpu8\\Admin\\controller\\', 'SystemAuthRole/index', '设置权限', 0, 'GET,PUT', 'form/index', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (39, '2022-11-16 15:19:24', '2022-11-16 15:27:45', 'hpadmin', 'Plugin/create', '\\Hangpu8\\Admin\\controller\\', 'PluginApp/tabs', '创建插件', 0, 'GET,POST', '', '1', '', '', '0', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (40, '2022-11-16 15:36:42', '2022-11-16 15:37:25', 'hpadmin', 'SystemConfigGroup/table', '', 'SystemConfigGroup/index', '配置分组列表', 0, 'GET', '', '0', '', '', '0', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (41, '2022-11-16 19:33:49', '2022-11-16 19:36:59', 'hpadmin', 'Uploadify/tabs', '\\Hangpu8\\Admin\\controller\\', 'System/tabs', '附件模块', 0, 'GET', '', '0', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (42, '2022-11-16 19:34:37', '2022-11-16 19:57:12', 'hpadmin', 'SystemUpload/index', '\\Hangpu8\\Admin\\controller\\', 'Uploadify/tabs', '附件管理', 0, 'GET', 'table/index', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (43, '2022-11-16 19:35:31', '2022-11-16 19:57:06', 'hpadmin', 'SystemUpload/upload', '\\Hangpu8\\Admin\\controller\\', 'SystemUpload/index', '上传附件', 0, 'POST', '', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (44, '2022-11-16 19:36:17', '2022-11-16 19:57:00', 'hpadmin', 'SystemUpload/del', '\\Hangpu8\\Admin\\controller\\', 'SystemUpload/index', '删除附件', 0, 'DELETE', '', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (45, '2022-11-16 19:38:19', '2022-11-16 19:56:54', 'hpadmin', 'SystemUpload/table', '\\Hangpu8\\Admin\\controller\\', 'SystemUpload/index', '附件列表', 0, 'GET', '', '0', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (46, '2022-11-16 19:41:08', '2022-11-16 19:55:32', 'hpadmin', 'SystemUploadCate/index', '\\Hangpu8\\Admin\\controller\\', 'Uploadify/tabs', '附件分类', 0, 'GET', 'table/index', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (47, '2022-11-16 19:41:58', '2022-11-16 19:55:26', 'hpadmin', 'SystemUploadCate/add', '\\Hangpu8\\Admin\\controller\\', 'SystemUploadCate/index', '添加附件分类', 0, 'GET,POST', 'form/index', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (48, '2022-11-16 19:42:37', '2022-11-16 19:55:21', 'hpadmin', 'SystemUploadCate/edit', '\\Hangpu8\\Admin\\controller\\', 'SystemUploadCate/index', '修改附件分类', 0, 'GET,PUT', 'form/index', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (49, '2022-11-16 19:43:35', '2022-11-16 19:55:15', 'hpadmin', 'SystemUploadCate/del', '\\Hangpu8\\Admin\\controller\\', 'SystemUploadCate/index', '删除附件分类', 0, 'DELETE', '', '1', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (50, '2022-11-16 19:45:01', '2022-11-16 19:55:10', 'hpadmin', 'SystemUploadCate/table', '\\Hangpu8\\Admin\\controller\\', 'SystemUploadCate/index', '附件分类列表', 0, 'GET', '', '0', '', '', '1', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (51, '2022-11-16 23:04:14', '2022-11-16 23:04:14', 'hpadmin', 'Publics/loginout', '\\Hangpu8\\Admin\\controller\\', 'Index/tabs', '退出登录', 0, 'DELETE', '', '1', '', '', '0', '1', '1', '1');

-- ----------------------------
-- Table structure for php_system_config_group
-- ----------------------------
DROP TABLE IF EXISTS `php_system_config_group`;
CREATE TABLE `php_system_config_group`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分组名称',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '分组标识',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '图标类名',
  `sort` int(11) NULL DEFAULT 0 COMMENT '排序',
  `is_system` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否系统：0否，1是',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-配置分组' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_config_group
-- ----------------------------
INSERT INTO `php_system_config_group` VALUES (1, '2022-10-29 17:08:56', '2022-11-16 15:53:12', '系统设置', 'system-set', '6666', 0, '1');

-- ----------------------------
-- Table structure for php_system_upload
-- ----------------------------
DROP TABLE IF EXISTS `php_system_upload`;
CREATE TABLE `php_system_upload`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '上传时间',
  `cid` int(11) NULL DEFAULT NULL COMMENT '所属分类',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '附件名称',
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件名称',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件地址',
  `format` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件格式',
  `size` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件大小',
  `width` int(11) NULL DEFAULT 0 COMMENT '宽度，单位：px（仅图片有效）',
  `height` int(11) NULL DEFAULT 0 COMMENT '高度，单位：px（仅图片有效',
  `adapter` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '选定器：oss阿里云，qiniu七牛云等等',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-附件管理器' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for php_system_upload_cate
-- ----------------------------
DROP TABLE IF EXISTS `php_system_upload_cate`;
CREATE TABLE `php_system_upload_cate`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分类名称',
  `dir_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分类目录',
  `sort` int(11) NULL DEFAULT 0 COMMENT '分类排序',
  `is_system` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否系统：0否，1是',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-附件分类' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_upload_cate
-- ----------------------------
INSERT INTO `php_system_upload_cate` VALUES (1, '2022-11-16 20:06:19', '2022-11-17 06:12:15', '系统附件', 'system_name', 0, '1');

-- ----------------------------
-- Table structure for php_system_webconfig
-- ----------------------------
DROP TABLE IF EXISTS `php_system_webconfig`;
CREATE TABLE `php_system_webconfig`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `cid` int(11) NULL DEFAULT NULL COMMENT '配置分组（外键）',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '标题名称',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '字段名称',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '数据值',
  `type` enum('input','select','radio','checkbox','textarea') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'input' COMMENT '表单类型',
  `extra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '选项数据',
  `placeholder` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '配置描述',
  `sort` int(11) NULL DEFAULT 0 COMMENT '配置排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-配置项' ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
