<?php
require_once dirname(__FILE__).'/common.php';

upgrade_query(
    "删除无用表 `menu`",
    "DROP TABLE `icms_menu`;",
    "menu"
);

upgrade_query(
    "删除无用表 `sessions`",
    "DROP TABLE `icms_sessions`;",
    "sessions"
);

$sql = file_get_contents(dirname(__FILE__)."/create_table.sql");
upgrade_query("创建表",$sql);

redirect('upgrade.2.alter.php');
