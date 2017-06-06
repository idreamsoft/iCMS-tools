-- DROP TABLE IF EXISTS `icms_apps`;

/* Create table in target */
CREATE TABLE IF NOT EXISTS `icms_apps`(
  `id` int(10) unsigned NOT NULL  auto_increment COMMENT '应用ID appid' ,
  `app` varchar(100) COLLATE utf8_general_ci NOT NULL  DEFAULT '' COMMENT '应用标识' ,
  `name` varchar(100) COLLATE utf8_general_ci NOT NULL  DEFAULT '' COMMENT '应用名' ,
  `title` varchar(100) COLLATE utf8_general_ci NOT NULL  DEFAULT '' COMMENT '应用标题' ,
  `apptype` tinyint(1) unsigned NOT NULL  DEFAULT 0 COMMENT '类型 0官方 1本地 2自定义' ,
  `type` tinyint(1) unsigned NOT NULL  DEFAULT 0 COMMENT '应用类型' ,
  `table` text COLLATE utf8_general_ci NOT NULL  COMMENT '应用表' ,
  `config` text COLLATE utf8_general_ci NOT NULL  COMMENT '应用配置' ,
  `fields` text COLLATE utf8_general_ci NOT NULL  COMMENT '应用自定义字段' ,
  `menu` text COLLATE utf8_general_ci NOT NULL  COMMENT '应用菜单' ,
  `addtime` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT '添加时间' ,
  `status` tinyint(1) NOT NULL  DEFAULT 0 COMMENT '应用状态' ,
  PRIMARY KEY (`id`) ,
  KEY `idx_name`(`app`)
) ENGINE=MyISAM DEFAULT CHARSET='utf8' COLLATE='utf8_general_ci';

LOCK TABLES `icms_apps` WRITE;

