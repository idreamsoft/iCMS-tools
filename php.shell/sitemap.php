#!/usr/local/php/bin/php
<?php
require_once(dirname(__FILE__).'/../iCMS.php');
error_reporting(E_ALL ^ E_NOTICE);
ini_set('memory_limit','2048M');

iPHP::$iTPL_MODE = "html";

$sitemap_dir  = 'sitemap';
$sitemap_path = iFS::path(iPATH.iCMS::$config['router']['html_dir'].$sitemap_dir);
$sitemap_url  = iCMS_URL.'/'.$sitemap_dir;

$xml       = iPHP::view('iCMS://sitemap.index.static.htm');
iFS::mkdir($sitemap_path);
iFS::write($sitemap_path.'/index.xml',$xml);

$tag = iPHP::view('iCMS://sitemap.baidu.tag.htm');
iFS::mkdir($sitemap_path);
iFS::write($sitemap_path.'/tag.xml',$tag);

$category   = iDB::all("SELECT `cid` FROM `#iCMS@__category` where `rootid`='0' and `status` ='1' and `appid` ='1' order by cid asc");

foreach ((array)$category as $key => $cat) {
	iPHP::assign('cid',(int)$cat['cid']);
	$xml = iPHP::view('iCMS://sitemap.baidu.htm');
    $xmlPath = $sitemap_path.'/'.$cat['cid'].'.xml';
	iFS::write($xmlPath,$xml);
	echo $xmlPath.PHP_EOL;
}
echo $sitemap_url.'/index.xml'.PHP_EOL;
echo $sitemap_url.'/tag.xml'.PHP_EOL;
