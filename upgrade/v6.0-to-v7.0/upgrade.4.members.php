<?php
require_once dirname(__FILE__).'/common.php';

if(iDB::check_table('group')){
    upgrade_query(
        "重命名 group 的字段  `ordernum` => `sortnum`",
        "ALTER TABLE `icms_group` CHANGE `ordernum` `sortnum` INT(10) UNSIGNED DEFAULT 0  NOT NULL;"
    );
    $fields  = apps_db::fields('#iCMS@__group');
    if(empty($fields['config'])){
        upgrade_query(
            "更新group表结构",
            "ALTER TABLE `icms_group` ADD COLUMN `config` mediumtext  COLLATE utf8_general_ci NOT NULL after `sortnum`;"
        );
    }
    if($fields['power'] && $fields['cpower'] ){
        $groups = iDB::all("SELECT * FROM `#iCMS@__group`");
        foreach ($groups as $key => $value) {
            $config_array = array();
            $value['power'] && $config_array['mpriv'] = json_decode($value['power'],true);
            $value['cpower'] && $config_array['cpriv'] = json_decode($value['cpower'],true);
            if($config_array){
                $config = json_encode($config_array);
                $gid = $value['gid'];
                flush_print("开始转换group表的power,cpower数据");
                iDB::update('group',compact('config'),compact('gid'));
            }
        }
        upgrade_query(
            "更新group表结构",
            "ALTER TABLE `icms_group`
  DROP COLUMN `power` ,
  DROP COLUMN `cpower` ;"
        );
    }
}

if(iDB::check_table('members')){
    $fields  = apps_db::fields('#iCMS@__members');
    if(empty($fields['config'])){
        upgrade_query(
            "更新members表结构",
            "ALTER TABLE `icms_members` ADD COLUMN `config` mediumtext  COLLATE utf8_general_ci NOT NULL after `info`;"
        );
    }
    if($fields['power'] && $fields['cpower'] ){
        $memberss = iDB::all("SELECT * FROM `#iCMS@__members`");
        foreach ($memberss as $key => $value) {
            $config_array = $info = array();
            $value['power'] && $config_array['mpriv'] = json_decode($value['power'],true);
            $value['cpower'] && $config_array['cpriv'] = json_decode($value['cpower'],true);
            $uid = $value['uid'];
            if($config_array){
                $config = json_encode($config_array);
                flush_print("开始转换members表的power,cpower数据");
                iDB::update('members',compact('config'),compact('uid'));
            }
            $value['info'] && $info = unserialize($value['info']);
            $info = json_encode($info);
            iDB::update('members',compact('info'),compact('uid'));
        }
        upgrade_query(
            "更新members表结构",
            "ALTER TABLE `icms_members`
  DROP COLUMN `cpower` ,
  DROP COLUMN `power` ;"
        );
    }
}

redirect('upgrade.5.tag.php');
