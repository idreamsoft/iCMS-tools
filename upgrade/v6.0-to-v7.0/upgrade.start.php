<?php
require_once dirname(__FILE__).'/common.php';
if(iPHP_SHELL){
    redirect('upgrade.1.create.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>iCMS v6.0升级程序</title>
        <meta name="keywords" content="iCMS,iCMS内容管理系统,文章管理系统,PHP文章管理系统" />
        <meta name="description" content="iCMS 是一套采用 PHP 和 MySQL 构建的高效简洁的内容管理系统,为您的网站提供一个完美的开源解决方案" />
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta content="icmsdev.com" name="Copyright" />
        <script src="//apps.bdimg.com/libs/jquery/1.9.1/jquery.min.js"></script>
        <link rel="stylesheet" href="http://apps.bdimg.com/libs/bootstrap/3.3.4/css/bootstrap.min.css" type="text/css" />
    </head>
    <body>
        <div class="container">
            <h2>升级前请先备份数据!如有意外概不负责!!</h2>
            <h2>升级前请先备份数据!如有意外概不负责!!</h2>
            <h2>升级前请先备份数据!如有意外概不负责!!</h2>
            <p class="lead"></p>
            <a href="./upgrade.1.create.php" class="btn btn-primary btn-lg btn-block">开始升级</a>
        </div>
    </body>
</html>
