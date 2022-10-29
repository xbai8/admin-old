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

 Date: 27/10/2022 01:43:25
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
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态：0禁用，1启用',
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
  `action_type` int(11) NULL DEFAULT NULL COMMENT '操作类型：0登录，1新增，2修改，3删除',
  `path` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '操作路由',
  `params` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '操作日志JSON格式',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-日志' ROW_FORMAT = DYNAMIC;

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
  `is_system` int(11) NULL DEFAULT 0 COMMENT '0不是系统，1是系统',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限-角色' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for php_system_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `php_system_auth_rule`;
CREATE TABLE `php_system_auth_rule`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `update_at` datetime NULL DEFAULT NULL,
  `path` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '请求地址：控制器/操作方法',
  `pid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '父级菜单地址',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '菜单名称',
  `sort` int(11) NULL DEFAULT 100 COMMENT '排序，值越大越靠后',
  `method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'GET' COMMENT '请求类型：GET，POST，DELETE，PUT，多个使用小写逗号隔开',
  `rule_type` int(3) NULL DEFAULT 0 COMMENT '规则类型：0：表单页，1：表格页，2：自定义组件，3目录',
  `auth_type` int(3) NULL DEFAULT 0 COMMENT '权限类型：0菜单，1按钮，2接口',
  `params` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '附带参数：规则类型=2，填写远程组件路径名称',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '图标类名',
  `show` int(11) NULL DEFAULT 1 COMMENT '是否显示：0隐藏，1显示（仅针对1-2级菜单）',
  `module` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'admin' COMMENT '模块名，多端：admin默认，根据客户端配置项控制',
  `is_login` int(3) NULL DEFAULT 0 COMMENT '是否需要登录：0否，1是',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限-规则' ROW_FORMAT = DYNAMIC;

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
  `is_system` int(11) NULL DEFAULT 0 COMMENT '是否系统：0否，1是',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '配置-分组' ROW_FORMAT = DYNAMIC;

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
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '附件-分类' ROW_FORMAT = DYNAMIC;

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
  `type` enum('input','select','radio','checkbox','textarea') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'input' COMMENT '表单类型',
  `extra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '选项数据',
  `placeholder` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '配置描述',
  `sort` int(11) NULL DEFAULT 100 COMMENT '配置排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '配置-配置项' ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
