<?php
defined('iPATH') OR exit('What are you doing?');

if(empty($total)){
    $total   = $dedeDB->value('
        SELECT count(*)
        FROM `#dede@__archives`
    ');
    // $total   = 10000;
}
$multi        = iCMS::page(array('total'=>$total,'perpage'=>$maxperpage,'nowindex'=>$_GET['page']));
$offset       = $multi->offset;
$limit        = "LIMIT {$offset},{$maxperpage}";
$archives_ids = $dedeDB->all('
    SELECT `id` FROM `#dede@__archives`
    '.$limit.'
');

$ids       = iPHP::get_ids($archives_ids);
$ids       = $ids?$ids:'0';
$where_sql = "WHERE `id` IN({$ids})";
$archives  = $dedeDB->all('
    SELECT * FROM `#dede@__archives`
    '.$where_sql.'
');

$dede_config['TRUNCATE'] && iDB::query('TRUNCATE TABLE `#iCMS@__article`');
$dede_config['TRUNCATE'] && iDB::query('TRUNCATE TABLE `#iCMS@__category_map`');
$dede_config['TRUNCATE'] && iDB::query('TRUNCATE TABLE `#iCMS@__prop_map`');
//
if(iDB::value("SELECT `id` FROM `#iCMS@__article` limit 1 ")){
    iPHP::alert("转换出错! 请确保 iCMS 文章表[article]为空!");
}

$flagMap= array(
    'c' =>array('推荐',1),
    'h' =>array('头条',2),
    'p' =>array('图片',3),
    'f' =>array('幻灯',4),
    's' =>array('滚动',5),
    'j' =>array('跳转',6),
    'a' =>array('特荐',7),
    'b' =>array('加粗',8),
);

iDB::query("DELETE FROM `#iCMS@__prop` WHERE `type` = 'article'");
foreach ($flagMap as $flagk => $flagv) {
    $prop = array(
        'rootid'   => '0',
        'cid'      => '0',
        'field'    => 'pid',
        'type'     => 'article',
        'ordernum' => '0',
        'name'     => $flagv[0],
        'val'      => $flagv[1],
    );
    iDB::insert('prop',$prop);
}

iPHP::import(iPHP_APP_CORE .'/iMAP.class.php');
iPHP::app('tag.class','static');

foreach ((array)$archives as $key => $value) {
    $flagArray = explode(',', $value['flag']);
    $flag = array();
    foreach ($flagArray as $fk => $fv) {
        $flag[]=$flagMap[$fv][1];
    }
    $value['litpic'] = str_replace($dede_file_uri, '', $value['litpic']);
    $array = getTags($dedeDB,$value['id'],$value['keywords']);
// var_dump($array);

    $article =  array(
        'id'          => $value['id'],
        'cid'         => $value['typeid'],
        'scid'        => $value['typeid2'],
        'pid'         => implode(',', (array)$flag),
        'title'       => addslashes($value['title']),
        'keywords'    => addslashes($array['keyword']),
        'tags'        => addslashes($array['tag']),
        'description' => addslashes($value['description']),
        'pubdate'     => $value['pubdate'],
        'postime'     => $value['senddate'],
        'weight'      => $value['weight'],
        'hits'        => $value['click'],
        'stitle'      => addslashes($value['shorttitle']),
        'source'      => addslashes($value['source']),
        'author'      => addslashes($value['writer']),
        'pic'         => addslashes($value['litpic']),
        'good'        => $value['goodpost'],
        'bad'         => $value['badpost'],
        'userid'      => '1',
        'postype'     => '1',
        'status'      => '1',
    );
    if($article['pic']){
        $article['haspic'] = '1';
    }
    if($value['mid']){
        $article['userid'] = $value['mid'];
    }
    if($article['pid']){
        map::init('prop','1');
        map::add($article['pid'],$article['id']);
    }
    if($article['tags']){
        tag::add($article['tags'],$article['userid'],$article['id'],$article['cid']);
    }
    map::init('category','1');
    map::add($article['cid'],$article['id']);
    $article['scid'] && map::add($article['scid'],$article['id']);

    iDB::insert('article',$article);
}
function getTags($dedeDB,$aid,$keywords=null){
    $variable = $dedeDB->all("
        SELECT * FROM `#dede@__taglist`
        WHERE `aid`='".$aid."'
    ");

    $tagArray = array();
    $tag = array();
    foreach ((array)$variable as $key => $value) {
        $tag[] = $value['tag'];
    }
    if($keywords){
        $keywordArray = explode(',', $keywords);
        $result  =  array_diff ( $keywordArray ,  $tag );
        $tagArray['keyword'] = implode(',', $result);
    }
    if($tag){
        $tagArray['tag'] = implode(',', $tag);
    }
    return $tagArray;
}
