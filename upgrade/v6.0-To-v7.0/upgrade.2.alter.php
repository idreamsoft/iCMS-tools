<?php
require_once dirname(__FILE__).'/common.php';

$fields  = apps_db::fields('#iCMS@__links');
$fields['orderNum'] && upgrade_query(
    "重命名 links 的字段  `orderNum` => `sortnum`",
    "ALTER TABLE `icms_links` CHANGE `orderNum` `sortnum` INT(10) UNSIGNED DEFAULT 0  NOT NULL;",
    "links"
);

$fields['ordernum'] && upgrade_query(
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
    "更新spider_url表结构",
    "ALTER TABLE `icms_spider_url` ADD COLUMN `appid` INT(10) NOT NULL AFTER `id`;",
    "spider_url"
);


upgrade_query(
    "更新prop表结构",
    "ALTER TABLE `icms_prop`
  ADD COLUMN `appid` int(10) unsigned   NOT NULL DEFAULT 0 after `field` ,
  CHANGE `type` `app` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' after `appid` ,
  CHANGE `sortnum` `sortnum` int(10) unsigned   NOT NULL DEFAULT 0 after `app`;",
    "prop"
);

upgrade_query(
    "更新prop表结构",
    "ALTER TABLE `icms_prop`
  DROP KEY `app` ,
  ADD KEY `type`(`app`) ;",
    "prop"
);

$prop_map_fields  = apps_db::fields('#iCMS@__prop_map');
$prop_map_fields['field'] OR upgrade_query(
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
    "更新user表结构",
    "ALTER TABLE `icms_user`
  DROP INDEX `username`,
  ADD  UNIQUE INDEX `username` (`username`),
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
redirect('upgrade.3.keywords.php');
