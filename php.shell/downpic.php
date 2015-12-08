#!/usr/local/php/bin/php
<?php
define('iPHP_DEBUG',true);
set_time_limit(0);
require dirname(__file__).'/../iCMS.php';

ini_set('memory_limit','1024M');
iDB::$show_errors            = true;
iFS::$CURLOPT_TIMEOUT        = "30";
iFS::$CURLOPT_CONNECTTIMEOUT = "3";
iFS::$CURLOPT_USERAGENT      = 'Baiduspider-image+(+http://www.baidu.com/search/spider.htm)';

$where  = "haspic='0' AND `status` = '0' and postype='1'";
$start  = 0;
$preNum = 100;
$max    = iDB::value("
    SELECT count(id) FROM `#iCMS@__article`
    WHERE {$where} order by id desc;"
);
$page   = ceil($max/$preNum);
iDB::query("set interactive_timeout=24*3600");

// iPHP::app('article.table');
$articleApp = iPHP::app("admincp.article.app");
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
    $ids       = iCMS::get_ids($ids_array);
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
            continue;
        }
        echo $aid." ....start >>>";
        $body  = $articleApp->remotepic($body,true,$aid);
        $body  = str_replace('<p><img src=\"\" /></p>', '', $body);
        if($body && $aid){
            if(check_pic($body,$aid)){
            }else{
                iDB::update('article_data',array('body'=>$body),array('id'=>$adid));
                $picurl = $articleApp->remotepic($body,'autopic',$aid);
                $articleApp->pic($picurl,$aid);
                iDB::update('article',array('status'=>'1','postype'=>'1','pubdate'=>time()),array('id'=>$aid));
                echo "....ok!\n";
            }
        }

        gc_collect_cycles();
    }
    gc_collect_cycles();
    unset($article);
    return $changeNum;
}

function nopic($body){
    $body = stripcslashes($body);
    preg_match_all("/<img.*?src\s*=[\"|'|\s]*(http:\/\/.*?\.(gif|jpg|jpeg|bmp|png)).*?>/is",$body,$pic_array);
    $p_array = array_unique($pic_array[1]);

    if(empty($p_array)){
        return true;
    }
    return false;
}
function check_pic($body,$aid=0){
    $body = stripcslashes($body);
    preg_match_all("/<img.*?src\s*=[\"|'|\s]*(http:\/\/.*?\.(gif|jpg|jpeg|bmp|png)).*?>/is",$body,$pic_array);
    $p_array = array_unique($pic_array[1]);

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
    preg_match_all("/<img.*?src\s*=[\"|'|\s]*(http:\/\/.*?\.(gif|jpg|jpeg|bmp|png)).*?>/is",$body,$pic_array);
    $p_array = array_unique($pic_array[1]);
    $pics = array();
    if($p_array)foreach($p_array as $key =>$_pic) {
        $pics[$key] = trim($_pic);
    }
    if(empty($pics)){
        return false;
    }
    return true;
}


