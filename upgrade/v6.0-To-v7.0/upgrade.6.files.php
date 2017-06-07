<?php
require_once dirname(__FILE__).'/common.php';

if(iDB::check_table('filedata')){
    upgrade_query(
        "重命名 `filedata` => `files`",
        "RENAME TABLE `icms_filedata` TO `icms_files`;"
    );
}

if(iDB::check_table('files') && iDB::check_table('files_map')){
    upgrade_query(
        "更新files表结构",
        "ALTER TABLE `icms_files` ADD COLUMN `status` tinyint(1) unsigned   NOT NULL DEFAULT 0 after `type` ;"
    );

    upgrade_query(
        "files 字段 status写入默认值",
        "UPDATE `icms_files` SET `status`='1';"
    );

    $fields  = apps_db::fields('#iCMS@__files');
    if($fields['indexid']){
        $perpage = 1000;
        $where  = "`indexid`!='0' ORDER BY `id` ASC";
        $count  = iDB::value("SELECT count(*) FROM `#iCMS@__files` where {$where};");
        $page   = ceil($count/$perpage);

        flush_print("开始转换files表的indexid数据,总共涉及到{$count}条数据,{$page}");

        if($count){
            for ($i=0; $i < $page; $i++) {
                $offset = $i*$perpage;
                $limit  = "LIMIT {$offset},{$perpage}";
                flush_print("start...files ".$limit);
                $ids_array = iDB::all("SELECT `id` FROM `#iCMS@__files` where {$where} {$limit}");
                flush_print(iDB::$last_query);
                $ids = iSQL::values($ids_array);
                $ids = $ids ? $ids : 0;
                $ids && files_indexid($ids);
            }
        }

        upgrade_query(
            "indexid数据转换完成,删除字段",
            "ALTER TABLE `icms_files`
  DROP COLUMN `indexid` ,
  DROP KEY `indexid` ;"
        );
    }

}
function files_indexid($ids){
    $where_sql = "WHERE `id` IN({$ids})";
    $resource = iDB::all("SELECT `id`,`userid`,`indexid`,`time` FROM `#iCMS@__files` {$where_sql}");
    foreach ((array)$resource as $key => $value) {
        iDB::insert('files_map',array(
            'fileid'  =>$value['id'],
            'userid'  =>$value['userid'],
            'appid'   =>1,
            'indexid' =>$value['indexid'],
            'addtime' =>$value['time'],
        ),true);

        flush_print($value['id'].(iDB::$link->affected_rows?'.....√':'.....×'));
    }
}

redirect('upgrade.7.article.php');
