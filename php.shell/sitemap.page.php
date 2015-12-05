#!/usr/local/php/bin/php
<?php
require_once(dirname(__FILE__).'/../iCMS.php');
error_reporting(E_ALL ^ E_NOTICE);
ini_set('memory_limit','2048M');

$sitemap_dir  = 'sitemap/all';
$sitemap_path = iFS::path(iPATH.iCMS::$config['router']['html_dir'].$sitemap_dir);
$sitemap_url  = iCMS_URL.'/'.$sitemap_dir;

iPHP::$iTPL_MODE = "html";
iFS::mkdir($sitemap_path);
$total = iDB::value("SELECT count(*) FROM `#iCMS@__article` WHERE `status`='1'");
$row   = 3000;
$pn    = ceil($total/$row);
iPHP::assign('row',$row);

$index = '<?xml version="1.0" encoding="UTF-8"?>'.
$index.= '<sitemapindex>';

for ($i=1; $i <=$pn ; $i++) {
    $_GET['page'] = $i;
    $xml = iPHP::view('iCMS://sitemap.baidu.page.htm');
    $xmlPath = $sitemap_path."/{$i}.xml";
    iFS::write($xmlPath,$xml);
    echo $xmlPath.PHP_EOL;
    $index.= '<sitemap>';
    $index.= '<loc>'.$sitemap_url."/{$i}.xml".'</loc>';
    $index.= '<lastmod>'.date("Y-m-d").'</lastmod>';
    $index.= '</sitemap>';
}
$index.= '</sitemapindex>';
$index_path = $sitemap_path.'/index.xml';
iFS::write($index_path,$index);
echo $sitemap_url.'/index.xml'.PHP_EOL;
