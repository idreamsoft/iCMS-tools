<?php
require_once dirname(__FILE__).'/common.php';

upgrade_query(
    "重命名 category 的字段  `ordernum` => `sortnum`",
    "ALTER TABLE `icms_category` CHANGE `ordernum` `sortnum` INT(10) UNSIGNED DEFAULT 0  NOT NULL;",
    "category"
);
upgrade_query(
    "重命名 group 的字段  `ordernum` => `sortnum`",
    "ALTER TABLE `icms_group` CHANGE `ordernum` `sortnum` INT(10) UNSIGNED DEFAULT 0  NOT NULL;",
    "group"
);
upgrade_query(
    "重命名 links 的字段  `ordernum` => `sortnum`",
    "ALTER TABLE `icms_links` CHANGE `ordernum` `sortnum` INT(10) UNSIGNED DEFAULT 0  NOT NULL;",
    "links"
);
upgrade_query(
    "重命名 prop 的字段  `ordernum` => `sortnum`",
    "ALTER TABLE `icms_prop` CHANGE `ordernum` `sortnum` INT(10) UNSIGNED DEFAULT 0  NOT NULL;",
    "prop"
);
upgrade_query(
    "重命名 push 的字段  `ordernum` => `sortnum`",
    "ALTER TABLE `icms_push` CHANGE `ordernum` `sortnum` INT(10) UNSIGNED DEFAULT 0  NOT NULL;",
    "push"
);

upgrade_query(
    "重命名 article 的字段  `ordernum` => `sortnum`",
    "ALTER TABLE `icms_article` CHANGE `ordernum` `sortnum` INT(10) UNSIGNED DEFAULT 0  NOT NULL COMMENT '排序';",
    "article"
);

upgrade_query(
    "重命名 tag 的字段  `ordernum` => `sortnum`",
    "ALTER TABLE `icms_tag` CHANGE `ordernum` `sortnum` INT(10) UNSIGNED DEFAULT 0  NOT NULL;",
    "tag"
);

upgrade_query(
    "更新article表结构",
    "ALTER TABLE `icms_article`
  CHANGE `id` `id` int(10) unsigned   NOT NULL auto_increment COMMENT '文章ID' first ,
  CHANGE `pubdate` `pubdate` int(10) unsigned   NOT NULL DEFAULT 0 COMMENT '发布时间' after `related` ,
  CHANGE `weight` `weight` int(10)   NOT NULL DEFAULT 0 COMMENT '权重' after `chapter` ,
  ADD COLUMN `markdown` tinyint(1) unsigned   NOT NULL DEFAULT 0 COMMENT 'markdown标识' after `weight` ,
  CHANGE `mobile` `mobile` tinyint(1) unsigned   NOT NULL DEFAULT 0 COMMENT '1手机发布 0 pc' after `markdown`;",
  "article"
);

upgrade_query(
    "更新category表结构",
    "ALTER TABLE `icms_category`
  CHANGE `mode` `mode` tinyint(1) unsigned   NOT NULL DEFAULT 0 after `spic` ,
  ADD COLUMN `rule` text  COLLATE utf8_general_ci NOT NULL after `htmlext` ,
  ADD COLUMN `template` text  COLLATE utf8_general_ci NOT NULL after `rule` ,
  ADD COLUMN `config` text  COLLATE utf8_general_ci NOT NULL after `template` ,
  CHANGE `count` `count` int(10) unsigned   NOT NULL DEFAULT 0 after `config` ,
  CHANGE `comments` `comments` int(10) unsigned   NOT NULL DEFAULT 0 after `count` ,
  CHANGE `createtime`  `addtime` int(10)   NULL DEFAULT 0 after `comments` ,
  CHANGE `status` `status` tinyint(1) unsigned   NOT NULL DEFAULT 1 after `addtime`;",
  "category"
);

upgrade_query(
    "更新category_map表结构",
    "ALTER TABLE `icms_category_map`
  ADD COLUMN `field` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '字段' after `iid` ,
  CHANGE `appid` `appid` int(10) unsigned   NOT NULL DEFAULT 0 COMMENT '应用ID' after `field` ;",
  "category_map"
);
upgrade_query(
    "category_map 字段 field写入默认值",
    "UPDATE `icms_category_map` SET `field`='pid';",
    "category_map"
);

