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
define('iPHP_DEBUG', true);
require dirname(__file__).'/../iCMS.php';
ini_set('memory_limit','512M');

$username = 'admin';
$password = md5('123456');

members::$GATEWAY = 'bool';
if(!members::check($username,$password)){
    exit("账号或密码错误");
}
files::init(array('userid'=> '1'));

function unlink_pid(){
    if($GLOBALS['shutdown_pid']){
        $pfile = iPHP_APP_CACHE.'/spider.'.$GLOBALS['shutdown_pid'].'.pid';
        echo $pfile." delete;".PHP_EOL;
        @unlink($pfile);
    }
}
if (!function_exists('pcntl_signal')) {
    function pcntl_signal($a=null,$b=null){
        return;
    }
}
//register_shutdown_function('shutdown');
declare(ticks = 1);
pcntl_signal(SIGTERM, "sig_handler");
pcntl_signal(SIGHUP,  "sig_handler");
pcntl_signal(SIGINT,  "sig_handler");

//信号处理函数
function sig_handler($signo){
     switch ($signo) {
        case SIGTERM:
            // 处理kill
            echo PHP_EOL."kill".PHP_EOL;
            unlink_pid();
            exit;
            break;
        case SIGHUP:
            //处理SIGHUP信号
            break;
        case SIGINT:
            //处理ctrl+c
            echo PHP_EOL."ctrl+c".PHP_EOL;
            unlink_pid();
            exit;
            break;
        default:
            // 处理所有其他信号
     }
}

function shutdown(){
    unlink_pid();
}
register_shutdown_function('shutdown');

if(empty($_SERVER['argv'][1])){
    exit("ERROR:need argc".PHP_EOL."exp:".$_SERVER['argv'][0]." [pid]".PHP_EOL.PHP_EOL.PHP_EOL);
}
iHttp::$CURLOPT_TIMEOUT        = 30; //数据传输的最大允许时间
iHttp::$CURLOPT_CONNECTTIMEOUT = 3;  //连接超时时间


// $project = array(
//     array('id'=>$_SERVER['argv'][1]),
// );

$project = explode(',', $_SERVER['argv'][1]);


// iFS::$PROXY_URL    = 'http://ooxx.com/proxy.php?url=';
// spider::$PROXY_URL = 'http://ooxx.com/proxy.php?url=';
spider::$work = 'shell';
foreach ((array)$project as $key => $pid) {
    if(empty($pid)){
        continue;
    }
    $GLOBALS['shutdown_pid'] = $pid;
    $pfile = iPHP_APP_CACHE.'/spider.'.$pid.'.pid';
    if(file_exists($pfile)){
        $project = spider::project($pid);
        $time = filemtime($pfile);
        if($time-$project['lastupdate']>=$project['psleep']){
            unlink_pid();
        }else{
            echo "project[".$pid."],runing...".PHP_EOL;
            continue;
        }
    }
    file_put_contents($pfile, $pid);
    spider::$pid = $pid;
    spider_urls::crawl("shell");
    @unlink($pfile);
}

