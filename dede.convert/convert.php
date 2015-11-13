<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.0.0
* @$Id: dedetoiCMS.php 2330 2014-01-03 05:19:07Z coolmoo $
*/
define('iPHP',TRUE);
define('iPHP_APP','iCMS'); //应用名
define('iPATH',dirname(strtr(__FILE__,'\\','/'))."/../");
//框架初始化
require iPATH.'iPHP/iPHP.php'; //iPHP框架文件
?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>DEDECMS 转 iCMS - 数据转换向导</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta content="iDreamSoft Inc." name="Copyright" />
		<link href="../app/ui/common/bootstrap/2.3.2/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
		<link href="../app/ui/common/bootstrap/2.3.2/css/bootstrap-responsive.min.css" type="text/css" rel="stylesheet"/>
		<link href="../app/ui/common/font-awesome/4.2.0/css/font-awesome.min.css" type="text/css" rel="stylesheet"/>
		<link href="../app/ui/common/artDialog/6.0.3/ui-dialog.css" type="text/css" rel="stylesheet"/>
		<link href="../app/ui/common/iCMS-6.0.0.css" type="text/css" rel="stylesheet"/>
		<link href="./convert-6.0.0.css" type="text/css" rel="stylesheet"/>
		<!--[if lt IE 9]>
		<script src="../app/ui/common/ie/html5shiv.min.js"></script>
		<script src="../app/ui/common/ie/respond.min.js"></script>
		<![endif]-->
		<script src="../app/ui/common/jquery-1.11.0.min.js"></script>
		<script src="../app/ui/common/bootstrap/2.3.2/js/bootstrap.min.js"></script>
		<script src="../app/ui/common/artDialog/6.0.3/dialog-plus-min.js"></script>
		<script src="../app/ui/common/iCMS-6.0.0.js"></script>
		<script>
		var install = {
			start:function () {
				$(".step").hide();
				this.step(0,1);
			},
			step1:function (a,b) {
				this.step(1,2);
			},
			step2:function (a,b) {
				this.step(2,3);
			},
			step3:function (a,b) {
				this.step(3,4);
			},
			step4:function (a,b) {
				this.step(4,5);
			},
			step:function (a,b) {
				$("#step"+b).show();
				$("#step"+a).hide();
				$('body').animate({
                    scrollTop: 570
                });
			},
		}
		$(function() {
			$('[data-toggle]').click(function(event) {
				event.preventDefault();
				var action = $(this).attr('data-toggle');
				install[action]();
			});
			<?php if($_GET['step']){?>
				$(".step").hide();
				$("#step<?php echo $_GET['step'];?>").show();
				$('body').animate({
                    scrollTop: 570
                });
			<?php }?>
			$("#install_btn").click(function(event) {
				event.preventDefault();

				var db_host    = $('#DB_HOST').val(),
				db_user        = $('#DB_USER').val(),
				db_password    = $('#DB_PASSWORD').val(),
				db_name        = $('#DB_NAME').val();

				if(db_host==''){
					iCMS.alert('请填写数据库服务器地址');
					$('#DB_HOST').focus();
					return false;
				}
				if(db_user==''){
					iCMS.alert('请填写数据库用户名');
					$('#DB_USER').focus();
					return false;
				}

				if(db_password==''){
					iCMS.alert('请填写数据库密码');
					$('#DB_PASSWORD').focus();
					return false;
				}

				if(db_name==''){
					iCMS.alert('请填写数据库名');
					$('#DB_NAME').focus();
					return false;
				}

				// $(this).button('loading');
				$("#install_form").submit();
			});

		})
		function callback(el){
			if(el){
				$(el).focus();
			}
			$("#install_btn").button('reset');
		}
		</script>
		<style>
			.TRUNCATE{font-size: 14px;}
			.TRUNCATE .add-on{padding: 10px;}
			.TRUNCATE .checkbox{height: auto !important;}
		</style>
	</head>
	<body>
		<div class="jumbotron masthead">
			<div class="container">
				<h1>数据转换向导</h1>
				<p>DEDECMS 转 iCMS</p>
				<p><a class="btn btn-primary btn-large" data-toggle="start">开始吧！iCMS君</a></p>
				<ul class="masthead-links">
					<li>
						<a href="http://github.com/idreamsoft/icms" target="_blank">源码</a>
					</li>
					<li>
						<a href="http://www.idreamsoft.com" target="_blank">官网</a>
					</li>
					<li>
						<a href="http://www.idreamsoft.com/examples" target="_blank">案例</a>
					</li>
					<li><a href="https://www.linode.com/?r=35103ee1524aaff9b3edcca8cf7de8fab6e5cf9e" target="_blank">VPS推荐</a></li>
					<li><a href="http://s.click.taobao.com/t?e=m%3D2%26s%3D7JZW1mRrlBccQipKwQzePCperVdZeJviEViQ0P1Vf2kguMN8XjClAmpVu972RyTOt3h8CUvoNV%2Fh102VLn%2Bh5EqjLczoYHWIfyEaJ8bL%2F82Uatkzf5yweOdn1BbglxZYxUhy8exlzcq9AmARIwX9K%2BnbtOD3UdznPV1H2z0iQv9NkKVMHClW0QbMqOpFMIvnvjQXzzpXdTHGJe8N%2FwNpGw%3D%3D" target="_blank">阿里云</a></li>
					<li>
						Version 6.0.0
					</li>
				</ul>
			</div>
		</div>
		<div class="social">
			<div class="container">
			</div>
		</div>
		<div class="container">
			<div class="clearfix mt60"></div>
			<div class="marketing step" id="step0">
				<h1>iCMS V6介绍。</h1>
				<p class="marketing-byline">需要为爱上iCMS找N多理由吗？ 就在眼前。</p>
				<div class="row-fluid">
					<div class="span4">
						<img class="marketing-img" src="./img/Development.png">
						<h2>人人为我，我为人人。</h2>
						<p>由<a href="http://t.qq.com/idreamsoft">@艾梦软件</a> 历时两年多开发，并在实际项目中高效运行。iCMS 使用了
						<a href="http://www.idreamsoft.com/iPHP/">iPHP</a>、
						<a href="http://github.com/twbs/bootstrap">Bootstrap</a>、
						<a href="http://jquery.com">jQuery</a>、
						<a href="https://github.com/aui/artDialog">artDialog</a>等开源软件，
						并托管在 <a href="http://github.com">GitHub</a> 上，方便大家使用这一套程序构建更好的web应用。
						</p>
					</div>
					<div class="span4">
						<img class="marketing-img" src="./img/responsive-design.png">
						<h2>一套程序、多种设备。</h2>
						<p>你的网站能在 <a href="http://www.idreamsoft.com" target="_blank">iCMS</a> 的帮助下通过同一套内容管理系统快速、有效适配手机、平板、PC 设备，这一切都是归于 iCMS 多终端适配功能。</p>
					</div>
					<div class="span4">
						<img class="marketing-img" src="./img/Enterprise-Features.jpg">
						<h2>功能齐全。</h2>
						<p><a href="http://www.idreamsoft.com" target="_blank">iCMS</a> 提供了网站运营所需的基本功能。也提供了功能强大标签(TAG)系统、内容多属性多栏目归属、自定义内链、高负载、整合第三方登陆</p>
					</div>
				</div>
			</div>
			<div class="well hide step" id="step1">
				<h1>第一步：配置信息</h1>
				<h2>DEDECMS数据库连接配置</h2>
				<form class="form-horizontal" action="iCMS.convert.php" method="post" id="install_form" target="iPHP_FRAME">
					<input name="action" type="hidden" value="config" />
					<div class="control-group">
						<label class="control-label" for="DB_HOST">服务器地址</label>
						<div class="controls">
							<input type="text" class="span4" id="DB_HOST" name="DB_HOST" value="localhost">
							<span class="help-block">数据库服务器名或服务器ip，一般为localhost</span>
							<span class="help-block">如果是远程数据库需要远程权限</span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="DB_USER">数据库用户名</label>
						<div class="controls">
							<input type="text" class="span4" id="DB_USER" name="DB_USER" placeholder="数据库用户名" value="root">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="DB_PASSWORD">数据库密码</label>
						<div class="controls">
							<input type="text" class="span4" id="DB_PASSWORD" name="DB_PASSWORD" placeholder="数据库密码" value="123456">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="DB_NAME">数据库名</label>
						<div class="controls">
							<input type="text" class="span4" id="DB_NAME" name="DB_NAME" placeholder="数据库名" value="dedecmsv57utf8sp1">
							<span class="help-block">本程序只读取数据</span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="DB_CHARSET">数据库编码</label>
						<div class="controls">
							<select name="DB_CHARSET" id="DB_CHARSET">
								<option value="utf8" selected="selected">UTF-8版本</option>
								<option value="gbk">GBK版本</option>
							</select>
							<span class="help-block">UTF-8版本,GBK版本</span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="DB_PREFIX">数据表名前缀</label>
						<div class="controls">
							<input type="text" class="span4" id="DB_PREFIX" name="DB_PREFIX" value="dede_">
							<span class="help-block">数据表名前缀，同一数据库安装多个请修改此处。</span>
						</div>
					</div>
					<div class="control-group TRUNCATE">
						<div class="controls">
							<div class="input-prepend input-append">
								<span class="add-on">清空iCMS表</span>
								<span class="add-on">
									<input type="checkbox" class="checkbox" id="TRUNCATE" name="TRUNCATE" title="清空iCMS表"/>
								</span>
							</div>
							<span class="help-block">选择后程序将在转换时清空[category][article][article_data][category_map][prop_map]。</span>
						</div>
					</div>

					<div class="form-actions">
						<button type="button" class="btn btn-large btn-primary" id="install_btn" data-loading-text="安装中，请稍候...">下一步</button>
					</div>
				</form>
			</div>
			<div class="well hide step" id="step2">
				<h1>第二步：恭喜您！顺利安装完成。</h1>
				<div style="width: 300px;margin:50px auto;">
					<a href="../admincp.php" class="btn btn-large btn-block btn-success" target="_blank">管理后台 »</a>
					<hr />
					<a href="../index.php" class="btn btn-large btn-block btn-primary" target="_blank">网站首页 »</a>
				</div>
			</div>
		</div>
		<iframe class="hide" id="iPHP_FRAME" name="iPHP_FRAME"></iframe>
		<footer class="footer">
			<div class="container">
				<p>艾梦软件(<a href="http://www.idreamsoft.com" target="_blank">iDreamSoft.com</a>) 版权所有  &copy; 2007-2014</p>
				<p>iCMS 源码受 <a href="https://github.com/idreamsoft/iCMS/blob/master/LICENSE.md" target="_blank">LGPL</a> 开源协议保护</p>
				<ul class="footer-links">
					<li><a href="http://www.idreamsoft.com" target="_blank">iCMS</a></li>
					<li class="muted">·</li>
					<li><a href="http://www.idreamsoft.com/feedback" target="_blank">反馈问题</a></li>
					<li class="muted">·</li>
					<li><a href="http://www.idreamsoft.com/releases" target="_blank">历史版本</a></li>
				</ul>
			</div>
		</footer>
		<div class="hide">
			<script type="text/javascript">
			var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
			document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F7b43330a4da4a6f4353e553988ee8a62' type='text/javascript'%3E%3C/script%3E"));
			</script>
		</div>
	</body>
</html>
