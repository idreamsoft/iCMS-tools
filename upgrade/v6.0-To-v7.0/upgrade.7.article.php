<?php
require_once dirname(__FILE__).'/common.php';

if(iDB::check_table('article')){
    upgrade_query(
        "重命名 article 的字段  `ordernum` => `sortnum`",
        "ALTER TABLE `icms_article` CHANGE `ordernum` `sortnum` INT(10) UNSIGNED DEFAULT 0  NOT NULL COMMENT '排序';"
    );

    upgrade_query(
        "更新article表结构",
        "ALTER TABLE `icms_article`
      CHANGE `id` `id` int(10) unsigned   NOT NULL auto_increment COMMENT '文章ID' first ,
      CHANGE `pubdate` `pubdate` int(10) unsigned   NOT NULL DEFAULT 0 COMMENT '发布时间' after `related` ,
      CHANGE `weight` `weight` int(10)   NOT NULL DEFAULT 0 COMMENT '权重' after `chapter` ,
      ADD COLUMN `markdown` tinyint(1) unsigned   NOT NULL DEFAULT 0 COMMENT 'markdown标识' after `weight` ,
      CHANGE `mobile` `mobile` tinyint(1) unsigned   NOT NULL DEFAULT 0 COMMENT '1手机发布 0 pc' after `markdown`;"
    );

    $fields  = apps_db::fields('#iCMS@__article');
    if($fields['metadata']){
        $perpage = 1000;
        $where  = "`metadata`!='' ORDER BY `id` ASC";
        $count  = iDB::value("SELECT count(*) FROM `#iCMS@__article` where {$where};");
        $page   = ceil($count/$perpage);

        flush_print("开始转换article表的metadata数据,总共涉及到{$count}条数据,{$page}");

        if($count){
            $cpa = iDB::all("SELECT `cid`,`contentprop` FROM `#iCMS@__category` WHERE `contentprop`!=''");
            foreach ((array)$cpa as $key => $value) {
                $contentprop[$value['cid']] = unserialize($value['contentprop']);
            }

            for ($i=0; $i < $page; $i++) {
                $offset = $i*$perpage;
                $limit  = "LIMIT {$offset},{$perpage}";
                flush_print("start...".$limit);
                $ids_array = iDB::all("SELECT `id` FROM `#iCMS@__article` where {$where} {$limit}");
                flush_print(iDB::$last_query);
                $ids = iSQL::values($ids_array);
                $ids = $ids ? $ids : 0;
                $ids && article_metadata($ids,$contentprop);
            }
        }

        upgrade_query(
            "metadata数据转换完成,删除字段",
            "ALTER TABLE `icms_article` DROP COLUMN `metadata` ;"
        );
    }
}

function article_metadata($ids,$contentprop=null){
    $where_sql = "WHERE `id` IN({$ids})";
    $resource = iDB::all("SELECT `id`,`cid`,`metadata` FROM `#iCMS@__article` {$where_sql}");
    foreach ((array)$resource as $key => $value) {
        $metadata  = unserialize($value['metadata']);
        $data = array();
        foreach ((array)$metadata as $mkey => $mval) {
            $name = $contentprop[$value['cid']][$mkey];
            empty($name) && $name = $mkey;
            $data[$mkey] = array(
                'name'  =>$name,
                'key'   =>$mkey,
                'value' =>$mval
            );
        }
        $data = addslashes(json_encode($data));
        iDB::insert('article_meta',array('id'=>$value['id'],'data'=>$data),true);
        flush_print($value['id'].(iDB::$link->affected_rows?'.....√':'.....×'));
    }
}

redirect('upgrade.8.category.php');
