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
require dirname(__file__).'/../iCMS.php';
if(version_compare(PHP_VERSION,'5.5','>=')){
    require dirname(__file__).'/mysqli.class.php';
}else{
    require dirname(__file__).'/mysql.class.php';
}

$dede_config_file    = iPATH.'conf/dede_config.php';
$dede_sysconfig_file = iPATH.'conf/dede_sysconfig.php';

/**
 * 转换程序
 */
if($_GET['do']=='convert'){
    $step        = $_GET['step'];
    /**
     * 连接DEDE数据库
     */
    $dede_config = include $dede_config_file;
    $dedeDB      = new iMysql($dede_config,'DEDE_DB');

    /**
     * 获取DEDE系统配置
     */
    if(empty($step)){
        $dede_sysconfig = $dedeDB->all('
            SELECT `aid`, `varname`, `value`
            FROM `#dede@__sysconfig`
        ');

        foreach ($dede_sysconfig as $key => $value) {
            $sysconfig[$value['varname']] = $value['value'];
        }

        $syscontent = var_export($sysconfig,true);
        iFS::write($dede_sysconfig_file,"<?php\n defined('iPHP') OR exit('Access Denied');\n return ".$syscontent.';',false);
    }else{
        $dede_sysconfig = include $dede_sysconfig_file;
        $dede_file_uri  =
            rtrim($dede_sysconfig['cfg_basehost'],'/').'/'.
            trim($dede_sysconfig['cfg_medias_dir'],'/').'/';
    }

    /**
     * 开始转换栏目
     */
    if($step=="1"){

// iDB::query('TRUNCATE TABLE `#iCMS@__category`');

        if(iDB::value("SELECT `cid` FROM `#iCMS@__category` limit 1 ")){
            iPHP::alert("转换出错! 请确保 iCMS 栏目表[category]为空!");
        }

        $rs = $dedeDB->all('
            SELECT *
            FROM `#dede@__arctype`
        ');

        $_count = count($rs);

        for ($i=0; $i < $_count; $i++) {
            $row  = $rs[$i];
            $data = array(
             'cid'             => $row['id'],
             'rootid'          => $row['reid'],
             'appid'           => '1',
             'name'            => $row['typename'],
             'ordernum'        => $row['sortrank'],
             'status'          => empty($row['ishidden'])?1:0,
             'domain'          => $row['moresite']?$row['siteurl']:'',
             // 'id'           => $row['defaultname'],
             'mode'            => '1',
             'issend'          => $row['issend'],
             'isucshow'        => $row['issend']?'1':'0',
             'indexTPL'        => $row['tempindex'],
             'listTPL'         => $row['templist'],
             'contentTPL'      => $row['temparticle'],
             // 'categoryRule' => $row['namerule2'],
             'categoryRule'    => '/{CDIR}/',
             // 'contentRule'  => $row['namerule'],
             'description'     => $row['description'],
             'keywords'        => $row['keywords'],
             'title'           => $row['seotitle'],
            );
            if($row['isdefault']=='-1'){
                $data['mode']='2';
            }

            $data['dir'] = str_replace('{cmspath}', '', $row['typedir']);
            $data['dir'] = str_replace($sysconfig['cfg_arcdir'], '', $data['dir']);
            $data['dir'] = trim($data['dir'],'/');

            if(stripos($data['dir'], 'http://')!==false){
                $data['url'] = $data['dir'];
                $data['dir'] = '';
            }

            if($row['defaultname']!='index.html'){
                $data['categoryRule'] = '/{CDIR}/'.$row['defaultname'];
            }
            $data['contentRule'] = str_replace(
                array('{aid}','{typedir}','{Y}','{M}','{D}','{timestamp}','{pinyin}'),
                array('{ID}','{CDIR}','{YYYY}','{MM}','{DD}','{TIME}','{LINK}/{ID}'),
                $row['namerule']
            );
            $data['indexTPL']   = str_replace('{style}', $sysconfig['cfg_df_style'], $data['indexTPL']);
            $data['listTPL']    = str_replace('{style}', $sysconfig['cfg_df_style'], $data['listTPL']);
            $data['contentTPL'] = str_replace('{style}', $sysconfig['cfg_df_style'], $data['contentTPL']);
            $cid = iDB::insert('category',$data);
        }
        $loopurl = __SELF__.'?do=convert&step=2';//loopurl($total,$query);
        $moreBtn = array(
            array("id"=>"btn_stop","text"=>"停止","js"=>'return true'),
            array("id"=>"btn_next","text"=>"继续","src"=>$loopurl,"next"=>true)
        );
        $dtime     = 10;
        $msg       = "<h3>{$_count}个栏目已转换完成,准备转换内容数据</h3>";
        $updateMsg = true;
        iPHP::dialog($msg,$loopurl?"src:".$loopurl:'',$dtime,$moreBtn,$updateMsg);
    }
    /**
     * 开始转换文章
     */
    if($step=="2"){
        $dialogTime  = 0.1;
        $maxperpage  = 1000;
        $total       = (int)$_GET['total'];
        $timer_start = iPHP::timer_start();

        if(empty($total)){
            $total   = $dedeDB->value('
                SELECT count(*)
                FROM `#dede@__archives`
            ');
            // $total   = 10000;
        }
        $multi        = iCMS::page(array('total'=>$total,'perpage'=>$maxperpage,'nowindex'=>$_GET['page']));
        $offset       = $multi->offset;
        $limit        = "LIMIT {$offset},{$maxperpage}";
        $archives_ids = $dedeDB->all('
            SELECT `id` FROM `#dede@__archives`
            '.$limit.'
        ');

        $ids       = iPHP::get_ids($archives_ids);
        $ids       = $ids?$ids:'0';
        $where_sql = "WHERE `id` IN({$ids})";
        $archives  = $dedeDB->all('
            SELECT * FROM `#dede@__archives`
            '.$where_sql.'
        ');

// iDB::query('TRUNCATE TABLE `#iCMS@__article`');
// iDB::query('TRUNCATE TABLE `#iCMS@__category_map`');
// iDB::query('TRUNCATE TABLE `#iCMS@__prop_map`');
//
        if(iDB::value("SELECT `id` FROM `#iCMS@__article` limit 1 ")){
            iPHP::alert("转换出错! 请确保 iCMS 文章表[article]为空!");
        }

        $flagMap= array(
            'c' =>1,
            'h' =>2,
            'p' =>3,
            'f' =>4,
            's' =>5,
            'j' =>6,
            'a' =>7,
            'b' =>8,
        );
        iPHP::import(iPHP_APP_CORE .'/iMAP.class.php');
        foreach ((array)$archives as $key => $value) {
            $flagArray = explode(',', $value['flag']);
            $flag = array();
            foreach ($flagArray as $fk => $fv) {
                $flag[]=$flagMap[$fv];
            }
            $value['litpic'] = str_replace($dede_file_uri, '', $value['litpic']);
            $article =  array(
                'id'          => $value['id'],
                'cid'         => $value['typeid'],
                'scid'        => $value['typeid2'],
                'pid'         => implode(',', (array)$flag),
                'title'       => addslashes($value['title']),
                'keywords'    => addslashes($value['keywords']),
                'description' => addslashes($value['description']),
                'pubdate'     => $value['pubdate'],
                'postime'     => $value['senddate'],
                'weight'      => $value['weight'],
                'hits'        => $value['click'],
                'stitle'      => addslashes($value['shorttitle']),
                'source'      => addslashes($value['source']),
                'author'      => addslashes($value['writer']),
                'pic'         => addslashes($value['litpic']),
                'good'        => $value['goodpost'],
                'bad'         => $value['badpost'],
                'userid'      => '1',
                'postype'     => '1',
                'status'      => '1',
            );
            if($article['pic']){
                $article['haspic'] = '1';
            }
            if($value['mid']){
                $article['userid'] = $value['mid'];
            }
            if($article['pid']){
                map::init('prop','1');
                map::add($article['pid'],$article['id']);
            }

            map::init('category','1');
            map::add($article['cid'],$article['id']);
            $article['scid'] && map::add($article['scid'],$article['id']);

            iDB::insert('article',$article);
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
        $msg = "<h3>共{$total}篇文章索引,已转换".$multi->nowindex*$multi->perpage."篇</h3>";
        $msg.= "<h3>本程序将分成".$multi->totalpage."次转换,".$multi->perpage."篇/次</h3>";
        $msg.= "<h3>本次用时".$use_time."秒,总用时".$urlQuery["alltime"]."秒</h3>";

        if($page>$multi->totalpage){
            $moreBtn = array(
                array("id"=>"btn_stop","text"=>"取消","js"=>'return true',"next"=>true),
                array("id"=>"btn_next","text"=>"继续","src"=>__SELF__.'?do=convert&step=3')
            );
            $msg = "<h3>{$total}篇文章索引已转换完成</h3>";
        }
        $updateMsg = true;
        iPHP::dialog($msg,$loopurl?"src:".$loopurl:'',$dialogTime,$moreBtn,$updateMsg);
    }
    if($step=="3"){
        $dialogTime  = 0.1;
        $maxperpage  = 1000;
        $total       = (int)$_GET['total'];
        $timer_start = iPHP::timer_start();

// iDB::query('TRUNCATE TABLE `#iCMS@__article_data`;');

        if(iDB::value("SELECT `id` FROM `#iCMS@__article_data` limit 1 ")){
            iPHP::alert("转换出错! 请确保 iCMS 文章表[article_data]为空!");
        }

        if(empty($total)){
            $total   = $dedeDB->value('
                SELECT count(*)
                FROM `#dede@__addonarticle`
            ');
            // $total   = 10000;
        }
        $multi        = iCMS::page(array('total'=>$total,'perpage'=>$maxperpage,'nowindex'=>$_GET['page']));
        $offset       = $multi->offset;
        $limit        = "LIMIT {$offset},{$maxperpage}";
        $addon_ids = $dedeDB->all('
            SELECT `aid` FROM `#dede@__addonarticle`
            '.$limit.'
        ');
        $ids       = iPHP::get_ids($addon_ids,'aid');
        $ids       = $ids?$ids:'0';
        $where_sql = "WHERE `aid` IN({$ids})";
        $addon  = $dedeDB->all('
            SELECT * FROM `#dede@__addonarticle`
            '.$where_sql.'
        ');

        foreach ((array)$addon as $key => $value) {
            $value['body'] = str_replace($dede_file_uri, '', $value['body']);
            $article_data = array(
                'id'   =>null,
                'aid'  =>$value['aid'],
                'body' =>addslashes($value['body'])
            );

            iDB::insert('article_data',$article_data);
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
        $msg = "<h3>共{$total}篇文章内容,已转换".$multi->nowindex*$multi->perpage."篇</h3>";
        $msg.= "<h3>本程序将分成".$multi->totalpage."次转换,".$multi->perpage."篇/次</h3>";
        $msg.= "<h3>本次用时".$use_time."秒,总用时".$urlQuery["alltime"]."秒</h3>";

        if($page>$multi->totalpage){
            $moreBtn = array(
                array("id"=>"btn_stop","text"=>"取消","js"=>'return true',"next"=>true),
                array("id"=>"btn_next","text"=>"继续","src"=>__SELF__.'?do=convert&step=4')
            );
            $msg = "<h3>{$total}篇文章内容已转换完成</h3>";
        }
        $updateMsg = true;
        iPHP::dialog($msg,$loopurl?"src:".$loopurl:'',$dialogTime,$moreBtn,$updateMsg);

    }
}

if($_POST['action']=='config'){

    $db_host     = trim($_POST['DB_HOST']);   // 服务器名或服务器ip,一般为localhost
    $db_user     = trim($_POST['DB_USER']);     // 数据库用户
    $db_password = trim($_POST['DB_PASSWORD']);   //数据库密码
    $db_name     = trim($_POST['DB_NAME']);     // 数据库名
    $db_prefix   = trim($_POST['DB_PREFIX']);       // 表名前缀, 同一数据库安装多个请修改此处
    $db_charset  = trim($_POST['DB_CHARSET']);

    $db_config = array(
        'HOST'       => $db_host,
        'USER'       => $db_user,
        'PASSWORD'   => $db_password,
        'DB'         => $db_name,
        'CHARSET'    => $db_charset,
        'PREFIX'     => $db_prefix,
        'PREFIX_TAG' => '#dede@__',
        'PORT'       => '3306',
        'TRUNCATE'   => isset($_POST['TRUNCATE'])?true:false
    );
    $dedeDB = new iMysql($db_config,'DEDE_DB');

	$db_host OR iPHP::alert("请填写数据库服务器地址",'js:top.callback("#DB_HOST");');
	$db_user OR iPHP::alert("请填写数据库用户名",'js:top.callback("#DB_USER");');
	$db_password OR iPHP::alert("请填写数据库密码",'js:top.callback("#DB_PASSWORD");');
	$db_name OR iPHP::alert("请填写数据库名",'js:top.callback("#DB_NAME");');
	strstr($db_prefix, '.') && iPHP::alert("您指定的数据表前缀包含点字符，请返回修改",'js:top.callback("#DB_PREFIX");');

    $mysql_link = $dedeDB->connect('link');
	$mysql_link OR iPHP::alert("数据库连接出错",'js:top.callback();');
    $GLOBALS[$dedeDB->dbFlag] = $mysql_link;
    $dedeDB->pre_set();
    $dedeDB->select_db(true) OR iPHP::alert("不能链接到数据库".$db_name,'js:top.callback("#DB_NAME");');
    $content = var_export($db_config,true);
	iFS::write($dede_config_file,"<?php\n defined('iPHP') OR exit('Access Denied');\n return ".$content.';',false);

    $loopurl = __SELF__.'?do=convert&step=1';//loopurl($total,$query);
    $moreBtn = array(
        array("id"=>"btn_stop","text"=>"停止","js"=>'return true'),
        array("id"=>"btn_next","text"=>"开始","src"=>$loopurl,"next"=>true)
    );
    $dtime     = 10;
    $all_time  = $looptimes*$use_time+$looptimes+1;
    $msg       = "<h3>配置完成，开始准备转换数据</h3>";
    $updateMsg = false;
    iPHP::dialog($msg,$loopurl?"src:".$loopurl:'',$dtime,$moreBtn,$updateMsg);
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
