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

$article   = iDB::all("SELECT `id`,`cid` FROM `#iCMS@__article` order by id asc");

iDB::query('TRUNCATE TABLE `#iCMS@__category_map`');

foreach ((array)$article as $key => $a) {
	if($node){
		iDB::insert('category_map',array(
			'node'  => $a['cid'],
			'iid'   => $a['id'],
			'appid' => iCMS_APP_ARTICLE,
		));
	}
	echo $a['id']."\n";
}
