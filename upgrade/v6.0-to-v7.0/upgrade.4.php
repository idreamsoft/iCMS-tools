<?php
require_once dirname(__FILE__).'/common.php';

if(iDB::check_table('article')){
    $fields  = apps_db::fields('#iCMS@__article');
    if($fields['metadata']){
        $start  = 0;
        $perpage = 1000;
        $where  = "`metadata`!=''";
        $count  = iDB::value("SELECT count(*) FROM `#iCMS@__article` where {$where};");
        $page   = ceil($count/$perpage);

        $cpa = iDB::all("SELECT `cid`,`contentprop` FROM `#iCMS@__category` WHERE `contentprop`!=''");
        foreach ((array)$cpa as $key => $value) {
            $contentprop[$value['cid']] = unserialize($value['contentprop']);
        }
        for ($i=0; $i <=$page; $i++) {
            $offset = $i*$perpage;
            $limit  = "LIMIT {$offset},{$perpage}";
            $ids_array = iDB::all("SELECT `id` FROM `#iCMS@__article` where {$where} {$limit}");
            $ids = iSQL::values($ids_array);
            $ids = $ids ? $ids : '0';
            article_metadata($ids,$contentprop);
        }
    }
}
function article_metadata($ids,$contentprop=null){
    $where_sql = "WHERE `id` IN({$ids})";
    $resource = iDB::all("SELECT `id`,`cid`,`metadata` FROM `#iCMS@__article` {$where_sql}");
    foreach ((array)$resource as $key => $value) {
        $metadata  = unserialize($value['metadata']);
        $data = array();
        foreach ($metadata as $mkey => $mval) {
            $name = $contentprop[$value['cid']][$mkey];
            empty($name) && $name = $mkey;
            $data[$mkey] = array(
                'name'  =>$name,
                'key'   =>$mkey,
                'value' =>$mval
            );
        }
        $data = json_encode($data);
        $insert_id = iDB::insert('article_meta',array('id'=>$value['id'],'data'=>$data),true);
        var_dump($insert_id);
        flush_print($value['id'].($insert_id?'.....√':'.....×'));
    }
}

// redirect('upgrade.4.php');