insert  into `icms_apps`(`id`,`app`,`name`,`title`,`apptype`,`type`,`table`,`config`,`fields`,`menu`,`addtime`,`status`) values
    (1,'article','文章系统','文章',0,1,'{\"article\":[\"article\",\"id\",\"\",\"文章\"],\"article_data\":[\"article_data\",\"id\",\"aid\",\"正文\"]}','{\"iFormer\":\"1\",\"info\":\"文章资讯系统\",\"template\":[\"iCMS:article:list\",\"iCMS:article:search\",\"iCMS:article:data\",\"iCMS:article:prev\",\"iCMS:article:next\",\"iCMS:article:array\",\"$article\"],\"version\":\"v7.0\",\"menu\":\"main\",\"iurl\":{\"rule\":\"2\",\"primary\":\"id\",\"page\":\"p\"}}','','[{\"id\":\"article\",\"sort\":\"2\",\"caption\":\"文章\",\"icon\":\"pencil-square-o\",\"children\":[{\"caption\":\"文章系统配置\",\"href\":\"article&do=config\",\"icon\":\"cog\"},{\"caption\":\"-\"},{\"caption\":\"栏目管理\",\"href\":\"article_category\",\"icon\":\"list-alt\"},{\"caption\":\"添加栏目\",\"href\":\"article_category&do=add\",\"icon\":\"edit\"},{\"caption\":\"-\"},{\"caption\":\"添加文章\",\"href\":\"article&do=add\",\"icon\":\"edit\"},{\"caption\":\"文章管理\",\"href\":\"article&do=manage\",\"icon\":\"list-alt\"},{\"caption\":\"草稿箱\",\"href\":\"article&do=inbox\",\"icon\":\"inbox\"},{\"caption\":\"回收站\",\"href\":\"article&do=trash\",\"icon\":\"trash-o\"},{\"caption\":\"-\"},{\"caption\":\"用户文章管理\",\"href\":\"article&do=user\",\"icon\":\"check-circle\"},{\"caption\":\"审核用户文章\",\"href\":\"article&do=examine\",\"icon\":\"minus-circle\"},{\"caption\":\"淘汰的文章\",\"href\":\"article&do=off\",\"icon\":\"times-circle\"},{\"caption\":\"-\"},{\"caption\":\"文章评论管理\",\"href\":\"comment&appname=article&appid=1\",\"icon\":\"comments\"}]}]',1493339400,1),
    (2,'category','分类系统','分类',0,1,'{\"category\":[\"category\",\"cid\",\"\",\"分类\"],\"category_map\":[\"category_map\",\"id\",\"node\",\"分类映射\"]}','{\"iFormer\":\"1\",\"info\":\"通用无限级分类系统\",\"template\":[\"iCMS:category:array\",\"iCMS:category:list\",\"$category\"],\"version\":\"v7.0\",\"menu\":\"main\",\"iurl\":{\"rule\":\"1\",\"primary\":\"cid\"}}','','null',1493339391,1),
    (3,'tag','标签系统','标签',0,1,'{\"tag\":[\"tag\",\"id\",\"\",\"标签\"],\"tag_map\":[\"tag_map\",\"id\",\"node\",\"标签映射\"]}','{\"iFormer\":\"1\",\"info\":\"自由多样性标签系统\",\"template\":[\"iCMS:tag:list\",\"iCMS:tag:array\",\"$tag\"],\"version\":\"v7.0\",\"menu\":\"main\",\"iurl\":{\"rule\":\"3\",\"primary\":\"id\"}}','','[{\"id\":\"assist\",\"children\":[{\"id\":\"tag\",\"caption\":\"标签\",\"icon\":\"tags\",\"children\":[{\"caption\":\"标签配置\",\"href\":\"tag&do=config\",\"icon\":\"cog\"},{\"caption\":\"-\"},{\"caption\":\"标签管理\",\"href\":\"tag\",\"icon\":\"tag\"},{\"caption\":\"添加标签\",\"href\":\"tag&do=add\",\"icon\":\"edit\"},{\"caption\":\"-\"},{\"caption\":\"分类管理\",\"href\":\"tag_category\",\"icon\":\"sitemap\"},{\"caption\":\"添加分类\",\"href\":\"tag_category&do=add\",\"icon\":\"edit\"}]}]}]',1493339382,1),
    (5,'comment','评论系统','评论',1,1,'{\"comment\":[\"comment\",\"id\",\"\",\"评论\"]}','{\"iFormer\":\"1\",\"info\":\"通用评论系统\",\"template\":[\"iCMS:comment:array\",\"iCMS:comment:list\",\"iCMS:comment:form\"],\"version\":\"v7.0\",\"menu\":\"main\"}','','[{\"id\":\"assist\",\"children\":[{\"caption\":\"-\"},{\"id\":\"comment\",\"caption\":\"评论\",\"icon\":\"comments\",\"href\":\"comment\",\"children\":[{\"caption\":\"评论系统配置\",\"href\":\"comment&do=config\",\"icon\":\"cog\"},{\"caption\":\"-\"},{\"caption\":\"评论管理\",\"href\":\"comment\",\"icon\":\"comments\"}]}]}\r\n]',1488703642,1),
    (6,'prop','属性系统','属性',0,1,'{\"prop\":[\"prop\",\"pid\",\"\",\"属性\"],\"prop_map\":[\"prop_map\",\"id\",\"node\",\"属性映射\"]}','{\"info\":\"通用属性系统\",\"template\":[\"iCMS:prop:array\"],\"version\":\"v7.0\",\"menu\":\"main\"}','','[{\"id\":\"assist\",\"children\":[{\"caption\":\"-\"},{\"id\":\"prop\",\"caption\":\"属性\",\"icon\":\"puzzle-piece\",\"children\":[{\"caption\":\"属性管理\",\"href\":\"prop\",\"icon\":\"puzzle-piece\"},{\"caption\":\"添加属性\",\"href\":\"prop&do=add\",\"icon\":\"edit\"}]}]}\r\n]',1489151390,1),
    (7,'message','私信系统','私信',0,1,'{\"message\":[\"message\",\"id\",\"\",\"私信\"]}','{\"info\":\"用户私信系统\",\"version\":\"v7.0\",\"template\":[\"iCMS:message:list\"]}','','',1488706289,1),
    (8,'favorite','收藏系统','收藏',0,1,'{\"favorite\":[\"favorite\",\"id\",\"\",\"收藏信息\"],\"favorite_data\":[\"favorite_data\",\"fid\",\"\",\"收藏数据\"],\"favorite_follow\":[\"favorite_follow\",\"id\",\"fid\",\"收藏关注\"]}','{\"info\":\"用户收藏系统\",\"template\":[\"iCMS:favorite:list\",\"iCMS:favorite:data\"],\"version\":\"v7.0\",\"menu\":\"main\"}','','',1488703818,1),
    (9,'user','用户系统','用户',0,1,'{\"user\":[\"user\",\"uid\",\"\",\"用户\"],\"user_category\":[\"user_category\",\"cid\",\"uid\",\"用户分类\"],\"user_data\":[\"user_data\",\"uid\",\"uid\",\"用户数据\"],\"user_follow\":[\"user_follow\",\"uid\",\"uid\",\"用户关注\"],\"user_openid\":[\"user_openid\",\"uid\",\"uid\",\"第三方\"],\"user_report\":[\"user_report\",\"id\",\"userid\",\"举报\"]}','{\"iFormer\":\"1\",\"info\":\"用户系统\",\"template\":[\"iCMS:user:data\",\"iCMS:user:list\",\"iCMS:user:category\",\"iCMS:user:follow\",\"iCMS:user:stat\",\"iCMS:user:inbox\"],\"version\":\"v7.0\",\"menu\":\"main\"}','','[{\"id\":\"members\",\"children\":[{\"caption\":\"会员设置\",\"href\":\"user&do=config\",\"icon\":\"cog\"},{\"caption\":\"-\"},{\"caption\":\"会员管理\",\"href\":\"user\",\"icon\":\"list-alt\"},{\"caption\":\"添加会员\",\"href\":\"user&do=add\",\"icon\":\"user\"}]}\r\n]',1488703838,1),
    (10,'admincp','后台程序','后台',0,0,'{\"access_log\":[\"access_log\",\"id\",\"\",\"访问记录\"]}','{\"info\":\"基础管理系统\",\"version\":\"v7.0\",\"menu\":\"main\"}','','[{\"id\":\"admincp\",\"sort\":\"-9999999\",\"caption\":\"管理\",\"icon\":\"home\",\"href\":\"iPHP_SELF\"},{\"caption\":\"-\",\"sort\":\"9999995\"},{\"id\":\"members\",\"sort\":\"9999996\",\"caption\":\"用户\",\"icon\":\"user\",\"children\":[]},{\"id\":\"assist\",\"sort\":\"9999997\",\"caption\":\"辅助\",\"icon\":\"gavel\",\"children\":[]},{\"id\":\"tools\",\"sort\":\"9999998\",\"caption\":\"工具\",\"icon\":\"gavel\",\"children\":[]},{\"id\":\"system\",\"sort\":\"9999999\",\"caption\":\"系统\",\"icon\":\"cog\",\"children\":[{\"caption\":\"-\"},{\"caption\":\"检查更新\",\"href\":\"patch&do=check&force=1\",\"target\":\"iPHP_FRAME\",\"icon\":\"repeat\"},{\"caption\":\"-\"},{\"caption\":\"官方网站\",\"href\":\"http:\\/\\/www.icmsdev.com\",\"target\":\"_blank\",\"icon\":\"star\"},{\"caption\":\"帮助文档\",\"href\":\"http:\\/\\/www.icmsdev.com\\/docs\\/\",\"target\":\"_blank\",\"icon\":\"question-circle\"}]}]',1493342705,1),
    (11,'config','系统配置','配置',0,0,'{\"config\":[\"config\",\"appid\",\"\",\"系统配置\"]}','{\"info\":\"系统配置\",\"version\":\"v7.0\",\"menu\":\"main\"}','','[{\"id\":\"system\",\"children\":[{\"id\":\"config\",\"caption\":\"系统设置\",\"href\":\"config\",\"icon\":\"cog\",\"sort\":\"-999\",\"children\":[{\"caption\":\"网站设置\",\"href\":\"config&tab=base\",\"icon\":\"cog\"},{\"caption\":\"模板设置\",\"href\":\"config&tab=tpl\",\"icon\":\"cog\"},{\"caption\":\"URL设置\",\"href\":\"config&tab=url\",\"icon\":\"cog\"},{\"caption\":\"缓存设置\",\"href\":\"config&tab=cache\",\"icon\":\"cog\"},{\"caption\":\"附件设置\",\"href\":\"config&tab=file\",\"icon\":\"cog\"},{\"caption\":\"缩略图设置\",\"href\":\"config&tab=thumb\",\"icon\":\"cog\"},{\"caption\":\"水印设置\",\"href\":\"config&tab=watermark\",\"icon\":\"cog\"},{\"caption\":\"其它设置\",\"href\":\"config&tab=other\",\"icon\":\"cog\"},{\"caption\":\"更新设置\",\"href\":\"config&tab=patch\",\"icon\":\"cog\"},{\"caption\":\"高级设置\",\"href\":\"config&tab=grade\",\"icon\":\"cog\"},{\"caption\":\"邮件设置\",\"href\":\"config&tab=mail\",\"icon\":\"cog\"}]},{\"caption\":\"-\",\"sort\":\"-998\"}]}]',1493342808,1),
    (12,'files','文件系统','文件',0,0,'{\"files\":[\"files\",\"id\",\"\",\"文件\"],\"files_map\":[\"files_map\",\"fileid\",\"fileid\",\"文件映射\"]}','{\"info\":\"文件管理系统\",\"version\":\"v7.0\",\"menu\":\"main\"}','','[{\"id\":\"tools\",\"children\":[{\"id\":\"files\",\"sort\":\"-998\",\"caption\":\"文件管理\",\"icon\":\"folder\",\"children\":[{\"caption\":\"云存储配置\",\"href\":\"files&do=cloud_config\",\"icon\":\"cog\"},{\"caption\":\"-\"},{\"caption\":\"文件管理\",\"href\":\"files\",\"icon\":\"folder\"},{\"caption\":\"上传文件\",\"href\":\"files&do=multi&from=modal\",\"icon\":\"upload\",\"data-toggle\":\"modal\",\"data-meta\":{\"width\":\"85%\",\"height\":\"640px\"}}]},{\"caption\":\"-\",\"sort\":\"-997\"}]}]',1492653210,1),
    (13,'menu','后台菜单','菜单',0,0,'0','{\"info\":\"后台菜单管理\",\"version\":\"v7.0\",\"menu\":\"main\"}','','',1488704378,1),
    (14,'group','角色系统','角色',0,0,'{\"group\":[\"group\",\"gid\",\"\",\"角色\"]}','{\"info\":\"角色权限系统\",\"version\":\"v7.0\",\"menu\":\"main\"}','','',1488704473,1),
    (15,'members','管理员系统','管理员',0,0,'{\"members\":[\"members\",\"uid\",\"\",\"管理员\"]}','{\"info\":\"管理员系统\",\"version\":\"v7.0\",\"menu\":\"main\"}','','[{\"id\":\"members\",\"children\":[{\"caption\":\"-\"},{\"caption\":\"管理员列表\",\"href\":\"members\",\"icon\":\"list-alt\"},{\"caption\":\"添加管理员\",\"href\":\"members&do=add\",\"icon\":\"user\"},{\"caption\":\"-\"},{\"caption\":\"角色管理\",\"href\":\"group\",\"icon\":\"list-alt\"},{\"caption\":\"添加角色\",\"href\":\"group&do=add\",\"icon\":\"group\"}]}\r\n]',1488704428,1),
    (16,'editor','后台编辑器','编辑器',0,0,'0','{\"info\":\"后台编辑器\",\"version\":\"v7.0\",\"menu\":\"main\"}','','',1488704375,1),
    (17,'apps','应用管理','应用',0,0,'{\"apps\":[\"apps\",\"id\",\"\",\"应用\"]}','{\"info\":\"应用管理\",\"template\":[\"iCMS:apps:data\"],\"version\":\"v7.0\",\"menu\":\"main\"}','','[{\"id\":\"system\",\"children\":[{\"id\":\"apps\",\"caption\":\"应用管理\",\"icon\":\"code\",\"sort\":\"0\",\"children\":[{\"caption\":\"应用管理\",\"href\":\"apps\",\"icon\":\"code\"},{\"caption\":\"添加应用\",\"href\":\"apps&do=add\",\"icon\":\"pencil-square-o\"},{\"caption\":\"-\"},{\"caption\":\"钩子管理\",\"href\":\"apps&do=hooks\",\"icon\":\"plug\"},{\"caption\":\"-\"},{\"caption\":\"应用市场\",\"href\":\"apps&do=store\",\"icon\":\"bank\"},{\"caption\":\"-\"},{\"caption\":\"模板市场\",\"href\":\"apps&do=template\",\"icon\":\"bank\"}]}]}]',1492652199,1),
    (18,'former','表单生成器','表单',0,0,'0','{\"info\":\"表单生成器\",\"version\":\"v7.0\",\"menu\":\"main\"}','','',1490201571,1),
    (19,'patch','升级程序','升级',0,0,'0','{\"info\":\"用于升级系统\",\"version\":\"v7.0\",\"menu\":\"main\"}','','',1488704373,1),
    (20,'content','内容管理','内容',0,1,'0','{\"info\":\"自定义应用内容管理\\/接口\",\"template\":[\"iCMS:content:list\",\"iCMS:content:prev\",\"iCMS:content:next\",\"$content\"],\"version\":\"v7.0\"}','','null',1493339370,1),
    (21,'index','首页程序','首页',0,1,'0','{\"info\":\"首页程序\",\"version\":\"v7.0\",\"menu\":\"main\",\"iurl\":{\"rule\":\"0\",\"primary\":\"\"}}','','',1488771698,1),
    (22,'public','公共程序','公共',0,1,'0','{\"info\":\"公共通用标签\",\"template\":[\"iCMS:public:ui\",\"iCMS:public:seccode\",\"iCMS:public:crontab\",\"iCMS:public:qrcode\"],\"version\":\"v7.0\",\"menu\":\"main\"}','','',1488703923,1),
    (23,'cache','缓存更新','缓存',0,1,'0','{\"info\":\"用于更新应用程序缓存\",\"version\":\"v7.0\",\"menu\":\"main\"}','','[{\"id\":\"tools\",\"children\":[{\"caption\":\"-\"},{\"id\":\"cache\",\"caption\":\"清理缓存\",\"icon\":\"refresh\",\"children\":[{\"caption\":\"更新所有缓存\",\"href\":\"cache&do=all\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"-\"},{\"caption\":\"更新系统设置\",\"href\":\"cache&acp=configAdmincp\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"更新菜单缓存\",\"href\":\"cache&do=menu\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"清除模板缓存\",\"href\":\"cache&do=tpl\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"-\"},{\"caption\":\"更新所有分类缓存\",\"href\":\"cache&do=category\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"更新文章栏目缓存\",\"href\":\"cache&do=article_category\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"更新标签分类缓存\",\"href\":\"cache&do=tag_category\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"-\"},{\"caption\":\"更新属性缓存\",\"href\":\"cache&acp=propAdmincp\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"更新内链缓存\",\"href\":\"cache&acp=keywordsAdmincp\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"更新过滤缓存\",\"href\":\"cache&acp=filterAdmincp\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"-\"},{\"caption\":\"重计栏目文章数\",\"href\":\"cache&do=article_count\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"}]}]}\r\n]',1489336794,1),
    (24,'filter','过滤系统','过滤',0,1,'0','{\"info\":\"关键词过滤/违禁词系统\",\"version\":\"v7.0\",\"menu\":\"main\"}','','[{\"id\":\"assist\",\"children\":[{\"caption\":\"-\"},{\"id\":\"filter\",\"caption\":\"关键词过滤\",\"href\":\"filter\",\"icon\":\"filter\"}]}\r\n]',1488704119,1),
    (25,'plugin','插件管理','插件',0,1,'0','{\"info\":\"插件程序\",\"version\":\"v7.0\"}','','',1488704192,1),
    (26,'forms','自定义表单','表单',1,1,'{\"forms\":[\"forms\",\"id\",\"\",\"表单\"]}','{\"info\":\"自定义表单管理\\/接口\",\"template\":[\"iCMS:forms:create\",\"iCMS:forms:list\",\"$forms\"],\"version\":\"v7.0\"}','','[{\"id\":\"assist\",\"children\":[{\"caption\":\"-\"},{\"id\":\"forms\",\"caption\":\"自定义表单\",\"icon\":\"building\",\"children\":[{\"caption\":\"表单管理\",\"href\":\"forms\",\"icon\":\"building\"},{\"caption\":\"创建表单\",\"href\":\"forms&do=create\",\"icon\":\"pencil-square-o\"},{\"caption\":\"-\"},{\"caption\":\"表单数据\",\"href\":\"forms&do=data\",\"icon\":\"dashboard\"},{\"caption\":\"添加表单数据\",\"href\":\"forms&do=submit\",\"icon\":\"edit\"}]}]}]',1493339346,1),
    (27,'weixin','微信系统','微信',1,1,'{\"weixin_api_log\":[\"weixin_api_log\",\"id\",\"\",\"记录\"],\"weixin_event\":[\"weixin_event\",\"id\",\"\",\"事件\"]}','{\"info\":\"微信公众平台接口程序\",\"version\":\"v7.0\",\"menu\":\"main\"}','','[{\"id\":\"weixin\",\"sort\":\"3\",\"caption\":\"微信\",\"icon\":\"weixin\",\"children\":[{\"caption\":\"配置接口\",\"href\":\"weixin&do=config\",\"icon\":\"cog\"},{\"caption\":\"自定义菜单\",\"href\":\"weixin&do=menu\",\"icon\":\"bars\"},{\"caption\":\"-\"},{\"caption\":\"事件管理\",\"href\":\"weixin&do=event\",\"icon\":\"cubes\"},{\"caption\":\"添加事件\",\"href\":\"weixin&do=event_add\",\"icon\":\"plus\"}]}\r\n]',1488703858,1),
    (28,'keywords','内链系统','内链',1,1,'{\"keywords\":[\"keywords\",\"id\",\"\",\"内链\"]}','{\"info\":\"内链系统\",\"version\":\"v7.0\",\"menu\":\"main\"}','','[{\"id\":\"assist\",\"children\":[{\"caption\":\"-\"},{\"id\":\"keywords\",\"caption\":\"内链\",\"icon\":\"paperclip\",\"children\":[{\"caption\":\"内链设置\",\"href\":\"keywords&do=config\",\"icon\":\"cog\"},{\"caption\":\"-\"},{\"caption\":\"内链管理\",\"href\":\"keywords\",\"icon\":\"paperclip\"},{\"caption\":\"添加内链\",\"href\":\"keywords&do=add\",\"icon\":\"edit\"}]}]}\r\n]',1488704241,1),
    (29,'links','友情链接','链接',1,1,'{\"links\":[\"links\",\"id\",\"\",\"友情链接\"]}','{\"iFormer\":\"1\",\"info\":\"友情链接程序\",\"template\":[\"iCMS:links:list\"],\"version\":\"v7.0\",\"menu\":\"main\"}','','[{\"id\":\"assist\",\"children\":[{\"caption\":\"-\"},{\"id\":\"links\",\"caption\":\"友情链接\",\"icon\":\"link\",\"children\":[{\"caption\":\"链接管理\",\"href\":\"links\",\"icon\":\"link\"},{\"caption\":\"添加链接\",\"href\":\"links&do=add\",\"icon\":\"edit\"}]}]}]',1489932498,1),
    (31,'search','搜索系统','搜索',1,1,'{\"search_log\":[\"search_log\",\"id\",\"\",\"搜索记录\"]}','{\"info\":\"文章搜索系统\",\"template\":[\"iCMS:search:list\",\"iCMS:search:url\",\"$search\"],\"version\":\"v7.0\",\"menu\":\"main\"}','','[{\"id\":\"assist\",\"children\":[{\"caption\":\"-\"},{\"caption\":\"搜索统计\",\"href\":\"search\",\"icon\":\"search\"}]}]',1493339357,1),
    (32,'database','数据库管理','数据库',1,1,'0','{\"info\":\"后台简易数据库管理\",\"version\":\"v7.0\",\"menu\":\"main\"}','','[{\"id\":\"tools\",\"children\":[{\"caption\":\"-\"},{\"id\":\"database\",\"caption\":\"数据库管理\",\"icon\":\"database\",\"children\":[{\"caption\":\"数据库备份\",\"href\":\"database&do=backup\",\"icon\":\"cloud-download\"},{\"caption\":\"备份管理\",\"href\":\"database&do=recover\",\"icon\":\"upload\"},{\"caption\":\"-\"},{\"caption\":\"修复优化\",\"href\":\"database&do=repair\",\"icon\":\"gavel\"},{\"caption\":\"性能优化\",\"href\":\"database&do=sharding\",\"icon\":\"puzzle-piece\"},{\"caption\":\"-\"},{\"caption\":\"数据替换\",\"href\":\"database&do=replace\",\"icon\":\"retweet\"}]}]}\r\n]',1488703931,1),
    (33,'html','静态系统','静态',1,1,'0','{\"info\":\"静态文件生成程序\",\"version\":\"v7.0\",\"menu\":\"main\"}','','[{\"id\":\"tools\",\"children\":[{\"id\":\"html\",\"sort\":\"-992\",\"caption\":\"生成静态\",\"icon\":\"file\",\"children\":[{\"caption\":\"首页静态化\",\"href\":\"html&do=index\",\"icon\":\"refresh\"},{\"caption\":\"-\"},{\"caption\":\"栏目静态化\",\"href\":\"html&do=category\",\"icon\":\"refresh\"},{\"caption\":\"文章静态化\",\"href\":\"html&do=article\",\"icon\":\"refresh\"},{\"caption\":\"-\"},{\"caption\":\"全站生成静态\",\"href\":\"html&do=all\",\"icon\":\"refresh\"},{\"caption\":\"-\"},{\"caption\":\"静态设置\",\"href\":\"config&tab=url\",\"icon\":\"cog\"}]}]}\r\n]',1488703939,1),
    (34,'spider','采集系统','采集',1,1,'{\"spider_post\":[\"spider_post\",\"id\",\"\",\"发布\"],\"spider_project\":[\"spider_project\",\"id\",\"\",\"方案\"],\"spider_rule\":[\"spider_rule\",\"id\",\"\",\"规则\"],\"spider_url\":[\"spider_url\",\"id\",\"\",\"采集结果\"],\"spider_error\":[\"spider_error\",\"id\",\"\",\"错误记录\"]}','{\"info\":\"采集系统\",\"version\":\"v7.0\",\"menu\":\"main\"}','','[{\"id\":\"tools\",\"children\":[{\"id\":\"spider\",\"sort\":\"-994\",\"caption\":\"采集管理\",\"href\":\"spider\",\"icon\":\"magnet\",\"children\":[{\"caption\":\"采集列表\",\"href\":\"spider&do=manage\",\"icon\":\"list-alt\"},{\"caption\":\"未发文章\",\"href\":\"spider&do=inbox\",\"icon\":\"inbox\"},{\"caption\":\"-\"},{\"caption\":\"采集方案\",\"href\":\"spider&do=project\",\"icon\":\"magnet\"},{\"caption\":\"添加方案\",\"href\":\"spider&do=addproject\",\"icon\":\"edit\"},{\"caption\":\"-\"},{\"caption\":\"采集规则\",\"href\":\"spider&do=rule\",\"icon\":\"magnet\"},{\"caption\":\"添加规则\",\"href\":\"spider&do=addrule\",\"icon\":\"edit\"},{\"caption\":\"-\"},{\"caption\":\"发布模块\",\"href\":\"spider&do=post\",\"icon\":\"magnet\"},{\"caption\":\"添加发布\",\"href\":\"spider&do=addpost\",\"icon\":\"edit\"}]},{\"caption\":\"-\",\"sort\":\"-993\"}]}]',1493011644,1);

