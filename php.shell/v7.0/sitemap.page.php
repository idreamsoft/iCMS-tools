#!/usr/bin/env php
<?php
define('iPHP_DEBUG', true);
define('iPHP_TPL_DEBUG', true);
require_once(dirname(__FILE__).'/../iCMS.php');
error_reporting(E_ALL ^ E_NOTICE);
ini_set('memory_limit','2048M');

$sitemap_dir  = 'sitemap/all';
$sitemap_path = iFS::path(iPATH.iCMS::$config['router']['dir'].$sitemap_dir);
$sitemap_url  = iCMS_URL.'/'.$sitemap_dir;

$last_pn_path = $sitemap_path.'/article.lastpn.txt';
$last_pn = @file_get_contents($last_pn_path);
empty($last_pn) && $last_pn = 0;

iView::$gateway ='html';


iFS::mkdir($sitemap_path);
$total = iDB::value("SELECT count(*) FROM `#iCMS@__article` WHERE `status`='1'");
$row   = 3000;
$pn    = ceil($total/$row);
iView::assign('row',$row);
$index = '<?xml version="1.0" encoding="UTF-8"?>'.
$index.= '<sitemapindex>';

for ($i=1; $i <=$pn ; $i++) {
    $xmlPath = $sitemap_path."/{$i}.xml";
    if($last_pn<=$i){
        $_GET['page'] = $i;
        $xml = iView::fetch('/tools/sitemap.baidu.page.htm');
        iFS::write($xmlPath,$xml);
    }
    echo $xmlPath.PHP_EOL;
    $index.= '<sitemap>';
    $index.= '<loc>'.$sitemap_url."/{$i}.xml".'</loc>';
    $index.= '<lastmod>'.date("Y-m-d").'</lastmod>';
    $index.= '</sitemap>';
}
iFS::write($last_pn_path,$pn);

$index.= '</sitemapindex>';
$index_path = $sitemap_path.'/index.xml';
iFS::write($index_path,$index);
echo $sitemap_url.'/index.xml'.PHP_EOL;
