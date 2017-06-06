<?php
require_once dirname(__FILE__).'/common.php';

if(iDB::check_table('keywords')){
    $fields  = apps_db::fields('#iCMS@__keywords');
    if(empty($fields['replace'])){
        upgrade_query(
            "更新keywords表结构",
            "ALTER TABLE `icms_keywords` ADD COLUMN `replace` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' after `keyword`;"
        );
    }
    if($fields['url']){
        upgrade_query(
            "url数据转换",
            "UPDATE `icms_keywords` SET
            `replace` = CONCAT('<a href=\"', url, '\" target=\"_blank\" class=\"keywords\"/>', keyword, '</a>')"
        );

        upgrade_query(
            "url数据转换完成,删除字段",
            "ALTER TABLE `icms_keywords` DROP COLUMN `url`,DROP COLUMN `times`;"
        );
    }
}



redirect('upgrade.4.members.php');
