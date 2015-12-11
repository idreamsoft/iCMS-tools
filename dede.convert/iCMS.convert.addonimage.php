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
        FROM `#dede@__addonimages`
    ');
    // $total   = 10000;
}
$multi        = iCMS::page(array('total'=>$total,'perpage'=>$maxperpage,'nowindex'=>$_GET['page']));
$offset       = $multi->offset;
$limit        = "LIMIT {$offset},{$maxperpage}";
$addon_ids = $dedeDB->all('
    SELECT `aid` FROM `#dede@__addonimages`
    '.$limit.'
');
$ids       = iPHP::get_ids($addon_ids,'aid');
$ids       = $ids?$ids:'0';
$where_sql = "WHERE `aid` IN({$ids})";
$addon  = $dedeDB->all('
    SELECT * FROM `#dede@__addonimages`
    '.$where_sql.'
');

foreach ((array)$addon as $key => $value) {
    $body = str_replace($dede_file_uri, '', $value['body']);
    preg_match_all("@{dede:img ddimg='(.*?)' text='(.*?)' width='(\d+)' height='(\d+)'}@is", $value['imgurls'], $matches);
    foreach ((array)$matches[1] as $pkey => $picurl) {
       $picurl = str_replace($dede_file_uri, '', $picurl);
       $body  .='<p><img src="'.$picurl.'"/></p>';
       $body  .='<p><b>'.$matches[2][$pkey].'</b></p>';
       $body  .='#--iCMS.PageBreak--#';
    }

    $article_data = array(
        'id'   =>null,
        'aid'  =>$value['aid'],
        'body' =>addslashes($body)
    );

    iDB::insert('article_data',$article_data);
}
