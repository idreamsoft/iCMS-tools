#!/usr/local/php/bin/php
<?php
require_once(dirname(__FILE__).'/../iCMS.php');
error_reporting(E_ALL ^ E_NOTICE);

$htmlApp = iPHP::app("admincp.html.app");
$htmlApp->CreateIndex('{iTPL}/index.htm','index');
