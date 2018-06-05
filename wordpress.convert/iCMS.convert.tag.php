<?php
defined('iPATH') OR exit('What are you doing?');

if(empty($total)){
    $total   = iDB_WP::value("
        SELECT count(*)
        FROM
          `#wp@__terms` AS T
          INNER JOIN `#wp@__term_taxonomy` AS TT
            ON (
              T.`term_id` = TT.`term_id`
            )
        WHERE TT.`taxonomy` = 'post_tag'
    ");
    // $total   = 10000;
}
$multi        = iUI::page(array('total'=>$total,'perpage'=>$maxperpage,'nowindex'=>$_GET['page']));
$offset       = $multi->offset;

if($offset=="0"){
    $wp_config['TRUNCATE'] && iDB::query('TRUNCATE TABLE `#iCMS@__tag`');

    if(iDB::value("SELECT `id` FROM `#iCMS@__tag` limit 1 ")){
        iUI::alert("转换出错! 请确保 iCMS 标签表[tag]为空!");
    }
}

$limit = "LIMIT {$offset},{$maxperpage}";
$terms_ids = iDB_WP::all("
    SELECT
      T.`term_id`
    FROM
      `#wp@__terms` AS T
      INNER JOIN `#wp@__term_taxonomy` AS TT
        ON (
          T.`term_id` = TT.`term_id`
        )
    WHERE TT.`taxonomy` = 'post_tag'
    ".$limit."
");

$ids       = iSQL::values($terms_ids,'term_id');
$ids       = $ids?$ids:'0';
$where_sql = " AND T.`term_id` IN({$ids})";
$all   = iDB_WP::all("
    SELECT
      T.`name`, T.`term_id`, TT.`count`, TT.`description`, TT.`parent`
    FROM
      `#wp@__terms` AS T
      INNER JOIN `#wp@__term_taxonomy` AS TT
        ON (
          T.`term_id` = TT.`term_id`
        )
    WHERE TT.`taxonomy` = 'post_tag'
    ".$where_sql."
");


foreach ((array)$all as $key => $row) {
    $data = array(
        'id'          =>$row['term_id'],
        'name'        =>$row['name'],
        'title'       =>$row['name'],
        'count'       =>$row['count'],
        'description' =>$row['description'],
        'rootid'      =>$row['parent'],
        'field'       =>'tags',
    );
    $tid = iDB::insert('tag',$data);
}
