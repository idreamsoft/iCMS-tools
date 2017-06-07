<?php

require_once dirname(__FILE__).'/common.php';

if(iDB::check_table('config') && !iDB::check_table('config_v6')){
    upgrade_query(
        "重命名 `config` => `config_v6`",
        "RENAME TABLE `icms_config` TO `icms_config_v6`;"
    );
    upgrade_query(
        "创建新config表",
        "CREATE TABLE `icms_config` (
  `appid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` mediumtext NOT NULL,
  PRIMARY KEY (`appid`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;"
    );
    upgrade_query(
        "写入数据",
        "
    INSERT  INTO `icms_config`(`appid`,`name`,`value`) values
    (0,'site','{\"name\":\"iCMS\",\"seotitle\":\"给我一套程序，我能搅动互联网\",\"keywords\":\"iCMS,iCMS内容管理系统,文章管理系统,PHP文章管理系统\",\"description\":\"iCMS 是一套采用 PHP 和 MySQL 构建的高效简洁的内容管理系统,为您的网站提供一个完美的开源解决方案\",\"icp\":\"\"}'),
    (0,'router','{\"url\":\"https:\\/\\/www.icmsdev.com\",\"404\":\"https:\\/\\/www.icmsdev.com\\/public\\/404.htm\",\"public\":\"https:\\/\\/www.icmsdev.com\\/public\",\"user\":\"https:\\/\\/www.icmsdev.com\\/user\",\"dir\":\"\\/\",\"ext\":\".html\",\"speed\":\"5\",\"rewrite\":\"0\",\"config\":{\"user\":[\"\\/user\",\"api.php?app=user\"],\"user:home\":[\"\\/user\\/home\",\"api.php?app=user&do=home\"],\"user:publish\":[\"\\/user\\/publish\",\"api.php?app=user&do=manage&pg=publish\"],\"user:article\":[\"\\/user\\/article\",\"api.php?app=user&do=manage&pg=article\"],\"user:category\":[\"\\/user\\/category\",\"api.php?app=user&do=manage&pg=category\"],\"user:comment\":[\"\\/user\\/comment\",\"api.php?app=user&do=manage&pg=comment\"],\"user:inbox\":[\"\\/user\\/inbox\",\"api.php?app=user&do=manage&pg=inbox\"],\"user:inbox:uid\":[\"\\/user\\/inbox\\/{uid}\",\"api.php?app=user&do=manage&pg=inbox&user={uid}\"],\"user:manage\":[\"\\/user\\/manage\",\"api.php?app=user&do=manage\"],\"user:manage:favorite\":[\"\\/user\\/manage\\/favorite\",\"api.php?app=user&do=manage&pg=favorite\"],\"user:manage:fans\":[\"\\/user\\/manage\\/fans\",\"api.php?app=user&do=manage&pg=fans\"],\"user:manage:follow\":[\"\\/user\\/manage\\/follow\",\"api.php?app=user&do=manage&pg=follow\"],\"user:profile\":[\"\\/user\\/profile\",\"api.php?app=user&do=profile\"],\"user:profile:base\":[\"\\/user\\/profile\\/base\",\"api.php?app=user&do=profile&pg=base\"],\"user:profile:avatar\":[\"\\/user\\/profile\\/avatar\",\"api.php?app=user&do=profile&pg=avatar\"],\"user:profile:setpassword\":[\"\\/user\\/profile\\/setpassword\",\"api.php?app=user&do=profile&pg=setpassword\"],\"user:profile:bind\":[\"\\/user\\/profile\\/bind\",\"api.php?app=user&do=profile&pg=bind\"],\"user:profile:custom\":[\"\\/user\\/profile\\/custom\",\"api.php?app=user&do=profile&pg=custom\"],\"user:register\":[\"\\/user\\/register\",\"api.php?app=user&do=register\"],\"user:logout\":[\"\\/user\\/logout\",\"api.php?app=user&do=logout\"],\"user:login\":[\"\\/user\\/login\",\"api.php?app=user&do=login\"],\"user:login:qq\":[\"\\/user\\/login\\/qq\",\"api.php?app=user&do=login&sign=qq\"],\"user:login:wb\":[\"\\/user\\/login\\/wb\",\"api.php?app=user&do=login&sign=wb\"],\"user:login:wx\":[\"\\/user\\/login\\/wx\",\"api.php?app=user&do=login&sign=wx\"],\"user:findpwd\":[\"\\/user\\/findpwd\",\"api.php?app=user&do=findpwd\"],\"uid:home\":[\"\\/{uid}\\/\",\"api.php?app=user&do=home&uid={uid}\"],\"uid:comment\":[\"\\/{uid}\\/comment\\/\",\"api.php?app=user&do=comment&uid={uid}\"],\"uid:share\":[\"\\/{uid}\\/share\\/\",\"api.php?app=user&do=share&uid={uid}\"],\"uid:favorite\":[\"\\/{uid}\\/favorite\\/\",\"api.php?app=user&do=favorite&uid={uid}\"],\"uid:fans\":[\"\\/{uid}\\/fans\\/\",\"api.php?app=user&do=fans&uid={uid}\"],\"uid:follower\":[\"\\/{uid}\\/follower\\/\",\"api.php?app=user&do=follower&uid={uid}\"],\"uid:cid\":[\"\\/{uid}\\/{cid}\\/\",\"api.php?app=user&do=home&uid={uid}&cid={cid}\"],\"uid:favorite:id\":[\"\\/{uid}\\/favorite\\/{id}\\/\",\"api.php?app=user&do=favorite&uid={uid}&id={id}\"],\"api\":[\"\\/api\",\"api.php\"],\"comment\":[\"\\/comment\",\"api.php?app=comment\"],\"search\":[\"\\/search\",\"api.php?app=search\"],\"public:seccode\":[\"\\/public\\/seccode\",\"api.php?app=public&do=seccode\"],\"public:agreement\":[\"\\/public\\/agreement\",\"api.php?app=public&do=agreement\"],\"favorite\":[\"\\/favorite\",\"api.php?app=favorite\"],\"favorite:id\":[\"\\/favorite\\/{id}\\/\",\"api.php?app=favorite&id={id}\"],\"forms\":[\"\\/forms\",\"api.php?app=forms\"],\"forms:save\":[\"\\/forms\\/save\",\"api.php?app=forms&do=save\"],\"forms:id\":[\"\\/forms\\/{id}\\/\",\"api.php?app=forms&id={id}\"]}}'),
    (0,'cache','{\"engine\":\"file\",\"host\":\"\",\"time\":\"300\",\"compress\":\"1\",\"page_total\":\"300\"}'),
    (0,'FS','{\"url\":\"https:\\/\\/www.icmsdev.com\\/res\\/\",\"dir\":\"res\",\"dir_format\":\"Y\\/m-d\\/H\",\"allow_ext\":\"gif,jpg,rar,swf,jpeg,png,zip\"}'),
    (0,'thumb','{\"size\":\"\"}'),
    (0,'watermark','{\"enable\":\"1\",\"width\":\"140\",\"height\":\"140\",\"allow_ext\":\"jpg,jpeg,png\",\"pos\":\"9\",\"x\":\"10\",\"y\":\"10\",\"img\":\"watermark.png\",\"text\":\"iCMS\",\"font\":\"\",\"fontsize\":\"24\",\"color\":\"#000000\",\"transparent\":\"80\"}'),
    (0,'publish','[]'),
    (0,'debug','{\"php\":\"1\",\"php_trace\":\"0\",\"tpl\":\"1\",\"tpl_trace\":\"0\",\"db\":\"0\",\"db_trace\":\"0\",\"db_explain\":\"0\"}'),
    (0,'time','{\"zone\":\"Asia\\/Shanghai\",\"cvtime\":\"0\",\"dateformat\":\"Y-m-d H:i:s\"}'),
    (0,'apps','[]'),
    (0,'other','{\"sidebar_enable\":\"1\",\"sidebar\":\"1\"}'),
    (0,'system','{\"patch\":\"1\"}'),
    (0,'sphinx','{\"host\":\"127.0.0.1:9312\",\"index\":\"iCMS_article iCMS_article_delta\"}'),
    (0,'open','[]'),
    (0,'template','{\"index\":{\"mode\":\"0\",\"rewrite\":\"0\",\"tpl\":\"{iTPL}\\/index.htm\",\"name\":\"index\"},\"desktop\":{\"tpl\":\"www\\/desktop\",\"index\":\"{iTPL}\\/index.htm\",\"domain\":\"https:\\/\\/www.icmsdev.com\"},\"mobile\":{\"agent\":\"WAP,Smartphone,Mobile,UCWEB,Opera Mini,Windows CE,Symbian,SAMSUNG,iPhone,Android,BlackBerry,HTC,Mini,LG,SonyEricsson,J2ME,MOT\",\"domain\":\"https:\\/\\/www.icmsdev.com\",\"tpl\":\"www\\/mobile\",\"index\":\"{iTPL}\\/index.htm\"}}'),
    (0,'api','{\"baidu\":{\"sitemap\":{\"site\":\"sdf\",\"access_token\":\"sdf\",\"sync\":\"0\"}}}'),
    (0,'mail','{\"host\":\"\",\"secure\":\"\",\"port\":\"25\",\"username\":\"\",\"password\":\"\",\"setfrom\":\"\",\"replyto\":\"\"}'),
    (1,'article','{\"pic_center\":\"1\",\"pic_next\":\"0\",\"pageno_incr\":\"\",\"markdown\":\"0\",\"autoformat\":\"0\",\"catch_remote\":\"0\",\"remote\":\"0\",\"autopic\":\"1\",\"autodesc\":\"1\",\"descLen\":\"100\",\"autoPage\":\"0\",\"AutoPageLen\":\"\",\"repeatitle\":\"0\",\"showpic\":\"0\",\"filter\":\"0\",\"clink\":\"-\"}'),
    (2,'category','{\"domain\":null}'),
    (3,'tag','{\"url\":\"https:\\/\\/www.icmsdev.com\",\"rule\":\"{PHP}\",\"dir\":\"\\/\",\"tpl\":\"{iTPL}\\/tag.htm\",\"tkey\":\"-\"}'),
    (5,'comment','{\"enable\":\"1\",\"examine\":\"0\",\"seccode\":\"1\",\"plugin\":{\"changyan\":{\"enable\":\"0\",\"appid\":\"\",\"appkey\":\"\"}}}'),
    (9,'user','{\"register\":{\"enable\":\"1\",\"seccode\":\"1\",\"interval\":\"86400\"},\"login\":{\"enable\":\"1\",\"seccode\":\"1\",\"interval\":\"3600\"},\"post\":{\"seccode\":\"1\",\"interval\":\"10\"},\"agreement\":\"\",\"coverpic\":\"\\/ui\\/coverpic.jpg\",\"open\":{\"WX\":{\"appid\":\"\",\"appkey\":\"\",\"redirect\":\"\"},\"QQ\":{\"appid\":\"\",\"appkey\":\"\",\"redirect\":\"\"},\"WB\":{\"appid\":\"\",\"appkey\":\"\",\"redirect\":\"\"},\"TB\":{\"appid\":\"\",\"appkey\":\"\",\"redirect\":\"\"}}}'),
    (12,'cloud','[]'),
    (17,'hooks','{\"article\":{\"body\":[[\"keywordsApp\",\"HOOK_run\"],[\"plugin_download\",\"HOOK\"],[\"plugin_markdown\",\"HOOK\"]]}}'),
    (27,'weixin','{\"menu\":[{\"type\":\"click\",\"name\":\"\",\"key\":\"\"},{\"type\":\"click\",\"name\":\"\",\"key\":\"\"},{\"type\":\"click\",\"name\":\"\",\"key\":\"\"}],\"appid\":\"\",\"appsecret\":\"\",\"token\":\"\",\"name\":\"\",\"account\":\"\",\"qrcode\":\"\",\"subscribe\":\"\",\"unsubscribe\":\"\",\"AESKey\":\"\"}'),
    (28,'keywords','{\"limit\":\"-1\"}'),
    (999999,'filter','{\"disable\":[\"\"],\"filter\":[\"\"]}');"
    );
}
if(iDB::check_table('config') && iDB::check_table('config_v6')){
    flush_print("开始转换config数据");
    $variable6 = iDB::all("SELECT * FROM `#iCMS@__config_v6`");
    $config6 = array();
    foreach ($variable6 as $key => $v) {
        if($v['name']=='word.filter' && $v['value']=='a:1:{i:0;a:1:{i:0;s:0:"";}}'){
            $config6['word.filter'] = array();
            continue;
        }
        if($v['name']=='word.disable' && $v['value']=='a:1:{i:0;s:0:"";}'){
            $config6['word.disable'] = array();
            continue;
        }

        $config6[$v['name']] = unserialize($v['value']);
    }

    $variable = iDB::all("SELECT * FROM `#iCMS@__config`");
    $config = array();
    foreach ($variable as $key => $v) {
        $config[$v['name']] = json_decode($v['value'],true);
    }
    $config['site'] = $config6['site'];

    $config['router']['url']    = $config6['router']['URL'];
    $config['router']['404']    = $config6['router']['404'];
    $config['router']['public'] = $config6['router']['public_url'];
    $config['router']['user']   = $config6['router']['user_url'];
    $config['router']['dir']     = $config6['router']['html_dir'];
    $config['router']['ext']     = $config6['router']['html_ext'];
    $config['router']['speed']   = $config6['router']['speed'];
    $config['router']['rewrite'] = $config6['router']['rewrite'];

    $config['tag']['url']  = $config6['router']['tag_url'];
    $config['tag']['rule'] = $config6['router']['tag_rule'];
    $config['tag']['dir']  = $config6['router']['tag_dir'];
    $config['tag']['tkey']  = $config6['other']['py_split'];

    $config['cache']     = array_merge($config['cache'],$config6['cache']);
    $config['FS']        = array_merge($config['FS'],$config6['FS']);
    $config['thumb']     = $config6['thumb'];
    $config['watermark'] = array_merge($config['watermark'],$config6['watermark']);

    $config6['user']['open'] = $config6['open'];
    $config['user'] = $config6['user'];

    $config['article'] = array_merge($config['article'],$config6['publish'],$config6['article']);
    $config['comment'] = $config6['comment'];

    $config6['debug']['db'] = $config6['debug']['sql'];
    unset($config6['debug']['sql']);
    $config['debug'] = array_merge($config['debug'],$config6['debug']);
    $config['time'] = $config6['time'];
    $config['other'] = $config6['other'];
    $config['system'] = $config6['system'];
    $config['sphinx'] = $config6['sphinx'];
    $config['filter']['disable'] = $config6['word.disable'];
    $config['filter']['filter'] = $config6['word.filter'];

    $config['template']["index"] = Array (
        'mode'    => $config6['template']["index_mode"],
        'rewrite' => $config6['template']["index_rewrite"],
        'tpl'     => $config6['template']["index"],
        'name'    => $config6['template']["index_name"],
    );
    unset(
        $config6['template']["index_mode"],
        $config6['template']["index_rewrite"],
        $config6['template']["index"],
        $config6['template']["index_name"]
    );

    $config['template']['desktop'] = array_merge($config['template']['desktop'],$config6['template']['desktop']);
    $config['template']['desktop']["domain"] = $config6['router']['URL'];
    $config['template']['mobile'] = array_merge($config['template']['mobile'],$config6['template']['mobile']);
    $config['template']['device'] = $config6['template']['device'];

    $config['api'] = $config6['api'];
    $config['mail'] = $config6['mail'];
    $config['weixin'] = $config6['weixin'];
    $config['keywords']['limit'] = $config6['other']['keyword_limit'];
    foreach ($config as $key => $value) {
        iDB::update("config",array('value'=>cnjson_decode($value)),array('name'=>$key));
    }
    flush_print("config数据转换完成.....√");
    flush_print("更新系统缓存");
    apps::cache();
    menu::cache();
    category::cache();
    $categoryAdmincp = new article_categoryAdmincp();
    $categoryAdmincp->do_cache(false);
    iView::clear_tpl();
    foreach (array('configAdmincp','propAdmincp','filterAdmincp','keywordsAdmincp') as $key => $acp) {
        iPHP::callback(array($acp,'cache'));
    }
}
redirect('upgrade.end.php');
