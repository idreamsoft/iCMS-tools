<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.0.0
* @$Id: install.php 2330 2014-01-03 05:19:07Z coolmoo $
*/
define('iPHP_DB_NEW_LINK', true);//多数据连接

require dirname(__FILE__).'/../iCMS.php';

if(version_compare(PHP_VERSION,'5.5','>=') && extension_loaded('mysqli')){
    require_once dirname(__FILE__).'/iMysqli.class.php';
}elseif(extension_loaded('mysql')){
    require_once dirname(__FILE__).'/iMysql.class.php';
}else{
    trigger_error('您的 PHP 环境看起来缺少 MySQL 数据库支持扩展。',E_USER_ERROR);
}

// iDB_WP::set_server_id(2);
iDB_WP::$show_errors  = true;

$wp_config_file    = iPHP_APP_CACHE.'/wp_config.php';

if($_POST['action']=='config'){
    /**
     * 配置转换程序
     */
    include dirname(__FILE__).'/iCMS.convert.config.php';
}
/**
 * 转换程序
 */
if($_GET['do']=='convert'){
    $step = $_GET['step'];
    /**
     * 连接wordpress数据库
     */
    $wp_config   = include $wp_config_file;
    iDB_WP::config($wp_config);

    $nextStep    = $step+1;
    $dialogTime  = 0.1;
    $maxperpage  = 1000;
    $total       = (int)$_GET['total'];
    $timer_start = iPHP::timer_start();
    switch ($step) {
        case '1':
            /**
             * 分类
             */
            $msgTitle = '栏目';
            include dirname(__FILE__).'/iCMS.convert.category.php';
        break;
        case '2':
            /**
             * 分类
             */
            $msgTitle = '标签';
            include dirname(__FILE__).'/iCMS.convert.tag.php';
        break;
        case '3':
            /**
             * 内容索引
             */
            $msgTitle = '文章索引';
            include dirname(__FILE__).'/iCMS.convert.article.php';
        break;
        case '4':
            /**
             * 内容索引
             */
            $msgTitle = '文章内容';
            include dirname(__FILE__).'/iCMS.convert.article_data.php';
        break;
        // case '3':
        //     /**
        //      * 普通文章
        //      */
        //     $msgTitle = '普通文章';
        //     include dirname(__FILE__).'/iCMS.convert.addonarticle.php';
        // break;

        default:
            iUI::success('恭喜您! 数据全部转换完成!');
            exit();
        break;
    }

    $timer_stop = iPHP::timer_stop();
    $alltime    = $_GET['alltime']+$dialogTime;
    $use_time   = $timer_stop-$timer_start;
    $page       = $multi->nowindex+1;

    $urlQuery["alltime"]     = $alltime+$use_time;
    $urlQuery["timer_start"] = $timer_start;
    $urlQuery["total"]       = $total;
    $loopurl = loopurl($total,$urlQuery);
    $moreBtn = array(
        array("id"=>"btn_stop","text"=>"停止","js"=>'return true'),
        array("id"=>"btn_next","text"=>"继续","src"=>$loopurl,"next"=>true)
    );
    $msg = "<h3>{$msgTitle}:共{$total}条数据,已转换".$multi->nowindex*$multi->perpage."条数据</h3>";
    $msg.= "<h3>本程序将分成".$multi->totalpage."次转换,".$multi->perpage."条数据/次</h3>";
    $msg.= "<h3>本次用时".$use_time."秒,总用时".$urlQuery["alltime"]."秒</h3>";

    if($page>$multi->totalpage){
        $moreBtn = array(
            array("id"=>"btn_stop","text"=>"取消","js"=>'return true',"next"=>true),
            array("id"=>"btn_next","text"=>"继续","src"=>iPHP_SELF.'?do=convert&step='.$nextStep)
        );
        $msg = "<h3>{$msgTitle}:{$total}条数据已转换完成</h3>";
    }
    $updateMsg = true;
    iUI::dialog($msg,$loopurl?"src:".$loopurl:'',$dialogTime,$moreBtn,$updateMsg);
}

function loopurl($total,$_query){
    if ($total>0 && $GLOBALS['page']<$total){
        //$p++;
        $url  = $_SERVER["REQUEST_URI"];
        $urlA = parse_url($url);

        parse_str($urlA["query"], $query);
        $query['page']      = $GLOBALS['page'];
        $query              = array_merge($query, (array)$_query);
        $urlA["query"]      = http_build_query($query);
        $url    = $urlA["path"].'?'.$urlA["query"];
        return $url;
        //iPHP::gotourl($url);
    }
}
function charsetTrans($html,$out,$encode){
    if(is_array($html)){
        foreach ($html as $key => $value) {
            return charsetTrans($value,$out,$encode);
        }
    }else{
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($html,$out,$encode);
        } elseif (function_exists('iconv')) {
            return iconv($encode,$out, $html);
        } else {
            iPHP::throwException('charsetTrans failed, no function');
        }
    }
}
