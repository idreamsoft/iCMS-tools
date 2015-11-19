#!/usr/local/php/bin/php
<?php
require_once(dirname(__FILE__).'/../iCMS.php');
error_reporting(E_ALL ^ E_NOTICE);
ini_set('memory_limit','2048M');

$path = '/wwwroot/www.ooxx.com/sitemap' ;
iPHP::$iTPL_MODE = "html";
$xml = iPHP::view('iCMS://sitemap.index.static.htm');
iFS::mkdir($path);
iFS::write($path.'/index.xml',$xml);

$tag = iPHP::view('iCMS://sitemap.baidu.tag.htm');
iFS::mkdir($path);
iFS::write($path.'/tag.xml',$tag);


$category   = iDB::all("SELECT `cid` FROM `#iCMS@__category` where `rootid`='0' and `status` ='1' and `appid` ='1' order by cid asc");


foreach ((array)$category as $key => $cat) {
	iPHP::assign('cid',(int)$cat['cid']);
	$xml = iPHP::view('iCMS://sitemap.baidu.htm');

	iFS::write($path.'/'.$cat['cid'].'.xml',$xml);
	echo $path.'/'.$cat['cid'].".xml\n";
}
