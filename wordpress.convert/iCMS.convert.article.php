<?php
defined('iPATH') OR exit('What are you doing?');

if(empty($total)){
    $total   = iDB_WP::value('
        SELECT count(*)
        FROM `#wp@__posts` where `post_type`=\'post\'
    ');
    // $total   = 10000;
}

$multi        = iUI::page(array('total'=>$total,'perpage'=>$maxperpage,'nowindex'=>$_GET['page']));
$offset       = $multi->offset;

if($offset=="0"){
    if($wp_config['TRUNCATE']){
        iDB::query('TRUNCATE TABLE `#iCMS@__article`');
        iDB::query('TRUNCATE TABLE `#iCMS@__category_map`');
        iDB::query('TRUNCATE TABLE `#iCMS@__tag_map`');
    }
    //
    if(iDB::value("SELECT `id` FROM `#iCMS@__article` limit 1 ")){
        iUI::alert("转换出错! 请确保 iCMS 文章表[article]为空!");
    }
}

$limit        = "LIMIT {$offset},{$maxperpage}";
$posts_ids = iDB_WP::all("
    SELECT `ID` FROM `#wp@__posts` where `post_type`='post'
    ".$limit."
");

$ids       = iSQL::values($posts_ids,'ID');
$ids       = $ids?$ids:'0';
$where_sql = "WHERE `ID` IN({$ids}) and `post_type`='post'";
$posts  = iDB_WP::all('
    SELECT `ID`, `post_author`, `post_date`,  `post_title`, `post_status`, `post_modified` FROM `#wp@__posts`
    '.$where_sql.'
');
$taxonomy  = iDB_WP::all('
SELECT
  `#wp@__terms`.`name`,`#wp@__terms`.`term_id`, `#wp@__term_relationships`.`object_id`,`#wp@__term_taxonomy`.`taxonomy`
FROM
  `#wp@__term_relationships`
  INNER JOIN `#wp@__term_taxonomy`
    ON (
      `#wp@__term_relationships`.`term_taxonomy_id` = `#wp@__term_taxonomy`.`term_taxonomy_id`
    )
  INNER JOIN `#wp@__terms`
    ON (
      `#wp@__term_taxonomy`.`term_id` = `#wp@__terms`.`term_id`
    )
WHERE `#wp@__term_relationships`.`object_id` IN ('.$ids.')
');
$category =array();
$tags     =array();
foreach ((array)$taxonomy as $k => $v) {
    if($v['taxonomy']=='category'){
        $category[$v['object_id']][]=$v['term_id'];
    }else{
        $tags[$v['object_id']][$v['term_id']]=$v['name'];
    }

}
foreach ((array)$posts as $key => $value) {

    $article =  array(
        'id'          => $value['ID'],
        'cid'         => $category[$value['ID']][0],
        'scid'        => implode(',', (array)$category[$value['ID']]),
        'title'       => addslashes($value['post_title']),
        'tags'        => addslashes(implode(',', (array)$tags[$value['ID']])),
        'pubdate'     => strtotime($value['post_modified']),
        'postime'     => strtotime($value['post_date']),
        'userid'      => '1',
        'postype'     => '1',
        'status'      => '1',
    );

    if($article['tags']){
        tag::add($article['tags'],$article['userid'],$article['id'],$article['cid']);
    }
    iMap::init('category','1');
    iMap::add($article['cid'],$article['id']);
//
    iDB::insert('article',$article);
}
