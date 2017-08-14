#!/usr/bin/env php
<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/
define('iPHP_DEBUG',true);
set_time_limit(0);
require dirname(__file__).'/../iCMS.php';

files::init(array('userid'=> '1'));

ini_set('memory_limit','1024M');
iDB::$show_errors            = true;
iHttp::$CURLOPT_TIMEOUT        = "30";
iHttp::$CURLOPT_CONNECTTIMEOUT = "3";
iHttp::$CURLOPT_USERAGENT      = 'Baiduspider-image+(+http://www.baidu.com/search/spider.htm)';
$where  = " `status` = '0' and postype='1' ";

$start  = 0;
$preNum = 100;
$max    = iDB::value("
    SELECT count(id) FROM `#iCMS@__article`
    WHERE {$where} order by id desc;"
);
$page   = ceil($max/$preNum);
iDB::query("set interactive_timeout=24*3600");

$articleApp = new articleAdmincp();
$changeNum = 0;
for ($i=0; $i <=$page; $i++) {
    $offset = ($i*$preNum)-$changeNum;
    run($offset,$preNum);
}

function run($offset=0,$preNum=10){
    global $where,$articleApp,$changeNum;
    echo "LIMIT {$offset},{$preNum}\n";
    $ids_array   = iDB::all("
        SELECT id FROM #iCMS@__article
        WHERE {$where}
        order by id desc
        LIMIT {$offset},{$preNum}
    ");
    $ids       = iSQL::values($ids_array);
    $ids       = $ids?$ids:'0';
    $where_sql = "WHERE a.id IN({$ids})";

    $article   = iDB::all("
        SELECT a.id,a.cid,a.tags,d.body,d.id as adid
        FROM #iCMS@__article as a
        LEFT JOIN #iCMS@__article_data AS d
        ON a.id = d.aid
        {$where_sql}
    ");
    foreach ((array)$article as $key => $row) {
        $aid  = $row['id'];
        $adid = $row['adid'];
        $body = $row['body'];

        $changeNum++;
        if(nopic($body)){
            iDB::update('article',array('status'=>'1','pubdate'=>time()),array('id'=>$aid));
            echo $aid." onpic....status:1 continue\n";
            // baidu_ping_url($aid);
            continue;
        }
        echo $aid." ....start >>>";
        $body  = filesAdmincp::remotepic($body,true,$aid);
        $body  = str_replace('<p><img src=\"\" /></p>', '', $body);

        if($body && $aid){
            if(check_pic($body,$aid)){
            }else{
                iDB::update('article_data',array('body'=>$body),array('id'=>$adid));
                $picurl = filesAdmincp::remotepic($body,'autopic',$aid);
                $articleApp->set_pic($picurl,$aid);
                iDB::update('article',array('status'=>'1','postype'=>'1','pubdate'=>time()),array('id'=>$aid));
                // baidu_ping_url($aid);
                echo "....ok!\n";
            }
        }

        // $tags = $row['tags'];
        // // $tagsArray = explode(',', string)
        // $tags = str_replace(',', "','", $tags);
        // if($row['tags']){
        //     iDB::query("update icms_tags set `status`='1' where `name` in('$tags')");
        //     echo $tags." ....update\n";
        // }
        // exit;
        gc_collect_cycles();
    }
    gc_collect_cycles();
    unset($article);
    return $changeNum;
}

function baidu_ping_url($aid){
    // return;
    $ii   = sprintf("%08s",$aid);
    $url[]= str_replace('{ID}', $ii, "http://www.ooxx.com/article/{ID}.shtml");
    $url[]= str_replace('{ID}', $ii, "https://m.ooxx.com/article/{ID}.shtml");
    print_r($url);
    print_r(plugin_baidu::ping($url));
    echo "\n";
}

function nopic($body){
    $body = stripcslashes($body);
    preg_match_all('@<img[^>]+src=(["\']?)(.*?)\\1[^>]*?>@is',$body,$pic_array);
    $p_array = array_unique($pic_array[2]);

    if(empty($p_array)){
        return true;
    }
    return false;
}
function check_pic($body,$aid=0){
    $body = stripcslashes($body);
    preg_match_all('@<img[^>]+src=(["\']?)(.*?)\\1[^>]*?>@is',$body,$pic_array);
    $p_array = array_unique($pic_array[2]);

    foreach((array)$p_array as $key =>$url) {
        $url = trim($url);
        $filpath = iFS::fp($url, 'http2iPATH');
        // var_dump($filpath);
        list($owidth, $oheight, $otype) = @getimagesize($filpath);
        if(empty($otype)){
            var_dump($filpath,$otype);
            if($aid){
                iDB::update('article',array('status'=>'2','postype'=>'1'),array('id'=>$aid));
                echo $aid." status:2\n";
            }
            return true;
        }
    }
    return false;
}
function haspic($body){
    $body = stripcslashes($body);
    preg_match_all('@<img[^>]+src=(["\']?)(.*?)\\1[^>]*?>@is',$body,$pic_array);
    $p_array = array_unique($pic_array[2]);
    $pics = array();
    if($p_array)foreach($p_array as $key =>$_pic) {
        $pics[$key] = trim($_pic);
    }
    if(empty($pics)){
        return false;
    }
    return true;
}

function fopen_url($url,$mo=false) {
    $uri=parse_url($url);
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $url);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT,2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl_handle, CURLOPT_FAILONERROR,1);
    curl_setopt($curl_handle, CURLOPT_REFERER,$uri['scheme'].'://'.$uri['host']);
    if($mo){
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 4.2.1; en-us; Nexus 5 Build/JOP40D) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19');
    }else{
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/3.0.195.38 Safari/532.0');
    }
    $file_content = curl_exec($curl_handle);
    curl_close($curl_handle);
    return $file_content;
}
