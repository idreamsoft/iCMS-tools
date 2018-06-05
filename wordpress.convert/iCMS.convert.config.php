<?php
defined('iPATH') OR exit('What are you doing?');
$nextStep    = 1;

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
    'CHARSET'    => 'utf8',
    'PREFIX'     => $db_prefix,
    'PREFIX_TAG' => '#wp@__',
    'PORT'       => '3306',
    'TRUNCATE'   => isset($_POST['TRUNCATE'])?true:false
);

iDB_WP::$dbFlag ='WP_DB';
iDB_WP::config($db_config);

$db_host OR iUI::alert("请填写数据库服务器地址",'js:top.callback("#DB_HOST");');
$db_user OR iUI::alert("请填写数据库用户名",'js:top.callback("#DB_USER");');
$db_password OR iUI::alert("请填写数据库密码",'js:top.callback("#DB_PASSWORD");');
$db_name OR iUI::alert("请填写数据库名",'js:top.callback("#DB_NAME");');
strstr($db_prefix, '.') && iUI::alert("您指定的数据表前缀包含点字符，请返回修改",'js:top.callback("#DB_PREFIX");');

$mysql_link = iDB_WP::connect('link');
$mysql_link OR iUI::alert("数据库连接出错",'js:top.callback();');
$GLOBALS[iDB_WP::$dbFlag] = $mysql_link;
iDB_WP::pre_set();
iDB_WP::select_db(true) OR iUI::alert("不能链接到数据库".$db_name,'js:top.callback("#DB_NAME");');
$content = var_export($db_config,true);
iFS::write($wp_config_file,"<?php\n defined('iPHP') OR exit('Access Denied');\n return ".$content.';',false);

$loopurl = iPHP_SELF.'?do=convert&step='.$nextStep;//loopurl($total,$query);
$moreBtn = array(
    array("id"=>"btn_stop","text"=>"停止","js"=>'return true'),
    array("id"=>"btn_next","text"=>"开始","src"=>$loopurl,"next"=>true)
);
$dtime     = 10;
$all_time  = $looptimes*$use_time+$looptimes+1;
$msg       = "<h3>配置完成，开始准备转换数据</h3>";
$updateMsg = false;
iUI::dialog($msg,$loopurl?"src:".$loopurl:'',$dtime,$moreBtn,$updateMsg);

