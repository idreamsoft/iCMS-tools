<?php
defined('iPATH') OR exit('What are you doing?');

if(empty($total)){
    $total   = iDB_WP::value('
        SELECT count(ID)
        FROM `#wp@__posts` where `post_type`=\'post\'
    ');
    // $total   = 10000;
}

$multi        = iUI::page(array('total'=>$total,'perpage'=>$maxperpage,'nowindex'=>$_GET['page']));
$offset       = $multi->offset;

if($offset=="0"){
    if($wp_config['TRUNCATE']){
        iDB::query('TRUNCATE TABLE `#iCMS@__article_data`');
    }
    //
    if(iDB::value("SELECT `id` FROM `#iCMS@__article_data` limit 1 ")){
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
    SELECT `ID`, `post_content` FROM `#wp@__posts`
    '.$where_sql.'
');

foreach ((array)$posts as $key => $value) {

    $article =  array(
        'id'          => $value['ID'],
        'aid'         => $value['ID'],
        'body'        => $value['post_content'],
    );

    iDB::insert('article_data',$article);
}
