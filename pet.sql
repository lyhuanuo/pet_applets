/*
 Navicat Premium Data Transfer

 Source Server         : pet_applets
 Source Server Type    : MySQL
 Source Server Version : 50649
 Source Host           : 120.53.10.183:3306
 Source Schema         : pet

 Target Server Type    : MySQL
 Target Server Version : 50649
 File Encoding         : 65001

 Date: 20/11/2020 16:22:44
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pet_admin
-- ----------------------------
DROP TABLE IF EXISTS `pet_admin`;
CREATE TABLE `pet_admin`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '管理员id',
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '管理员名称',
  `password` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `avatar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `phone` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `sex` tinyint(1) NOT NULL DEFAULT 0 COMMENT '性别：0保密1男2女',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：0禁用1正常',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `ctime` int(10) NOT NULL DEFAULT 0 COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '管理员表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pet_admin
-- ----------------------------
INSERT INTO `pet_admin` VALUES (1, '佳旺宠物用品厂', '$2y$10$8I/b63s8lINOdMrF7o6MpeqN1w/xuAi8rKVf8A0M4w/oHb6D2XFNC', '/uploads/images/fe78207b9702d5de3e6be162668c9d1d.png', '15094554466', 1, 1, 'dasdasdaadasda', 0);

-- ----------------------------
-- Table structure for pet_admin_logs
-- ----------------------------
DROP TABLE IF EXISTS `pet_admin_logs`;
CREATE TABLE `pet_admin_logs`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `admin_id` int(10) NOT NULL DEFAULT 0 COMMENT '管理员id',
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '管理员名称',
  `login_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '登入ip',
  `log_url` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'URL',
  `log_info` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `ctime` int(10) NOT NULL DEFAULT 0 COMMENT '操作时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '管理员日志表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for pet_article
-- ----------------------------
DROP TABLE IF EXISTS `pet_article`;
CREATE TABLE `pet_article`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `title` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型：1操作指南2用户协议',
  `ctime` int(10) NOT NULL DEFAULT 0 COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '文章信息管理' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pet_article
-- ----------------------------
INSERT INTO `pet_article` VALUES (1, '操作指南', '<p>1.打开微信扫一扫</p><p>2.绑定信息添加宠物名字性别，返家寄语。</p>', 1, 0);
INSERT INTO `pet_article` VALUES (3, '用户协议', '<p style=\"text-align: center;\"><a><b><span style=\"font-size: 13.5pt; color: black;\">用户协议和法律协议</span></b></a></p><p>本协议为您与本小程序管理者之间所订立的契约，具有合同的法律效力，请您仔细阅读。</p><p><b><span style=\"font-size: 14pt;\">一、 本协议内容、生效、变更</span></b></p><p>本协议内容包括协议正文及所有本小程序已经发布的或将来可能发布的各类规则。所有规则为本协议不可分割的组成部分，与协议正文具有同等法律效力。如您对协议有任何疑问，应向本小程序咨询。您在同意所有协议条款并完成注册程序，才能成为本站的正式用户，您点击“我以阅读并同意本小程序用户协议和法律协议”按钮后，本协议即生效，对双方产生约束力。</p><p>只要您使用本小程序平台服务，则本协议即对您产生约束，届时您不应以未阅读本协议的内容或者未获得本小程序对您问询的解答等理由，主张本协议无效，或要求撤销本协议。您确认：本协议条款是处理双方权利义务的契约，始终有效，法律另有强制性规定或双方另有特别约定的，依其规定。您承诺接受并遵守本协议的约定。如果您不同意本协议的约定，您应立即停止注册程序或停止使用本小程序平台服务。本小程序有权根据需要不定期地制订、修改本协议及/或各类规则，并在本小程序平台公示，不再另行单独通知用户。变更后的协议和规则一经在网站公布，立即生效。如您不同意相关变更，应当立即停止使用本小程序平台服务。您继续使用本小程序平台服务的，即表明您接受修订后的协议和规则。</p><p><b><span style=\"font-size: 14pt;\">二、 注册</span></b></p><p>注册资格用户须具有法定的相应权利能力和行为能力的自然人、法人或其他组织，能够独立承担法律责任。您完成注册程序或其他本小程序平台同意的方式实际使用本平台服务时，即视为您确认自己具备主体资格，能够独立承担法律责任。若因您不具备主体资格，而导致的一切后果，由您及您的监护人自行承担。</p><p><b><span style=\"font-size: 14pt;\">注册资料</span></b></p><p>2.1用户应自行诚信向本站提供注册资料，用户同意其提供的注册资料真实、准确、完整、合法有效，用户注册资料如有变动的，应及时更新其注册资料。如果用户提供的注册资料不合法、不真实、不准确、不详尽的，用户需承担因此引起的相应责任及后果，并且本小程序保留终止用户使用本平台各项服务的权利。</p><p>2.2用户在本站进行浏览等活动时，涉及用户真实姓名/名称、通信地址、联系电话、电子邮箱等隐私信息的，本站将予以严格保密，除非得到用户的授权或法律另有规定，本站不会向外界披露用户隐私信息。</p><p><b><span style=\"font-size: 14pt;\">账户</span></b></p><p>3.1您注册成功后，即成为本小程序平台的会员，将持有本小程序平台唯一编号的账户信息，您可以根据本站规定改变您的密码。</p><p>3.2您设置的姓名为真实姓名，不得侵犯或涉嫌侵犯他人合法权益。否则，本小程序有权终止向您提供服务，注销您的账户。账户注销后，相应的会员名将开放给任意用户注册登记使用。</p><p>3.3您应谨慎合理的保存、使用您的会员名和密码，应对通过您的会员名和密码实施的行为负责。除非有法律规定或司法裁定，且征得本小程序的同意，否则，会员名和密码不得以任何方式转让、赠与或继承（与账户相关的财产权益除外）。</p><p>3.4用户不得将在本站注册获得的账户借给他人使用，否则用户应承担由此产生的全部责任，并与实际使用人承担连带责任。</p><p>3.5如果发现任何非法使用等可能危及您的账户安全的情形时，您应当立即以有效方式通知本小程序要求暂停相关服务，并向公安机关报案。您理解本小程序对您的请求采取行动需要合理时间，本小程序对在采取行动前已经产生的后果（包括但不限于您的任何损失）不承担任何责任。</p><p><b><span style=\"font-size: 14pt;\">用户信息的合理使用</span></b></p><p>4.1您同意本小程序平台拥有通过邮件、短信电话等形式，向在本站注册用户发送信息等告知信息的权利。</p><p>4.2您了解并同意，本小程序有权应国家司法、行政等主管部门的要求，向其提供您在本小程序平台填写的注册信息和交易记录等必要信息。如您涉嫌侵犯他人知识产权，则本小程序亦有权在初步判断涉嫌侵权行为存在的情况下，向权利人提供您必要的身份信息。</p><p>4.3用户同意本小程序有权使用用户的注册信息、用户名、密码等信息，登陆进入用户的注册账户，进行证据保全，包括但不限于公证、见证等。</p><p><b><span style=\"font-size: 14pt;\">免责条款</span></b></p><p>5.1 本平台仅提供信息对接，发生一切纠纷问题皆与本平台无关，请通过仲裁部门维护各自权益。</p><p>&nbsp;</p>', 2, 0);
INSERT INTO `pet_article` VALUES (4, '码被占用怎么办？', '<p>如果刚收的产品码被占用，第一时间联系，我的订单找到客服，会给您解决问题。<br><br></p>', 1, 1598710780);
INSERT INTO `pet_article` VALUES (5, '返家寄语模板', '<p>&nbsp; &nbsp; &nbsp; &nbsp;1.我走丢了，我想回家，请您给我的妈妈打电话，我妈妈会酬谢您的。</p><p>　　2.他是我们的最亲爱的宝贝，是我们的家人，我们很爱他~~迷路送返，定有重谢！</p><p>　　3.麻麻是美女，我带你去看，送我回家请您吃饭饭。</p><p>　　4.我迷路了，请好心人联系我妈妈，非常感谢您。</p><p>　　5.饭量贼大，活动贼猛，拉屎贼多，不宜长期饲养，送回重谢。我老想家了。</p><p>　　6.我的特长一挖、二咬、三狼嚎 迷路送返，定有酬谢</p><p>　　7.我想拔拔了，请好心人送我回家。</p><p>　　8.我是妈妈的宝贝，请你送我回家</p>', 1, 1598711694);

-- ----------------------------
-- Table structure for pet_codes
-- ----------------------------
DROP TABLE IF EXISTS `pet_codes`;
CREATE TABLE `pet_codes`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `code_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '二维码编号',
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '二维码',
  `picture` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态：0未使用 1已使用',
  `binding_time` int(10) NOT NULL DEFAULT 0 COMMENT '绑定时间',
  `member_id` int(11) NOT NULL COMMENT '所属会户',
  `ctime` int(10) NOT NULL DEFAULT 0 COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code_number`(`code_number`) USING BTREE COMMENT '编码唯一索引'
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '二维码管理' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pet_codes
-- ----------------------------
INSERT INTO `pet_codes` VALUES (1, 'DAF00833', '/uploads/qr/20201104/code/DAF00833.png', '/uploads/qr/20201104/picture/DAF00833.png', 1, 1604454650, 11, 1604453750);
INSERT INTO `pet_codes` VALUES (2, 'DAF24673', '/uploads/qr/20201104/code/DAF24673.png', '/uploads/qr/20201104/picture/DAF24673.png', 0, 0, 0, 1604453750);
INSERT INTO `pet_codes` VALUES (3, 'DAF54500', '/uploads/qr/20201104/code/DAF54500.png', '/uploads/qr/20201104/picture/DAF54500.png', 0, 0, 0, 1604453750);
INSERT INTO `pet_codes` VALUES (4, 'DAF51102', '/uploads/qr/20201104/code/DAF51102.png', '/uploads/qr/20201104/picture/DAF51102.png', 0, 0, 0, 1604453750);
INSERT INTO `pet_codes` VALUES (5, 'DAF24256', '/uploads/qr/20201104/code/DAF24256.png', '/uploads/qr/20201104/picture/DAF24256.png', 0, 0, 0, 1604453750);
INSERT INTO `pet_codes` VALUES (6, 'DAF41736', '/uploads/qr/20201104/code/DAF41736.png', '/uploads/qr/20201104/picture/DAF41736.png', 0, 0, 0, 1604453750);
INSERT INTO `pet_codes` VALUES (7, 'DAF60384', '/uploads/qr/20201104/code/DAF60384.png', '/uploads/qr/20201104/picture/DAF60384.png', 0, 0, 0, 1604453750);
INSERT INTO `pet_codes` VALUES (8, 'DAF46330', '/uploads/qr/20201104/code/DAF46330.png', '/uploads/qr/20201104/picture/DAF46330.png', 0, 0, 0, 1604453750);
INSERT INTO `pet_codes` VALUES (9, 'DAF59193', '/uploads/qr/20201104/code/DAF59193.png', '/uploads/qr/20201104/picture/DAF59193.png', 0, 0, 0, 1604453750);
INSERT INTO `pet_codes` VALUES (10, 'DAF95372', '/uploads/qr/20201104/code/DAF95372.png', '/uploads/qr/20201104/picture/DAF95372.png', 0, 0, 0, 1604453750);

-- ----------------------------
-- Table structure for pet_config
-- ----------------------------
DROP TABLE IF EXISTS `pet_config`;
CREATE TABLE `pet_config`  (
  `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '配置标识',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '配置名称',
  `value` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '配置值',
  `values` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '可选值 以 逗号 隔开',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型 ：1 input 2 checkbox 3radio 4 select 5textarea6file ',
  `sort` tinyint(3) NOT NULL DEFAULT 0 COMMENT '排序',
  `ctime` int(10) NOT NULL DEFAULT 0 COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `key`(`key`) USING BTREE COMMENT '配置标志'
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '网站配置表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pet_config
-- ----------------------------
INSERT INTO `pet_config` VALUES (1, 'site_name', '网站名称', '护宠码', '', 1, 0, 1597489629);
INSERT INTO `pet_config` VALUES (2, 'SMSAPPID', '腾讯邮箱appid', '1400420456', '', 1, 0, 1597629093);
INSERT INTO `pet_config` VALUES (4, 'SMSAPPKEY', '腾讯短信appkey', '64b64e0e5e1097e54676842900988b9a', '', 1, 0, 1597629338);
INSERT INTO `pet_config` VALUES (5, 'SMSTEMPLATEID', '腾讯短信息模板id', '709540', '', 1, 0, 1597629390);
INSERT INTO `pet_config` VALUES (6, 'SMSSIGN', '腾讯短信签名', '佳旺宠物用品厂', '', 1, 0, 1597629430);

-- ----------------------------
-- Table structure for pet_download_log
-- ----------------------------
DROP TABLE IF EXISTS `pet_download_log`;
CREATE TABLE `pet_download_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `code_date` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '二维码日期',
  `ctime` int(10) NOT NULL DEFAULT 0 COMMENT '下载时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '二维码下载记录' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for pet_info
-- ----------------------------
DROP TABLE IF EXISTS `pet_info`;
CREATE TABLE `pet_info`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `code_id` int(11) NOT NULL DEFAULT 0 COMMENT '二维码id',
  `code_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '二维码编号',
  `member_id` int(11) NOT NULL DEFAULT 0 COMMENT '用户id',
  `nickname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户昵称',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '宠物姓名',
  `type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '宠物品种',
  `img` varchar(800) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片',
  `birthday` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '小红屋生日',
  `sex` tinyint(1) NOT NULL DEFAULT 1 COMMENT '性别:1男性2女性',
  `age` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '年龄',
  `phone` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `wx` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信号',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '返家寄语',
  `relation` tinyint(1) NOT NULL DEFAULT 0 COMMENT '关联状态：0未关联1已关联',
  `ctime` int(10) NOT NULL DEFAULT 0 COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '宠物管理表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pet_info
-- ----------------------------
INSERT INTO `pet_info` VALUES (1, 0, 'DAF00833', 11, '刚子', '五子棋', '泰迪', '/uploads/pet/images/d1968142581e62b4f95d68a7a892e128.jpg,/uploads/pet/images/d8cb30daac2bd0211d26896ab4ca0df6.jpg,/uploads/pet/images/a0f7b07dc50d90bc3d64e5a3695ab5ba.jpg', '2020-11-04', 2, '', '13011118090', '13011118090', '他是我们的最亲爱的宝贝，是我们的家人，我们很爱他~~迷路送返，定有重谢！', 0, 1604454650);

-- ----------------------------
-- Table structure for pet_info_codes
-- ----------------------------
DROP TABLE IF EXISTS `pet_info_codes`;
CREATE TABLE `pet_info_codes`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `code_id` int(10) NOT NULL DEFAULT 0 COMMENT '二维码id',
  `pet_id` int(10) NOT NULL DEFAULT 0 COMMENT '宠物id',
  `ctime` int(10) NOT NULL DEFAULT 0 COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pet_info_codes
-- ----------------------------
INSERT INTO `pet_info_codes` VALUES (1, 1, 1, 1604454650);

-- ----------------------------
-- Table structure for pet_lost_info
-- ----------------------------
DROP TABLE IF EXISTS `pet_lost_info`;
CREATE TABLE `pet_lost_info`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `pet_id` int(10) NOT NULL DEFAULT 0 COMMENT '宠物ID',
  `member_id` int(10) NOT NULL COMMENT '会员ID',
  `latitude` float(10, 6) NOT NULL DEFAULT 0.000000 COMMENT '纬度',
  `longitude` float(10, 6) NOT NULL DEFAULT 0.000000 COMMENT '经度',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '丢失地址',
  `lost_time` int(10) NOT NULL DEFAULT 0 COMMENT '丢失时间',
  `lost_img` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '丢失二维码',
  `amount` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '悬赏金额',
  `phone` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '联系手机号',
  `wx` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '联系微信号',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态：0丢失1已找到',
  `ctime` int(10) NOT NULL DEFAULT 0 COMMENT '添加时间',
  `utime` int(10) NOT NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '宠物丢失管理' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pet_lost_info
-- ----------------------------
INSERT INTO `pet_lost_info` VALUES (1, 1, 62, 0.000000, 0.000000, '兴山区沟北街道公助街', 1604455140, '/uploads/lost_img/lost_pet1.png', 10000.00, '13011118090', '13011118090', 0, 1604455177, 0);

-- ----------------------------
-- Table structure for pet_member
-- ----------------------------
DROP TABLE IF EXISTS `pet_member`;
CREATE TABLE `pet_member`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `openid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'openid',
  `nickname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `sex` tinyint(1) NOT NULL DEFAULT 0 COMMENT '性别：0未知1男2女',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `realname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '真实姓名',
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '城市',
  `province` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '省份',
  `country` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '国家',
  `phone` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `wx` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信号',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：0禁用1正常',
  `member_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '用户类型：0微信 1支付宝',
  `ctime` int(10) NOT NULL DEFAULT 0 COMMENT '添加时间',
  `utime` int(10) NOT NULL DEFAULT 0 COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `openid`(`openid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 66 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '二维码绑定用户管理表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pet_member
-- ----------------------------
INSERT INTO `pet_member` VALUES (5, 'ojxwx5cASs_EEAhzFXrCmjgHs2tg', '翻就相思结', 1, 'https://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eq5E8fic7GQDgZwTYIruGdQvBdXNfog6qLKL9UzBPgicma5CpL7poaSRHggV9SibN5QmubadEskdM2Zg/132', '', 'Guangzhou', 'Guangdong', 'China', '18320304907', '', '', 1, 0, 0, 1603764048);
INSERT INTO `pet_member` VALUES (6, 'ojxwx5d3YvMENQicskDP6-wurKoM', '猫语者', 1, 'https://thirdwx.qlogo.cn/mmopen/vi_32/pFpnI7kOW1OibSO4UXP3Ma5QBmbic9CqpFicrcvvc6h9kvibmgklXn4V0TshuhKibfaLvIIV41eeWoLRgwobI1hCP3Q/132', '', 'Southeast', 'Guizhou', 'China', '', '', '', 1, 0, 1597904962, 1600918566);
INSERT INTO `pet_member` VALUES (7, 'ojxwx5WsS0rUIYEdpOkkd_9BUb9s', 'Shinawatra', 2, 'https://thirdwx.qlogo.cn/mmopen/vi_32/LwbwtgoxjsD6kNibRGXcc8gm8YhOn2Y9jibFMSlribvXKPLwCKD4gAO3frFdnbEh5wQ1RicofBHlDWTGzQofiauicEIg/132', '', 'Changzhi', 'Shanxi', 'China', '', '', '', 1, 0, 1598325087, 1600496999);
INSERT INTO `pet_member` VALUES (8, 'ojxwx5fzrQGzrtdNGsk-rsSYgRJA', '佳旺宠物用品厂', 1, 'https://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLPweyHWgU9TxQXqCNP1Z8F9icEdx0leQeIEoibVREm3BfYfromoQKiaItjqBPyt2sAiaW2iclx6ptkA0w/132', '', 'Hegang', 'Heilongjiang', 'China', '15094554466', '', '', 1, 0, 1598495841, 1602956788);
INSERT INTO `pet_member` VALUES (9, 'ojxwx5ZBuwqQcZNuez9-a4-CX7uY', '蓝色妖姬', 1, 'https://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaELC8LkG34wJQJxTIaT0zMbJ19KWpBXKQoSscR3Wur1Cg4ZhEonoiaWZzcsbHyue7E9NlRSwB1tgBtg/132', '', 'Shenzhen', 'Guangdong', 'China', '', '', '', 1, 0, 1598527915, 1601173661);
INSERT INTO `pet_member` VALUES (10, 'ojxwx5Xzb8-lCJcCii0EqB399g1A', '钱男友', 1, 'https://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLGdibPsZ7wre9VaWnjONHVU07Bs99YtlKCE3q9FQ3QxJGEm2u0qx0YfcrSOwm5icS1PLxB7EM6gymw/132', '', '', 'Tai Po', 'Hong Kong', '', '', '', 1, 0, 1598529316, 0);
INSERT INTO `pet_member` VALUES (11, 'ojxwx5Vv4uph4P4OUMJ2aqYSmsgo', '刚子', 1, 'https://thirdwx.qlogo.cn/mmopen/vi_32/fyH5ycdXIpVPLbicptIq43AJ4ceUaiblPawcJibI9pbAWp4n4W5csA81A3xKjmNWkjhyXlIEQLXb9Z4lWejEkvW6g/132', '', 'Tongzhou', 'Beijing', 'China', '13011118090', '', '', 1, 0, 1598529441, 1604454411);
INSERT INTO `pet_member` VALUES (12, 'ojxwx5f4d0m-kMCzK8_UcXioFNAs', '刚宠18501116686', 1, 'https://thirdwx.qlogo.cn/mmopen/vi_32/sDIMibo20nZzZeSSpMh4y2rK8odebSiauXHnQq7xDEeicBooJ2QibLGfBwnXWKMVEiap159LbVqwKQFvpsu1DD04Nibg/132', '', '', '', 'China', '', '', '', 1, 0, 1598529878, 1601854316);
INSERT INTO `pet_member` VALUES (13, 'ojxwx5Vm5UCsxhBrjoSYOSBvsJ3w', '张一', 2, 'https://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIdgvyMelHNreMJicRXCcNG4WvR3QYj4oCckNjMzwspQrEdgiaafhLg3sEnvo0Y7CgHxfIbpTAic0org/132', '', 'Tongzhou', 'Beijing', 'China', '', '', '', 1, 0, 1598529978, 0);
INSERT INTO `pet_member` VALUES (14, 'ojxwx5c5PgTAKW1TlwJrQxhFw2pQ', '小华', 2, 'https://thirdwx.qlogo.cn/mmopen/vi_32/27hatcrlWiaIDuGMPYuhjuCcBrkLPt4BicJiaeM8wICSwk5lzPPb04kQeL6dqSvN0vdgrF2TbGibqB7sdyNlySuxDg/132', '', 'Hegang', 'Heilongjiang', 'China', '', '', '', 1, 0, 1598530343, 1601071417);
INSERT INTO `pet_member` VALUES (15, 'ojxwx5UabxWzVkk3kxSuwaWKfTyM', '欢乐马', 1, 'https://thirdwx.qlogo.cn/mmopen/vi_32/Q3auHgzwzM6Qq9mSJJnsVicYafYqzRicsg2C8eBokOAdFia9sSJibXj2CmsgXhkUv2kibKSlrPiaiaSWic6ZDEx77dEzTw/132', '', '', 'Chongqing', 'China', '', '', '', 1, 0, 1598576611, 1600917654);
INSERT INTO `pet_member` VALUES (16, 'ojxwx5c6z74vYqfnlTY82PTB_RYc', '许俊来', 0, 'https://thirdwx.qlogo.cn/mmhead/c7icMyZFV8t3uicRbVd6NVicLS1nueu7QgActp90IvQXBM/132', '', '', '', '', '', '', '', 1, 0, 1598612911, 0);
INSERT INTO `pet_member` VALUES (17, 'ojxwx5eKl_KFgOq0JXSn175I-1jQ', '林雅茹', 0, 'https://thirdwx.qlogo.cn/mmhead/nibib01HNmKTRm9GgtHYymIlGDC7XIiaQH2Y450pKVy6D0/132', '', '', '', '', '', '', '', 1, 0, 1598816146, 0);
INSERT INTO `pet_member` VALUES (18, 'ojxwx5S0roQoTZdHE6Jpz1eeHG4c', 'APP开发 程龙', 1, 'https://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83epbNNsIyBY5ibxRhyZt4iaXQorePob5sz6HuIgVowQrhlJ44RtwKhsrjbuc4CeFs2QaAGaqTEVrfIkw/132', '', 'Chengde', 'Hebei', 'China', '', '', '', 1, 0, 1598838120, 0);
INSERT INTO `pet_member` VALUES (20, 'ojxwx5b8ZzlUz1_YJ88Xt3mBFfkI', '母笑阳', 2, 'https://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJImh9HZsPl0vhHne4TdagUPwjMO5z4jyKAKyLAOveUA4bz6z0u713BWY1ehUQE8ZoBNicaC1plong/132', '', 'Baoding', 'Hebei', 'China', '', '', '', 1, 0, 1598961894, 1603350458);
INSERT INTO `pet_member` VALUES (21, 'ojxwx5XkmFBK6h1pI1iZLwvN_ALQ', '哈哈哈哈的哈哈', 1, 'https://thirdwx.qlogo.cn/mmopen/vi_32/ibJVWQSzVU2olLCK3u24NLsKrEKFJBibatdqC21WaialJnibqLJIj1vwWlno9TDxQ36N7atGIne7ict0MdVEpqQ2qNA/132', '', 'Guangzhou', 'Guangdong', 'China', '', '', '', 1, 0, 1599015304, 0);
INSERT INTO `pet_member` VALUES (22, 'ojxwx5T1TOW80yw0uTyqieDSbXmg', '洪怡萍', 0, 'https://thirdwx.qlogo.cn/mmhead/5tCU4Bs3tY11Hn7G6YlfGnj0yOuxtNjpqungWFJSabA/132', '', '', '', '', '', '', '', 1, 0, 1599025876, 0);
INSERT INTO `pet_member` VALUES (23, 'ojxwx5Ucu8EWCA7VO_gjVFDSF75w', '邱雅筑', 0, 'https://thirdwx.qlogo.cn/mmhead/FVKfrib7reN1aP7D5ibYnNabdMzyNLibBiauq4x3c1uPlbM/132', '', '', '', '', '', '', '', 1, 0, 1599025900, 0);
INSERT INTO `pet_member` VALUES (24, 'ojxwx5Y-haV7d27aL3AiOigO5ZYM', '郭常群', 0, 'https://thirdwx.qlogo.cn/mmhead/XXRch5ibxHGuibwnsNfuLLrlO8hWk4Nn70DsyhVfmhbUY/132', '', '', '', '', '', '', '', 1, 0, 1599026191, 0);
INSERT INTO `pet_member` VALUES (25, 'ojxwx5aLuyecHIp7W24tSaVlQnGY', '蒋世昌', 0, 'https://thirdwx.qlogo.cn/mmhead/QMqrBDJ7Jzc8RNlfPNaN2gGvt17zKI9b228MHIeTpNs/132', '', '', '', '', '', '', '', 1, 0, 1599026469, 0);
INSERT INTO `pet_member` VALUES (26, 'ojxwx5SiSZfT71rqSK7JZxS_slw8', '余纬绮', 0, 'https://thirdwx.qlogo.cn/mmhead/nKiaxCZopjITMdOWA5LHg08Hyhj5HXmEPfn5gXMLjKRg/132', '', '', '', '', '', '', '', 1, 0, 1599027973, 0);
INSERT INTO `pet_member` VALUES (27, 'ojxwx5Txtk0kbvwjAbVozxEUlhD0', '蔡秀娟', 0, 'https://thirdwx.qlogo.cn/mmhead/u1q74lON9J9MfWicSbcicZvWIy6ZDiblFPibU7gqmogqemo/132', '', '', '', '', '', '', '', 1, 0, 1599028730, 0);
INSERT INTO `pet_member` VALUES (28, 'ojxwx5dOXcDG8qNqjB1bKn7kdNrA', '李淑琦', 0, 'https://thirdwx.qlogo.cn/mmhead/01mMoQQKibEiceTlibaZghensqm9ia4X21ONxPTfdpOoDIY/132', '', '', '', '', '', '', '', 1, 0, 1599031738, 0);
INSERT INTO `pet_member` VALUES (29, 'ojxwx5W2I26ZsJjLsLBYZm91lQhA', '蔡靖雯', 0, 'https://thirdwx.qlogo.cn/mmhead/jQrgxLMso3Ck9dZR2h8WyKhjC26PAdfQrOKWstibiavibc/132', '', '', '', '', '', '', '', 1, 0, 1599032057, 0);
INSERT INTO `pet_member` VALUES (30, 'ojxwx5V3BWhWlwqkigS2ssYYGQeE', '李佩凡', 0, 'https://thirdwx.qlogo.cn/mmhead/bLQWnRVJgiaRGa8OtPHBoxNhnzH6d1e4icUkpC2HaCb1A/132', '', '', '', '', '', '', '', 1, 0, 1599034179, 0);
INSERT INTO `pet_member` VALUES (31, 'ojxwx5Sfgk6geBqUPhdWcNADckLE', '蒋淑慧', 0, 'https://thirdwx.qlogo.cn/mmhead/zx2sAicWDoJOeop7ywQxDDgBD8kGsSVB95S7ib0Oax3yo/132', '', '', '', '', '', '', '', 1, 0, 1599034848, 0);
INSERT INTO `pet_member` VALUES (32, 'ojxwx5bu5KrfD6AYqLqdAvl0QPp4', '嘿', 1, 'https://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83epSaYKIIs3nGSMNoo9pMaLE16zXeOlGZdXGyKQHulu0LcnibcXB3gl5Tibv0TuSrcBB6nJjiaSicGibwicQ/132', '', 'Guangzhou', 'Guangdong', 'China', '', '', '', 1, 0, 1600508098, 1600598724);
INSERT INTO `pet_member` VALUES (35, '2088422884219945', '嘿', 1, 'https://tfs.alipayobjects.com/images/partner/T1rhFyXXtaXXXXXXXX', '', '杭州市', '浙江省', '', '', '', '', 1, 1, 1600587036, 1600598386);
INSERT INTO `pet_member` VALUES (36, 'ojxwx5dTWvo6pSBazZnhJCKHGZrw', '蓝紫启', 0, 'https://thirdwx.qlogo.cn/mmhead/iaeyHGc3yVgibypPGKWjVQc0TpWgqymPic6roic0nqHhslc/132', '', '', '', '', '', '', '', 1, 0, 1600596373, 0);
INSERT INTO `pet_member` VALUES (37, '2088402071013123', '-ohthatdoog', 1, 'https://tfs.alipayobjects.com/images/partner/T1IPlfXn8jXXXXXXXX', '', '梅州市', '广东省', '', '18320304907', '', '', 1, 1, 1600651334, 1603763271);
INSERT INTO `pet_member` VALUES (38, 'ojxwx5RDXiP6YamAdhYqL5_BalJg', '小倩', 1, 'https://thirdwx.qlogo.cn/mmopen/vi_32/MibOYDxw9TDxFvRxAeIJDK2OBYQE4ozPBoianJcBg7GicQ62M0E5SmVQrlfB2kn155pWoAeeoLpbIX7ugKBcjOI3w/132', '', '', 'Dusseldorf', '', '', '', '', 1, 0, 1600850435, 1600936086);
INSERT INTO `pet_member` VALUES (39, 'ojxwx5Vx4meQeLCpXpXovWSUWicc', '谢冠中', 0, 'https://thirdwx.qlogo.cn/mmhead/ClP49qzgicFY5sLgMQAm4SGNgHWCicr7Xwv8wWpN7n7CQ/132', '', '', '', '', '', '', '', 1, 0, 1600887301, 1600887308);
INSERT INTO `pet_member` VALUES (40, 'ojxwx5ZrXyEXCRJxsODiVzItVkdQ', '低调', 1, 'https://thirdwx.qlogo.cn/mmopen/vi_32/MibOYDxw9TDxFvRxAeIJDKzfSGAFqu1iaLCJ7I16TflBVhJpm3z9yOH58xmlW0qpbAn7twTPk9EGyBh9b9n9RxGw/132', '', 'Lanzhou', 'Gansu', 'China', '', '', '', 1, 0, 1600912805, 0);
INSERT INTO `pet_member` VALUES (41, '2088312453370523', '华诺', 1, 'https://tfs.alipayobjects.com/images/partner/TB1DaMXcuBFDuNk6XeCXXbN6XXa', '', '广州市', '广东省', '', '', '', '', 1, 1, 1600944047, 1603351785);
INSERT INTO `pet_member` VALUES (42, 'ojxwx5StP0aReTArZ45cCC3BrmaU', '陈志宏', 0, 'https://thirdwx.qlogo.cn/mmhead/ZTEVeZiaca954Tf6Dpp5Y5icKVBiaia6YTpx9EqyYlT8FT8/132', '', '', '', '', '', '', '', 1, 0, 1600980233, 1600980255);
INSERT INTO `pet_member` VALUES (43, 'ojxwx5TyGMYEKSlmdj31G5IuAJmc', '林雅雄', 0, 'https://thirdwx.qlogo.cn/mmhead/yxPyZqeeA4A0uPHEcTmic6b0ia1Chrr0SEOK7KQibUS0ZM/132', '', '', '', '', '', '', '', 1, 0, 1600991789, 1600991807);
INSERT INTO `pet_member` VALUES (44, 'ojxwx5R1kwmZtSYCUYIktr7BlRmU', '魏剑帆', 1, 'https://thirdwx.qlogo.cn/mmopen/vi_32/IEiawcHX9Y3HgEpoHqCUYKX4ibbZjbCCT5FZ3X7aUF8jtzBxcqHqIrS8BpVrZH0mR3PQqwicxsCPzhOkuZzRUhOxw/132', '', 'Guangzhou', 'Guangdong', 'China', '', '', '', 1, 0, 1601014370, 0);
INSERT INTO `pet_member` VALUES (45, 'ojxwx5fpo5U-pqWNkI4p0ocjNLJo', '黄沛文', 0, 'https://thirdwx.qlogo.cn/mmhead/t3tayQTctnicJbjL7CHOyerkIiak1wwNqhxCGwBdH0FEE/132', '', '', '', '', '', '', '', 1, 0, 1601015240, 0);
INSERT INTO `pet_member` VALUES (47, 'ojxwx5R-bZ7PRo-4S8d9bOkqCeNc', '林德瑄', 0, 'https://thirdwx.qlogo.cn/mmhead/lqGdlbDIJFXttzibMia6XdNZ5LnszT1DicPJgsJbCZCic8w/132', '', '', '', '', '', '', '', 1, 0, 1601068310, 1601068332);
INSERT INTO `pet_member` VALUES (48, 'ojxwx5e1PLWOqh8s4igX-g-O_GGQ', '张松伶', 0, 'https://thirdwx.qlogo.cn/mmhead/TwSTp0ZOcMHf9jxJGAI2wLxqx74fBR8pHKU9U0o396A/132', '', '', '', '', '', '', '', 1, 0, 1601069037, 1601069056);
INSERT INTO `pet_member` VALUES (49, 'ojxwx5X0eHmTocAU0Id3-ClIbh-4', '华諾', 1, 'https://thirdwx.qlogo.cn/mmopen/vi_32/csIiaicHFXzFPKd7RrjdxQuDWCw2uCBpGXvWiah12EsnzaTWK2ibSWj1rA8WBku25doyhZ3v3LU06fQiacvWwaN9Dvw/132', '', 'Shangrao', 'Jiangxi', 'China', '', '', '', 1, 0, 1601100412, 1602036312);
INSERT INTO `pet_member` VALUES (50, '2088112335100835', '醒着做梦', 1, 'https://tfs.alipayobjects.com/images/partner/TB1VWa.Xl8rDuNk6XejXXbEYXXa', '', '广州市', '广东省', '', '13538777734', '', '', 1, 0, 1601103210, 0);
INSERT INTO `pet_member` VALUES (51, '2088602187894250', 'A燕芳', 2, 'https://tfs.alipayobjects.com/images/partner/T1V04eXlDVXXXXXXXX', '', '杭州市', '浙江省', '', '', '', '', 1, 0, 1601106847, 0);
INSERT INTO `pet_member` VALUES (52, '2088802119163454', '胡然', 1, 'https://tfs.alipayobjects.com/images/partner/T15aRlXXJdXXXXXXXX', '', '鹤岗市', '黑龙江省', '', '15094554466', '', '', 1, 1, 1601117542, 1602956843);
INSERT INTO `pet_member` VALUES (53, 'ojxwx5TKSQcpPvshcuaJOblNkd0Q', '李雅筑', 0, 'https://thirdwx.qlogo.cn/mmhead/Yh2DkZXR1ky3Nbm1sLmcCCZdmUzypgJnXcF5I223Vd0/132', '', '', '', '', '', '', '', 1, 0, 1601129761, 0);
INSERT INTO `pet_member` VALUES (54, 'ojxwx5XZr7B55B4QM6Au8VD_Hwic', '钢镚联盟', 1, 'https://thirdwx.qlogo.cn/mmopen/vi_32/xPnONW68Gfx544B2jFcDntcc4ebibibrsNZndKCdrGCnfKqVd5aIULIArUFPcVMtlpKBTYLuVCADbR3ibm536Sm2Q/132', '', '', '', '', '', '', '', 1, 0, 1601168252, 1604454748);
INSERT INTO `pet_member` VALUES (55, '2088732219773711', '测试账号请忽略！', 1, 'https://tfs.alipayobjects.com/images/partner/TB1.mK0b6uEDuNjmf_lXXcSrXXa', '', '杭州市', '浙江省', '', '17326067201', '', '', 1, 0, 1601172637, 0);
INSERT INTO `pet_member` VALUES (56, '2088732350409133', '测试数据，请忽略', 1, 'https://tfs.alipayobjects.com/images/partner/TB17Xi2bFmLDuNkUQcDXXXqTFXa', '', '吉林市', '吉林省', '', '18174004457', '', '', 1, 1, 1601175028, 1603510741);
INSERT INTO `pet_member` VALUES (57, 'ojxwx5bfiA89_n6reuOcNG5c1ZA4', '黄秀雄', 0, 'https://thirdwx.qlogo.cn/mmhead/1g0iax6wubUmYibpETSa5W8YqxaV0CnNKEic9aib2dJOCJo/132', '', '', '', '', '', '', '', 1, 0, 1601201403, 0);
INSERT INTO `pet_member` VALUES (58, '2088802574204042', '挽安-', 1, 'https://tfs.alipayobjects.com/images/partner/TB1qwg4b8hDDuNjm2EIXXXc7pXa', '', '杭州市', '浙江省', '', '15268819227', '', '', 1, 1, 1601258039, 1603351840);
INSERT INTO `pet_member` VALUES (59, 'ojxwx5dBmg2Iq5RHhkH5YPngty6w', '王吉泰', 0, 'https://thirdwx.qlogo.cn/mmhead/8ia5xnEwqe9YPECTQFxsprGCYY3dibTvYxaxwRBNBbics8/132', '', '', '', '', '', '', '', 1, 0, 1601268630, 1603425208);
INSERT INTO `pet_member` VALUES (60, 'ojxwx5VFqXsIFOGwOhpWCixQ2qnQ', '抱着长娥烤玉兔', 1, 'https://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTKiaia1g7tO80O3zicgbzUBfziaiaoJwtSTNKltCe1LlwXSZ3yaPYg9BsSM4xygqDrSku9b5luuGqic1vLg/132', '', 'Harbin', 'Heilongjiang', 'China', '', '', '', 1, 0, 1601268930, 0);
INSERT INTO `pet_member` VALUES (61, '2088932262492289', '三水', 2, 'https://tfs.alipayobjects.com/images/partner/TB1iI4XcA9iDuNjmgXdXXb.6VXa', '', '杭州市', '浙江省', '', '', '', '', 1, 0, 1601273645, 0);
INSERT INTO `pet_member` VALUES (62, '2088512653459729', '', 1, 'https://tfs.alipayobjects.com/images/partner/T1gJlEXh8dXXXXXXXX', '', '鹤岗市', '黑龙江省', '', '13011118090', '', '', 1, 1, 1601447685, 1604367148);
INSERT INTO `pet_member` VALUES (63, 'ojxwx5W5OT6QGtpxmY8SlEAHn408', '王琼龙', 0, 'https://thirdwx.qlogo.cn/mmhead/pwIibagokcTkKvibEvUSBEt8L7hxJvJfU73FGLY01VsoE/132', '', '', '', '', '', '', '', 1, 0, 1602956253, 1602956257);
INSERT INTO `pet_member` VALUES (64, '2088832321612631', '', 1, 'https://tfs.alipayobjects.com/images/partner/TB1URuEcbVCDuNkUuMMXXcS7pXa', '', '杭州市', '浙江省', '', '17315212240', '', '', 1, 0, 1603778684, 0);
INSERT INTO `pet_member` VALUES (65, '2088312145183363', '天涯', 1, 'https://tfs.alipayobjects.com/images/partner/T1nQhmXexgXXXXXXXX', '', '北京市', '北京', '', '', '', '', 1, 0, 1604454762, 0);

-- ----------------------------
-- Table structure for pet_member_log
-- ----------------------------
DROP TABLE IF EXISTS `pet_member_log`;
CREATE TABLE `pet_member_log`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '日志id',
  `openid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'openid',
  `nickname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '会员昵称',
  `code_id` int(11) NOT NULL DEFAULT 0 COMMENT '二维码id',
  `code_number` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '二维码编号',
  `latitude` float(9, 6) NOT NULL DEFAULT 0.000000 COMMENT '纬度',
  `longitude` float(9, 6) NOT NULL DEFAULT 0.000000 COMMENT '经度',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '地址',
  `ctime` int(10) NOT NULL DEFAULT 0 COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户扫码日志表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pet_member_log
-- ----------------------------
INSERT INTO `pet_member_log` VALUES (1, '', '', 1, 'DAF00833', 47.358139, 130.296707, '黑龙江省鹤岗市兴山区公助街', 1604454751);

-- ----------------------------
-- Table structure for pet_remark_template
-- ----------------------------
DROP TABLE IF EXISTS `pet_remark_template`;
CREATE TABLE `pet_remark_template`  (
  `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '返家寄语',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：0禁用1正常',
  `ctime` int(10) NOT NULL DEFAULT 0 COMMENT '添加时间',
  `utime` int(10) NOT NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '返家寄语模板管理' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pet_remark_template
-- ----------------------------
INSERT INTO `pet_remark_template` VALUES (1, '模板一', '他是我们的最亲爱的宝贝，是我们的家人，我们很爱他~~迷路送返，定有重谢！', 1, 0, 0);
INSERT INTO `pet_remark_template` VALUES (3, '模板二', '我走丢了，我想回家，请您给我的妈妈打电话，我妈妈会酬谢您的。', 1, 1601003727, 0);
INSERT INTO `pet_remark_template` VALUES (4, '模板三', '麻麻是美女，我带你去看，送我回家请您吃饭饭。', 1, 1601003850, 0);
INSERT INTO `pet_remark_template` VALUES (5, '模板四', '我迷路了，请好心人联系我妈妈，非常感谢您。', 1, 1601003866, 0);
INSERT INTO `pet_remark_template` VALUES (6, '模板五', '饭量贼大，活动贼猛，拉屎贼多，不宜长期饲养，送回重谢。我老想家了。', 1, 1601003881, 0);
INSERT INTO `pet_remark_template` VALUES (7, '模板六', '我的特长一挖、二咬、三狼嚎 迷路送返，定有酬谢', 1, 1601003895, 0);
INSERT INTO `pet_remark_template` VALUES (8, '模板七', '我想拔拔了，请好心人送我回家。', 1, 1601003912, 0);
INSERT INTO `pet_remark_template` VALUES (9, '模板八', '我是妈妈的宝贝，请你送我回家', 1, 1601003927, 0);

-- ----------------------------
-- Table structure for pet_sendsms
-- ----------------------------
DROP TABLE IF EXISTS `pet_sendsms`;
CREATE TABLE `pet_sendsms`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `phone` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `code` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '验证码',
  `expire_time` int(10) NOT NULL DEFAULT 0 COMMENT '过期时间',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '使用状态：0未使用1已使用',
  `ctime` int(10) NOT NULL DEFAULT 0 COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '发送短信验证码记录' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for pet_system_menu
-- ----------------------------
DROP TABLE IF EXISTS `pet_system_menu`;
CREATE TABLE `pet_system_menu`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '父ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单图标',
  `href` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '链接',
  `target` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self' COMMENT '链接打开方式',
  `sort` int(5) NOT NULL DEFAULT 0 COMMENT '菜单排序',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态(0:禁用,1:启用)',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注信息',
  `authority` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '权限标识',
  `level` tinyint(1) NOT NULL DEFAULT 1 COMMENT '等级：1第一级2第二级3第三级',
  `ctime` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `utime` int(10) NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `title`(`title`) USING BTREE,
  INDEX `href`(`href`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '系统菜单表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pet_system_menu
-- ----------------------------
INSERT INTO `pet_system_menu` VALUES (1, 0, '菜单管理', '', '', '', 50, 1, '', ' ', 1, 0, 1597225563);
INSERT INTO `pet_system_menu` VALUES (2, 1, '菜单列表', '', 'menu', '_self', 1, 1, ' ', '', 2, 0, 0);
INSERT INTO `pet_system_menu` VALUES (3, 0, '管理员管理', '', '', '', 10, 1, '', '', 1, 0, 1597225577);
INSERT INTO `pet_system_menu` VALUES (4, 3, '管理员列表', '', 'user', '_black', 0, 1, '', '', 2, 0, 1597221567);
INSERT INTO `pet_system_menu` VALUES (6, 0, '二维码管理', '', 'codes', '_self', 0, 1, '', '', 1, 0, 0);
INSERT INTO `pet_system_menu` VALUES (7, 0, '宠物管理', '', '', '_self', 0, 1, '', '', 1, 0, 0);
INSERT INTO `pet_system_menu` VALUES (8, 0, '会员管理', '', '', '', 30, 1, '', '', 1, 0, 1597225587);
INSERT INTO `pet_system_menu` VALUES (9, 8, '会员列表', '', 'member', '_self', 0, 1, '', '', 2, 0, 0);
INSERT INTO `pet_system_menu` VALUES (10, 8, '会员扫码日志', '', 'memberlog', '_self', 0, 1, '', '', 2, 0, 0);
INSERT INTO `pet_system_menu` VALUES (11, 0, '系统管理', '', '', '', 0, 1, '', '', 1, 0, 0);
INSERT INTO `pet_system_menu` VALUES (12, 11, '网站配置', '', 'config/editconf', '_self', 0, 1, '', '', 2, 0, 1597626629);
INSERT INTO `pet_system_menu` VALUES (14, 0, '文章管理', '', 'article', '', 10, 1, '', '', 1, 1597389907, 0);
INSERT INTO `pet_system_menu` VALUES (15, 11, '配置列表', '', 'config', '', 10, 1, '', '', 2, 1597626234, 0);
INSERT INTO `pet_system_menu` VALUES (16, 7, '宠物列表', '', 'pet', '', 10, 1, '', '', 2, 1599446896, 0);
INSERT INTO `pet_system_menu` VALUES (17, 7, '宠物丢失列表', '', 'petlost', '', 0, 1, '', '', 2, 1599447005, 0);
INSERT INTO `pet_system_menu` VALUES (18, 7, '寄语模板', '', 'petremark', '_self', 0, 1, '', '', 2, 1599447005, 0);

SET FOREIGN_KEY_CHECKS = 1;
