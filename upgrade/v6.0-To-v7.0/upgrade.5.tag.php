<?php
require_once dirname(__FILE__).'/common.php';

if(iDB::check_table('tags')){
    upgrade_query(
        "重命名 `tags` => `tag`",
        "RENAME TABLE `icms_tags` TO `icms_tag`;"
    );
}
if(iDB::check_table('tags_map')){
    upgrade_query(
        "重命名 `tags_map` => `tag_map`",
        "RENAME TABLE `icms_tags_map` TO `icms_tag_map`;"
    );
}
if(iDB::check_table('tag_map')){
    upgrade_query(
        "更新tag_map表结构",
        "ALTER TABLE `icms_tag_map`
      ADD COLUMN `field` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '字段' after `iid`;"
    );

    upgrade_query(
        "tag_map 字段 field写入默认值",
        "UPDATE `icms_tag_map` SET `field`='tags';"
    );
}

if(iDB::check_table('tag')){
    upgrade_query(
        "重命名 tag 的字段  `ordernum` => `sortnum`",
        "ALTER TABLE `icms_tag` CHANGE `ordernum` `sortnum` INT(10) UNSIGNED DEFAULT 0  NOT NULL;"
    );

    $fields  = apps_db::fields('#iCMS@__tag');
    if($fields['metadata']){
        $perpage = 1000;
        $where  = "`metadata`!='' ORDER BY `id` ASC";
        $count  = iDB::value("SELECT count(*) FROM `#iCMS@__tag` where {$where};");
        $page   = ceil($count/$perpage);

        flush_print("开始转换tag表的metadata数据,总共涉及到{$count}条数据,{$page}");

        if($count){
            for ($i=0; $i < $page; $i++) {
                $offset = $i*$perpage;
                $limit  = "LIMIT {$offset},{$perpage}";
                flush_print("start...tag ".$limit);
                $ids_array = iDB::all("SELECT `id` FROM `#iCMS@__tag` where {$where} {$limit}");
                flush_print(iDB::$last_query);
                $ids = iSQL::values($ids_array);
                $ids = $ids ? $ids : 0;
                $ids && tag_metadata($ids);
            }
        }

        upgrade_query(
            "metadata数据转换完成,删除字段",
            "ALTER TABLE `icms_tag` DROP COLUMN `metadata` ;"
        );
    }
}

function tag_metadata($ids){
    $where_sql = "WHERE `id` IN({$ids})";
    $resource = iDB::all("SELECT `id`,`metadata` FROM `#iCMS@__tag` {$where_sql}");
    foreach ((array)$resource as $key => $value) {
        $metadata  = json_decode($value['metadata'],true);
        $data = array();
        foreach ($metadata as $mkey => $mval) {
            $data[$mkey] = array(
                'name'  =>$mkey,
                'key'   =>$mkey,
                'value' =>$mval
            );
        }
        $data = addslashes(json_encode($data));
        iDB::insert('tag_meta',array('id'=>$value['id'],'data'=>$data),true);
        flush_print($value['id'].'.....√');
    }
}

redirect('upgrade.6.files.php');
