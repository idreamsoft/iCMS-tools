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

require dirname(__file__).'/../iCMS.php';
if(version_compare(PHP_VERSION,'5.5','>=')){
    require dirname(__file__).'/mysqli.class.php';
}else{
    require dirname(__file__).'/mysql.class.php';
}

$dede_config_file    = iPATH.'conf/dede_config.php';
$dede_sysconfig_file = iPATH.'conf/dede_sysconfig.php';

if($_POST['action']=='config'){
    /**
     * 配置转换程序
     */
    include dirname(__file__).'/iCMS.convert.config.php';
}
/**
 * 转换程序
 */
if($_GET['do']=='convert'){
    $step = $_GET['step'];
    /**
     * 连接DEDE数据库
     */
    $dede_config = include $dede_config_file;
    $dedeDB      = new iMysql_DEDE($dede_config,'DEDE_DB');

    /**
     * 获取DEDE系统配置
     */
    if(file_exists($dede_sysconfig_file)){
        $dede_sysconfig = include $dede_sysconfig_file;
    }else{
        $sysconfig = $dedeDB->all('
            SELECT `varname`, `value`
            FROM `#dede@__sysconfig`
        ');

        foreach ($sysconfig as $key => $value) {
            $dede_sysconfig[$value['varname']] = $value['value'];
        }

        iFS::write($dede_sysconfig_file,"<?php\n defined('iPHP') OR exit('Access Denied');\n return ".var_export($dede_sysconfig,true).';',false);
    }

    if($dede_sysconfig){
        $dede_file_uri  = rtrim($dede_sysconfig['cfg_basehost'],'/').'/'.
                          trim($dede_sysconfig['cfg_medias_dir'],'/').'/';
    }

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
            include dirname(__file__).'/iCMS.convert.arctype.php';
        break;
        case '2':
            /**
             * 内容索引
             */
            $msgTitle = '内容索引';
            include dirname(__file__).'/iCMS.convert.archives.php';
        break;
        case '3':
            /**
             * 普通文章
             */
            $msgTitle = '普通文章';
            include dirname(__file__).'/iCMS.convert.addonarticle.php';
        break;
        case '4':
            /**
             * 图片集
             */
            $msgTitle = '图片集';
            include dirname(__file__).'/iCMS.convert.addonimage.php';
        break;
        default:
            iPHP::success('恭喜您! 数据全部转换完成!');
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
            array("id"=>"btn_next","text"=>"继续","src"=>__SELF__.'?do=convert&step='.$nextStep)
        );
        $msg = "<h3>{$msgTitle}:{$total}条数据已转换完成</h3>";
    }
    $updateMsg = true;
    iPHP::dialog($msg,$loopurl?"src:".$loopurl:'',$dialogTime,$moreBtn,$updateMsg);

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
