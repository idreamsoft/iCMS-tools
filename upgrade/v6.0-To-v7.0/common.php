<?php
define('iPHP_DEBUG', true);

require dirname(__FILE__).'/../iCMS.php';

if(!version_compare(substr(iCMS_VERSION, 1),'7.0','>=')){
    exit("请将升级程序[v6.0-To-v7.0],放到iCMS v7.0程序目录下");
}

iDB::$show_errors = true;

@set_time_limit(0);
flush_start();

function flush_start() {
    @header('X-Accel-Buffering: no');
    ob_start();
    ob_end_clean() ;
    ob_end_flush();
    ob_implicit_flush(true);
}
function flush_print($msg) {
    if(iPHP_SHELL){
        echo PHP_EOL.$msg.PHP_EOL;
    }else{
        echo '<pre>'.$msg.'</pre><hr />';
    }
    flush();
    ob_flush();
}

function upgrade_query($msg,$sql,$table=null) {
    flush_print($msg);
    flush_print($sql);
    if($table){
        if(iDB::check_table($table)){
            run_query($sql);
        }else{
            flush_print($table.'表不存在.跳过此处升级');
        }
    }else{
        run_query($sql);
    }
}
function run_query($sql) {
    $sql      = str_replace("\r", "\n", $sql);
    $resource = array();
    $num      = 0;
    $sql_array = explode(";\n", trim($sql));
    foreach($sql_array as $query) {
        $queries = explode("\n", trim($query));
        foreach($queries as $query) {
            $resource[$num] .= $query[0] == '#' ? '' : $query;
        }
        $num++;
    }
    unset($sql);

    foreach($resource as $key=>$query) {
        $query = trim($query);
        $query = str_replace('`icms_', '`#iCMS@__', $query);
        $query && iDB::query($query);
    }
}
function redirect($url){
    if(iPHP_SHELL){
        require dirname(__FILE__).'/'.$url;
    }else{
        iPHP::redirect($url);
    }
}

