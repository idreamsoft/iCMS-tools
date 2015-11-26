#!/usr/local/php/bin/php
<?php
require_once(dirname(__FILE__).'/../iCMS.php');
error_reporting(E_ALL ^ E_NOTICE);
ini_set('memory_limit','2048M');

$path = iPATH.'sitemap/all' ;
iPHP::$iTPL_MODE = "html";
iFS::mkdir($path);
$total = iDB::val("SELECT count(*) FROM `#iCMS@__article` WHERE `status`='1'");
$row   = 1000;
$pn    = ceil($total/$row);
iPHP::assign('row',$row);

for ($i=1; $i <=$pn ; $i++) {
    $_GET['page'] = $i;
    $xml = iPHP::view('iCMS://sitemap.baidu.page.htm');
    iFS::write($path."/{$i}.xml",$xml);
    echo $path.'/'.$i.".xml\n";
}
