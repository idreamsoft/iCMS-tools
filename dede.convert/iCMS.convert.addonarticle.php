<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.0.0
* @$Id: install.php 2330 2014-01-03 05:19:07Z coolmoo $
*/
defined('iPATH') OR exit('What are you doing?');

if(empty($total)){
    $total   = $dedeDB->value('
        SELECT count(*)
        FROM `#dede@__addonarticle`
    ');
    // $total   = 10000;
}
$multi        = iCMS::page(array('total'=>$total,'perpage'=>$maxperpage,'nowindex'=>$_GET['page']));
$offset       = $multi->offset;

if($offset=="0"){
    $dede_config['TRUNCATE'] && iDB::query('TRUNCATE TABLE `#iCMS@__article_data`;');

    if(iDB::value("SELECT `id` FROM `#iCMS@__article_data` limit 1 ")){
        iPHP::alert("转换出错! 请确保 iCMS 文章表[article_data]为空!");
    }
}

$limit     = "LIMIT {$offset},{$maxperpage}";
$addon_ids = $dedeDB->all('
    SELECT `aid` FROM `#dede@__addonarticle`
    '.$limit.'
');
$ids       = iPHP::get_ids($addon_ids,'aid');
$ids       = $ids?$ids:'0';
$where_sql = "WHERE `aid` IN({$ids})";
$addon  = $dedeDB->all('
    SELECT * FROM `#dede@__addonarticle`
    '.$where_sql.'
');

foreach ((array)$addon as $key => $value) {
    $value['body'] = str_replace($dede_file_uri, '', $value['body']);
    $article_data = array(
        'id'   =>null,
        'aid'  =>$value['aid'],
        'body' =>addslashes($value['body'])
    );

    iDB::insert('article_data',$article_data);
}
