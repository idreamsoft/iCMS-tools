<?php
require_once dirname(__FILE__).'/common.php';
$sql = file_get_contents(dirname(__FILE__)."/create_table.sql");
upgrade_query("创建表",$sql);
redirect('upgrade.3.php');
