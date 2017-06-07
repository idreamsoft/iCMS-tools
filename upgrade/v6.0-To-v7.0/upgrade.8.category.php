<?php
require_once dirname(__FILE__).'/common.php';

if(iDB::check_table('category_map')){
    upgrade_query(
        "更新category_map表结构",
        "ALTER TABLE `icms_category_map`
      CHANGE `node` `node` int(10) unsigned   NOT NULL DEFAULT 0 COMMENT 'cid' after `id`,
      ADD COLUMN `field` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '字段' after `iid` ,
      CHANGE `appid` `appid` int(10) unsigned   NOT NULL DEFAULT 0 COMMENT '应用ID' after `field` ;"
    );
    upgrade_query(
        "category_map 字段 field写入默认值",
        "UPDATE `icms_category_map` SET `field`='pid';"
    );
}

if(iDB::check_table('category')){
    upgrade_query(
        "重命名 category 的字段  `ordernum` => `sortnum`",
        "ALTER TABLE `icms_category` CHANGE `ordernum` `sortnum` INT(10) UNSIGNED DEFAULT 0  NOT NULL;",
        "category"
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


    $fields  = apps_db::fields('#iCMS@__category');
    $perpage = 10;
    $where  = "1=1 ORDER BY `cid` ASC";
    $count  = iDB::value("SELECT count(*) FROM `#iCMS@__category` where {$where};");
    $page   = ceil($count/$perpage);

    flush_print("开始转换category表,总共涉及到{$count}条数据,{$page}");

    if($count){
        for ($i=0; $i < $page; $i++) {
            $offset = $i*$perpage;
            $limit  = "LIMIT {$offset},{$perpage}";
            flush_print("start...".$limit);
            $ids_array = iDB::all("SELECT `cid` FROM `#iCMS@__category` where {$where} {$limit}");
            flush_print(iDB::$last_query);
            $ids = iSQL::values($ids_array,'cid');
            $ids = $ids ? $ids : 0;
            $ids && category_column($ids);
        }
    }

        upgrade_query(
            "数据转换完成,删除字段",
            "ALTER TABLE `icms_category`
  DROP COLUMN `categoryURI` ,
  DROP COLUMN `categoryRule` ,
  DROP COLUMN `contentRule` ,
  DROP COLUMN `urlRule` ,
  DROP COLUMN `indexTPL` ,
  DROP COLUMN `listTPL` ,
  DROP COLUMN `contentTPL` ,
  DROP COLUMN `contentprop` ,
  DROP COLUMN `hasbody` ,
  DROP COLUMN `metadata` ,
  DROP COLUMN `isucshow` ,
  DROP COLUMN `isexamine` ,
  DROP COLUMN `issend` ;"
        );
}
function category_column($ids){
    $where_sql = "WHERE `cid` IN({$ids})";
    $resource = iDB::all("SELECT * FROM `#iCMS@__category` {$where_sql}");
    foreach ((array)$resource as $key => $value) {
      $rule_array = array(
        "index"   =>$value['categoryRule'],
        "list"    =>'',
        "article" =>$value['contentRule'],
        "tag"     =>$value['urlRule'],
      );
      $rule = addslashes(json_encode($rule_array));

      $template_array = array(
        "index"   =>$value['indexTPL'],
        "list"    =>$value['listTPL'],
        "article" =>$value['contentTPL'],
        "tag"     =>'{iTPL}/tag.htm',
      );
      $template = addslashes(json_encode($template_array));

      if($value['contentprop']){
        $contentprop = unserialize($value['contentprop']);
        foreach ((array)$contentprop as $pkey => $pval) {
          $meta_array=array(
              'name'=>$pval,
              'key'=>$pkey,
          );
        }
        $meta_array && $meta = json_encode($meta_array);
      }

      $config_array = array(
        "ucshow"  =>$value['isucshow'],
        "send"    =>$value['issend'],
        "examine" =>$value['isexamine'],
        "meta"    =>$meta
      );
      $config = addslashes(json_encode($config_array));

      $cid = $value['cid'];

      $update = iDB::update('category',compact('config','rule','template'),compact('cid'));

      flush_print($value['cid'].' update '.($update?'.....√':'.....×'));

      if($value['metadata']){
        $metadata  = unserialize($value['metadata']);
        $data = array();
        foreach ((array)$metadata as $mkey => $mval) {
            $data[$mkey] = array(
                'name'  =>$mkey,
                'key'   =>$mkey,
                'value' =>$mval
            );
        }
      }
      if($value['hasbody']){
        // $data['body'] = iCache::get('category/'.$value['cid'].'.body');
        // $data['body'] && $data['body'] = stripslashes($data['body']);
      }
      if($data){
        $data = addslashes(json_encode($data));
        iDB::insert('category_meta',array('id'=>$value['cid'],'data'=>$data),true);
        flush_print($value['cid'].' category_meta '.(iDB::$link->affected_rows?'.....√':'.....×'));
      }
    }
}

redirect('upgrade.9.config.php');