upgrade_query(
    "更新files表结构",
    "ALTER TABLE `icms_files` ADD COLUMN `status` tinyint(1) unsigned   NOT NULL DEFAULT 0 after `type` ;",
    "files"
);

upgrade_query(
    "更新group表结构",
    "ALTER TABLE `icms_group` ADD COLUMN `config` mediumtext  COLLATE utf8_general_ci NOT NULL after `sortnum`;",
    "group"
);

upgrade_query(
    "更新keywords表结构",
    "ALTER TABLE `icms_keywords`
  ADD COLUMN `replace` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' after `keyword` ,
  DROP COLUMN `times` ;",
    "keywords"
);

upgrade_query(
    "更新members表结构",
    "ALTER TABLE `icms_members`
  ADD COLUMN `config` mediumtext  COLLATE utf8_general_ci NOT NULL after `info`;",
    "members"
);

upgrade_query(
    "更新prop表结构",
    "ALTER TABLE `icms_prop`
  ADD COLUMN `appid` int(10) unsigned   NOT NULL DEFAULT 0 after `field` ,
  CHANGE `type` `app` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' after `appid` ,
  CHANGE `sortnum` `sortnum` int(10) unsigned   NOT NULL DEFAULT 0 after `app` ,
  DROP KEY `type`,
  ADD KEY `app`(`app`) ;",
    "prop"
);

upgrade_query(
    "更新prop_map表结构",
    "ALTER TABLE `icms_prop_map`
  CHANGE `node` `node` int(10) unsigned   NOT NULL DEFAULT 0 COMMENT 'pid' after `id` ,
  ADD COLUMN `field` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '字段' after `iid`;",
    "prop_map"
);

upgrade_query(
    "prop_map 字段 field写入默认值",
    "UPDATE `icms_prop_map` SET `field`='pid';",
    "prop_map"
);

upgrade_query(
    "更新tag_map表结构",
    "ALTER TABLE `icms_tag_map`
  ADD COLUMN `field` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '字段' after `iid`;",
    "tag_map"
);

upgrade_query(
    "tag_map 字段 field写入默认值",
    "UPDATE `icms_tag_map` SET `field`='tags';",
    "tag_map"
);

upgrade_query(
    "更新user表结构",
    "ALTER TABLE `icms_user`
  ADD COLUMN `setting` varchar(1024)  COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '其它设置' after `hits_month`;",
    "user"
);

upgrade_query(
    "user 字段 setting写入默认值",
    "UPDATE `icms_user` SET `setting`='{\"inbox\":{\"receive\":\"follow\"}}';",
    "user"
);

upgrade_query(
    "更新user_data表结构",
    "ALTER TABLE `icms_user_data`
  CHANGE `address` `address` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '街道地址' after `mobile` ,
  CHANGE `province` `province` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '省份' after `address` ,
  CHANGE `personstyle` `personstyle` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '个人标签' after `profession` ,
  ADD COLUMN `meta` text  COLLATE utf8_general_ci NOT NULL COMMENT '其它数据' after `unickEdit` ,
  DROP COLUMN `pskin` ,
  DROP COLUMN `enterprise` ,
  DROP COLUMN `weibo` ,
  DROP COLUMN `coverpic` ,
  DROP COLUMN `height` ,
  DROP COLUMN `isSeeFigure` ,
  DROP COLUMN `weight` ,
  DROP COLUMN `bwhB` ,
  DROP COLUMN `bwhW` ,
  DROP COLUMN `bwhH` ,
  DROP COLUMN `phair` ,
  DROP COLUMN `shoesize` ,
  DROP COLUMN `tb_nick` ,
  DROP COLUMN `tb_buyer_credit` ,
  DROP COLUMN `tb_seller_credit` ,
  DROP COLUMN `tb_type` ,
  DROP COLUMN `is_golden_seller` ;",
    "user_data"
);
redirect('upgrade.4.php');
