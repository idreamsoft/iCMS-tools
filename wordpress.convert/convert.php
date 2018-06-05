<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 icmsdev.com iiimon Inc. All rights reserved.
*
* @author coolmoo <icmsdev@qq.com>
* @site http://www.icmsdev.com
* @licence http://www.icmsdev.com/license.php
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
		<title>wordpress 转 iCMS - 数据转换向导</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta content="iCMSdev.com" name="Copyright" />
		<link href="../app/admincp/ui/bootstrap/2.3.2/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
		<link href="../app/admincp/ui/bootstrap/2.3.2/css/bootstrap-responsive.min.css" type="text/css" rel="stylesheet"/>
		<link href="../app/admincp/ui/font-awesome/4.2.0/css/font-awesome.min.css" type="text/css" rel="stylesheet"/>
		<link href="../app/admincp/ui/artDialog/ui-dialog.css" type="text/css" rel="stylesheet"/>
		<link href="../app/admincp/ui/iCMS.css" type="text/css" rel="stylesheet"/>
		<script src="../app/admincp/ui/jquery-1.11.0.min.js"></script>
		<script src="../app/admincp/ui/bootstrap/2.3.2/js/bootstrap.min.js"></script>
		<script src="../app/admincp/ui/artDialog/dialog-plus-min.js"></script>
		<script src="../app/admincp/ui/iCMS.js"></script>


		<link href="./convert.css" type="text/css" rel="stylesheet"/>

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
				<p>wordpress 转 iCMS</p>
				<p><a class="btn btn-primary btn-large" data-toggle="start">开始吧！iCMS君</a></p>
			</div>
		</div>
		<div class="social">
			<div class="container">
			</div>
		</div>
		<div class="container">
			<div class="clearfix mt60"></div>
			<div class="marketing step" id="step0">
				<h1>iCMS v7.0介绍。</h1>
				<p class="marketing-byline">需要为爱上iCMS找N多理由吗？ 就在眼前。</p>
				<div class="row-fluid">
					<div class="span4">
						<img class="marketing-img" src="./img/Development.png">
						<h2>十年磨一剑,免费且开源</h2>
						<p>由艾梦软件历时多年开发，并在实际项目中高效运行。iCMS 项目使用了
						<a href="https://www.icmsdev.com/iPHP/" target="_blank">iPHP</a>、
						<a href="http://github.com/twbs/bootstrap" target="_blank">Bootstrap</a>、
						<a href="http://jquery.com" target="_blank">jQuery</a>、
						<a href="http://ueditor.baidu.com" target="_blank">UEditor</a>、
						<a href="https://github.com/aui/artDialog" target="_blank">artDialog</a>等开源软件，
						并托管在 <a href="http://github.com/idreamsoft/iCMS" target="_blank">GitHub</a>、<a target="_blank" href="http://git.oschina.net/php/icms">GIT@OSC</a> 上，方便大家使用这一套程序构建更好的web应用。
						</p>
					</div>
					<div class="span4">
						<img class="marketing-img" src="./img/responsive-design.png">
						<h2>一套程序,适配多种设备</h2>
						<p>你的网站能在 <a href="https://www.icmsdev.com" target="_blank">iCMS</a> 的帮助下通过一套内容管理系统快速、有效适配手机、微信、微信小程序、平板、PC等设备，这一切都是归于 iCMS 多终端适配功能。</p>
					</div>
					<div class="span4">
						<img class="marketing-img" src="./img/Enterprise-Features.jpg">
						<h2>完整的功能支持</h2>
						<p><a href="https://www.icmsdev.com" target="_blank">iCMS</a> 提供了网站运营所需的基本功能。也提供了功能强大标签(TAG)系统、自定义应用、自定义表单、内容多属性多栏目归属、自定义内链、高负载、整合第三方登陆</p>
					</div>
				</div>
			</div>
			<div class="well hide step" id="step1">
				<h1>第一步：配置信息</h1>
				<h2>wordpress数据库连接配置</h2>
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
							<input type="text" class="span4" id="DB_NAME" name="DB_NAME" placeholder="数据库名" value="wordpress">
							<span class="help-block">本程序只读取数据</span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="DB_PREFIX">数据表名前缀</label>
						<div class="controls">
							<input type="text" class="span4" id="DB_PREFIX" name="DB_PREFIX" value="wp_">
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
							<span class="help-block">选择后程序将在转换时清空[article][article_data][category][category_map][tag][tag_map]。</span>
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
				<p>艾梦软件(<a href="http://www.icmsdev.com" target="_blank">iCMSdev.com</a>) 版权所有  &copy; 2007-2018</p>
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
