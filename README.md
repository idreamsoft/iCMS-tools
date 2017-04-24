# iCMS 内容管理系统 一些脚本工具

##dede.convert
> * dedecms文章转换程序
> * 把本目录放到iCMS程序的根目录下
> * 然后给conf目录写权限
> * 注：此转换程序无法转换DEDE的自定义字段

##php.shell
> * iCMS php脚本工具
> 在Linux下测试通过，WIN下CMD执行可能出现乱码 可忽略
> WIN下请移除php文件开头的下面代码 `#!/usr/local/php/bin/php`
> linux 如果php路径不在`/usr/local/php/bin/php`请用`ln`或者修改php脚本
> linux 下给php脚本执行权限 `chmod +x php脚本`
> 执行：`/php脚本路径/php脚本.php`

##php.shell/v6.0
> iCMS v6.0 php脚本工具

|脚本|说明|
|--------|:-----|
|downpic.php|下载草稿文章里的图片|
|re.picsize.php|查找符合条件的文章里的图片超过设置的大小 重新生成|
|re.category_map.php|重新关联category map|
|re.prop_map.php|重新关联prop map|
|re.watermark.php|重新加载水印|
|sitemap.page.php|生成重站sitemap|
|sitemap.php|生成最新的内容的sitemap|
|spider.pid.php|采集指定pid|
|spider.shell.php|采集标记自动采集|
|update.index.php|生成首页|

##php.shell/v7.0
> iCMS v7.0 php脚本工具

|脚本|说明|
|--------|:-----|
|spider.shell.php|采集标记自动采集|


##template/iCMS
> php脚本工具用到的模板