UNLOCK TABLES;

-- DROP TABLE IF EXISTS `icms_access_log`;
/* Create table in target */
CREATE TABLE IF NOT EXISTS `icms_access_log`(
  `id` int(10) unsigned NOT NULL  auto_increment ,
  `uid` int(10) NOT NULL  DEFAULT 0 ,
  `username` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' ,
  `app` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' ,
  `uri` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' ,
  `useragent` varchar(512) COLLATE utf8_general_ci NOT NULL  DEFAULT '' ,
  `ip` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' ,
  `method` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' ,
  `referer` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' ,
  `addtime` int(10) NOT NULL  DEFAULT 0 ,
  PRIMARY KEY (`id`) ,
  KEY `uid`(`uid`) ,
  KEY `app`(`app`) ,
  KEY `ip`(`ip`)
) ENGINE=MyISAM DEFAULT CHARSET='utf8' COLLATE='utf8_general_ci';

-- DROP TABLE IF EXISTS `icms_article_meta`;

/* Create table in target */
CREATE TABLE IF NOT EXISTS `icms_article_meta`(
  `id` int(10) unsigned NOT NULL  ,
  `data` mediumtext COLLATE utf8_general_ci NOT NULL  ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET='utf8' COLLATE='utf8_general_ci';


-- DROP TABLE IF EXISTS `icms_category_meta`;
/* Create table in target */
CREATE TABLE IF NOT EXISTS `icms_category_meta`(
  `id` int(10) unsigned NOT NULL  ,
  `data` mediumtext COLLATE utf8_general_ci NOT NULL  ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET='utf8' COLLATE='utf8_general_ci';


-- DROP TABLE IF EXISTS `icms_files_map`;

/* Create table in target */
CREATE TABLE IF NOT EXISTS `icms_files_map`(
  `fileid` int(10) unsigned NOT NULL  ,
  `userid` int(10) unsigned NOT NULL  ,
  `appid` int(10) unsigned NOT NULL  ,
  `indexid` int(10) unsigned NOT NULL  ,
  `addtime` int(10) unsigned NOT NULL  ,
  PRIMARY KEY (`fileid`,`appid`,`indexid`)
) ENGINE=MyISAM DEFAULT CHARSET='utf8' COLLATE='utf8_general_ci';

-- DROP TABLE IF EXISTS `icms_forms`;

/* Create table in target */
CREATE TABLE IF NOT EXISTS `icms_forms`(
  `id` int(10) unsigned NOT NULL  auto_increment COMMENT '表单ID' ,
  `app` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' COMMENT '表单标识' ,
  `name` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' COMMENT '表单名' ,
  `title` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' COMMENT '表单标题' ,
  `pic` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' COMMENT '表单图片' ,
  `description` varchar(5120) COLLATE utf8_general_ci NOT NULL  DEFAULT '' COMMENT '表单简介' ,
  `tpl` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' COMMENT '表单模板' ,
  `table` text COLLATE utf8_general_ci NOT NULL  COMMENT '表单表' ,
  `config` text COLLATE utf8_general_ci NOT NULL  COMMENT '表单配置' ,
  `fields` text COLLATE utf8_general_ci NOT NULL  COMMENT '表单字段' ,
  `addtime` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT '添加时间' ,
  `type` tinyint(1) unsigned NOT NULL  DEFAULT 0 COMMENT '表单类型' ,
  `status` tinyint(1) NOT NULL  DEFAULT 0 COMMENT '表单状态' ,
  PRIMARY KEY (`id`) ,
  KEY `idx_name`(`app`)
) ENGINE=MyISAM DEFAULT CHARSET='utf8' COLLATE='utf8_general_ci';

-- DROP TABLE IF EXISTS `icms_spider_error`;

/* Create table in target */
CREATE TABLE IF NOT EXISTS `icms_spider_error`(
  `id` int(10) unsigned NOT NULL  auto_increment ,
  `rid` int(10) unsigned NOT NULL  DEFAULT 0 ,
  `pid` int(10) unsigned NOT NULL  DEFAULT 0 ,
  `sid` int(10) unsigned NOT NULL  DEFAULT 0 ,
  `url` varchar(1024) COLLATE utf8_general_ci NOT NULL  DEFAULT '' ,
  `msg` varchar(1024) COLLATE utf8_general_ci NOT NULL  DEFAULT '' ,
  `work` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' ,
  `date` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' ,
  `addtime` int(10) unsigned NOT NULL  DEFAULT 0 ,
  `type` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET='utf8' COLLATE='utf8_general_ci';

-- DROP TABLE IF EXISTS `icms_tag_meta`;

/* Create table in target */
CREATE TABLE IF NOT EXISTS `icms_tag_meta`(
  `id` int(10) unsigned NOT NULL  ,
  `data` mediumtext COLLATE utf8_general_ci NOT NULL  ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET='utf8' COLLATE='utf8_general_ci';

-- DROP TABLE IF EXISTS `icms_weixin_event`;

/* Create table in target */
CREATE TABLE IF NOT EXISTS `icms_weixin_event`(
  `id` int(10) unsigned NOT NULL  auto_increment ,
  `pid` int(10) unsigned NOT NULL  DEFAULT 0 ,
  `name` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' ,
  `eventype` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' COMMENT '事件类型' ,
  `eventkey` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' COMMENT '事件KEY值/关键词' ,
  `msgtype` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' COMMENT '回复类型' ,
  `operator` varchar(10) COLLATE utf8_general_ci NOT NULL  DEFAULT '' COMMENT '匹配模式' ,
  `msg` mediumtext COLLATE utf8_general_ci NOT NULL  COMMENT '消息内容包含格式' ,
  `addtime` int(10) unsigned NOT NULL  DEFAULT 0 ,
  `status` tinyint(1) unsigned NOT NULL  DEFAULT 0 ,
  PRIMARY KEY (`id`) ,
  KEY `eventkey`(`eventkey`)
) ENGINE=MyISAM DEFAULT CHARSET='utf8' COLLATE='utf8_general_ci';
