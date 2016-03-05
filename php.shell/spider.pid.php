#!/usr/local/php/bin/php
<?php
/**
 * iCMS - i Content Management System
 * Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
 *
 * @author coolmoo <idreamsoft@qq.com>
 * @site http://www.idreamsoft.com
 * @licence http://www.idreamsoft.com/license.php
 * @version 6.0.0
 * @$Id: spider.shell.php 156 2013-03-22 13:40:07Z coolmoo $
 */
require dirname(__file__).'/../iCMS.php';
ini_set('memory_limit','512M');

require iPHP_APP_CORE.'/iACP.class.php';
$username      = 'admin';
$password      = 'admin password md5';
iMember::$AJAX = true;
iMember::check($username,$password);

iFS::$CURLOPT_TIMEOUT        = 30; //数据传输的最大允许时间
iFS::$CURLOPT_CONNECTTIMEOUT = 3;  //连接超时时间

function unlink_pid(){
    //echo PHP_EOL.'shutdown'.PHP_EOL;
    $pfile = iPHP_APP_CACHE.'/spider.'.$GLOBALS['shutdown_pid'].'.pid';
    echo $pfile." delete;".PHP_EOL;
    @unlink($pfile);
}
if (!function_exists('pcntl_signal')) {
    function pcntl_signal($a=null,$b=null){
        return;
    }
}
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
@register_shutdown_function('unlink_pid');

declare(ticks = 1);
pcntl_signal(SIGTERM, "sig_handler");
pcntl_signal(SIGHUP,  "sig_handler");
pcntl_signal(SIGINT,  "sig_handler");

if(empty($_SERVER['argv'][1])){
    exit("ERROR:need argc".PHP_EOL."exp:".$_SERVER['argv'][0]." [pid]".PHP_EOL.PHP_EOL.PHP_EOL);
}

$pid = $_SERVER['argv'][1];

iPHP::import(iPHP_APP_CORE .'/iSpider.Autoload.php');
spider::$work = 'shell';
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
spiderUrls::crawl("shell");
@unlink($pfile);


