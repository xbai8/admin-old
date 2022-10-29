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

 Date: 29/10/2022 17:09:26
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for php_system_admin
-- ----------------------------
DROP TABLE IF EXISTS `php_system_admin`;
CREATE TABLE `php_system_admin`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `update_at` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `role_id` int(11) NULL DEFAULT NULL COMMENT '管理员ID',
  `pid` int(11) NULL DEFAULT NULL COMMENT '上级管理员ID',
  `username` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户密码',
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '状态：0禁用，1启用',
  `nickname` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '昵称',
  `last_login_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` datetime NULL DEFAULT NULL COMMENT '最后登录时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间，删除则有数据',
  `headimg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '管理员头像',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限-管理员' ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-日志' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_admin_log
-- ----------------------------

-- ----------------------------
-- Table structure for php_system_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `php_system_admin_role`;
CREATE TABLE `php_system_admin_role`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '角色名称',
  `rule` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '角色权限规则',
  `is_system` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '0不是系统，1是系统',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限-角色' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_admin_role
-- ----------------------------
INSERT INTO `php_system_admin_role` VALUES (1, '2022-10-28 07:11:51', '2022-10-28 07:11:48', '系统管理员', 'Index/tabs,Index/index,Publics/login,Publics/site,Index/user,Index/menus', '1');

-- ----------------------------
-- Table structure for php_system_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `php_system_auth_rule`;
CREATE TABLE `php_system_auth_rule`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `update_at` datetime NULL DEFAULT NULL,
  `path` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '请求地址：控制器/操作方法',
  `namespace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '类所在命名空间',
  `pid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '父级菜单地址',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '菜单名称',
  `sort` int(11) NULL DEFAULT 100 COMMENT '排序，值越大越靠后',
  `method` enum('GET','POST','PUT','DELETE') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'GET' COMMENT '请求类型',
  `auth_rule` enum('layouts/index','','form/index','table/index','remote/index') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'layouts/index' COMMENT '规则类型：layouts/index一级目录，\'\' 二级目录',
  `auth_type` enum('0','1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '权限类型：0菜单，1按钮，2接口',
  `auth_params` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '附带参数：remote/index，填写远程组件路径名称',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '图标类名',
  `show` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '是否显示：0隐藏，1显示（仅针对1-2级菜单）',
  `is_login` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '是否需要登录：0否，1是',
  `is_system` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否系统：0否，1是',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `path`(`path`) USING BTREE COMMENT '唯一索引'
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限-规则' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_auth_rule
-- ----------------------------
INSERT INTO `php_system_auth_rule` VALUES (1, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'Index/tabs', '\\Hangpu8\\Admin\\controller\\', '', '首页', 100, 'GET', 'layouts/index', '0', '', 'hp-packaging', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (2, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'Index/index', '\\Hangpu8\\Admin\\controller\\', 'Index/tabs', '看板', 100, 'GET', 'remote/index', '0', '', '', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (3, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'Publics/login', '\\Hangpu8\\Admin\\controller\\', 'Index/tabs', '系统登录', 100, 'POST', '', '2', '', '', '0', '0', '1');
INSERT INTO `php_system_auth_rule` VALUES (4, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'Publics/site', '\\Hangpu8\\Admin\\controller\\', 'Index/tabs', '获取应用信息', 100, 'GET', '', '2', '', '', '0', '0', '1');
INSERT INTO `php_system_auth_rule` VALUES (5, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'Index/user', '\\Hangpu8\\Admin\\controller\\', 'Index/tabs', '获取管理员信息', 100, 'GET', '', '2', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (6, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'Index/menus', '\\Hangpu8\\Admin\\controller\\', 'Index/tabs', '获取菜单信息', 100, 'GET', '', '2', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (7, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'System/tabs', '\\Hangpu8\\Admin\\controller\\', '', '系统', 100, 'GET', 'layouts/index', '0', '', 'hp-packaging', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (8, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'Webconfig/tabs', '\\Hangpu8\\Admin\\controller\\', 'System/tabs', '配置项', 100, 'GET', '', '0', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (9, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'Auth/tabs', '\\Hangpu8\\Admin\\controller\\', 'System/tabs', '权限管理', 100, 'GET', '', '0', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (10, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'SystemWebconfig/index', '\\Hangpu8\\Admin\\controller\\', 'Webconfig/tabs', '系统配置', 100, 'GET', 'form/index', '0', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (11, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'SystemConfigGroup/index', '\\Hangpu8\\Admin\\controller\\', 'Webconfig/tabs', '配置分组', 100, 'GET', 'table/index', '0', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (12, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'SystemAuthRule/index', '\\Hangpu8\\Admin\\controller\\', 'Auth/tabs', '菜单管理', 100, 'GET', 'table/index', '0', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (13, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'SystemAuthRole/index', '\\Hangpu8\\Admin\\controller\\', 'Auth/tabs', '部门管理', 100, 'GET', 'table/index', '0', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (14, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'SystemAdmin/index', '\\Hangpu8\\Admin\\controller\\', 'Auth/tabs', '账户管理', 100, 'GET', 'table/index', '0', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (15, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'Plugin/tabs', '\\Hangpu8\\Admin\\controller\\', '', '功能', 100, 'GET', 'layouts/index', '0', '', 'hp-packaging', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (16, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'PluginApp/tabs', '\\Hangpu8\\Admin\\controller\\', 'Plugin/tabs', '应用插件', 100, 'GET', '', '0', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (17, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'Modules/tabs', '\\Hangpu8\\Admin\\controller\\', 'Plugin/tabs', '功能模块', 100, 'GET', '', '0', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (18, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'Plugin/index', '\\Hangpu8\\Admin\\controller\\', 'PluginApp/tabs', '插件中心', 100, 'GET', 'table/index', '0', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (20, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'Modules/index', '\\Hangpu8\\Admin\\controller\\', 'Modules/tabs', '一键功能', 100, 'GET', 'table/index', '0', '', '', '1', '1', '0');

-- ----------------------------
-- Table structure for php_system_config_group
-- ----------------------------
DROP TABLE IF EXISTS `php_system_config_group`;
CREATE TABLE `php_system_config_group`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分组名称',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '分组标识',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '图标类名',
  `sort` int(11) NULL DEFAULT 100 COMMENT '排序',
  `is_system` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否系统：0否，1是',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '配置-分组' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_config_group
-- ----------------------------
INSERT INTO `php_system_config_group` VALUES (1, '2022-10-29 17:08:56', '2022-10-29 17:08:56', '系统设置', 'system-set', '', 100, '1');

-- ----------------------------
-- Table structure for php_system_uploadify
-- ----------------------------
DROP TABLE IF EXISTS `php_system_uploadify`;
CREATE TABLE `php_system_uploadify`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL COMMENT '上传时间',
  `cid` int(11) NULL DEFAULT NULL COMMENT '分类ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '附件名称',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件地址',
  `format` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件格式',
  `size` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件大小',
  `width` int(11) NULL DEFAULT 0 COMMENT '宽度，单位：px（仅图片有效）',
  `height` int(11) NULL DEFAULT 0 COMMENT '高度，单位：px（仅图片有效',
  `adapter` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '选定器：oss阿里云，qiniu七牛云等等',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '附件-管理器' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_uploadify
-- ----------------------------

-- ----------------------------
-- Table structure for php_system_uploadify_cate
-- ----------------------------
DROP TABLE IF EXISTS `php_system_uploadify_cate`;
CREATE TABLE `php_system_uploadify_cate`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分类名称',
  `dir_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分类目录',
  `pid` int(11) NULL DEFAULT 0 COMMENT '父级ID',
  `is_system` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '系统分类',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '附件-分类' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_uploadify_cate
-- ----------------------------
INSERT INTO `php_system_uploadify_cate` VALUES (1, NULL, NULL, '系统附件', NULL, 0, '0');

-- ----------------------------
-- Table structure for php_system_webconfig
-- ----------------------------
DROP TABLE IF EXISTS `php_system_webconfig`;
CREATE TABLE `php_system_webconfig`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `cid` int(11) NULL DEFAULT NULL COMMENT '配置分组（外键）',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '标题名称',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '字段名称',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '数据值',
  `type` enum('input','select','radio','checkbox','textarea') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'input' COMMENT '表单类型',
  `extra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '选项数据',
  `placeholder` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '配置描述',
  `sort` int(11) NULL DEFAULT 100 COMMENT '配置排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '配置-配置项' ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
