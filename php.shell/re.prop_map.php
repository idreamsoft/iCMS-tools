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
 * @$Id: category.map.php 156 2013-03-22 13:40:07Z coolmoo $
 */
require dirname(__file__).'/../iCMS.php';

iDB::query('TRUNCATE TABLE `#iCMS@__prop_map`');

$article   = iDB::all("SELECT `id`,`pid` FROM `#iCMS@__article` order by id asc");

foreach ((array)$article as $key => $a) {
	if($node){
		iDB::insert('prop_map',array(
			'node'  => $a['pid'],
			'iid'   => $a['id'],
			'appid' => iCMS_APP_ARTICLE,
		));
	}
	echo $a['iid']."\n";
}
