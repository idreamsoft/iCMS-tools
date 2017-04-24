#!/usr/local/php/bin/php
<?php
/**
 * iCMS - i Content Management System
 * Copyright (c) 2007-2017 idreamsoft.com iiimon Inc. All rights reserved.
 *
 * @author coolmoo <idreamsoft@qq.com>
 * @site http://www.idreamsoft.com
 * @licence http://www.idreamsoft.com/license.php
 */
require dirname(__file__).'/../iCMS.php';
ini_set('memory_limit','512M');

$username = 'admin';
$password = md5('123456');

members::$GATEWAY = 'bool';
if(!members::check($username,$password)){
    exit("账号或密码错误");
}

$buffer = ini_get('output_buffering');
echo str_repeat('', $buffer+1);
ob_end_flush();

iHttp::$CURLOPT_TIMEOUT        = 30; //数据传输的最大允许时间
iHttp::$CURLOPT_CONNECTTIMEOUT = 3;  //连接超时时间

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

$project   = iDB::all("SELECT * FROM `#iCMS@__spider_project` WHERE `auto`='1' order by `id` desc");
spider::$work = 'shell';
foreach ((array)$project as $key => $pro) {
    $GLOBALS['shutdown_pid'] = $pro['id'];
    $pfile = iPHP_APP_CACHE.'/spider.'.$pro['id'].'.pid';
    if(file_exists($pfile)){
        $time = filemtime($pfile);
        if($time-$project['lastupdate']>=$project['psleep']){
            unlink_pid();
        }else{
            echo "project[".$pro['id']."],runing...".PHP_EOL;
            continue;
        }
    }
    file_put_contents($pfile, $pro['id']);
    spider::$pid = $pro['id'];
    spider_urls::crawl("shell",$pro['id'],$pro['rid']);
    @unlink($pfile);
}
