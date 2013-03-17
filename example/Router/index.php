<?php
$lotusHome = substr(__FILE__, 0, strpos(__FILE__, "example")).'/';
include $lotusHome . "runtime/Config.php";
include $lotusHome . "runtime/Store.php";
include $lotusHome . "runtime/StoreMemory.php";
include $lotusHome . "runtime/Router/Router.php";
include $lotusHome . "runtime/Url/Url.php";


// 默认的module和action的名字
$config['router.routing_table']['default'] = array('module' => 'default', 'action' => 'index');
// URL中变量的分隔符号
//$config['router.routing_table']['delimiter'] = '-';
// 后缀，常用来将URL模拟成单个文件
//$config['router.routing_table']['postfix'] = '.html';
// REWRITE STANDARD PATH_INFO 三种模式，不分大小写
$config['router.routing_table']['protocol'] = 'REWRITE';

$configHandle = new LtConfig();
$configHandle->addConfig($config);


$router = new LtRouter();
$router->configHandle = $configHandle;
$router->init();


$url = new LtUrl();
$url->configHandle = $configHandle;
$url->init();

$params = array(
		'id' => 123456,
		'page' => '12',
		//'void' => null,
		'q-/key' => '空 -/格',
		//'empty' => '',
		);

$url->baseUrl = 'http://127.0.0.1';
$link1 = $url->generate('news', 'top');
$url->baseUrl = 'http://localhost';
$link2 = $url->generate('goods', 'detail', $params);
$url->baseUrl = 'http://127.0.0.1';
$link3 = $url->getLink('goods', 'detail', $params);

$url2 = new LtUrl();
// $url2->withPath = false; //是否包含相对路径
$url2->init();
$link4 = $url2->generate('default', 'index', $params, null, 'standard');
$link5 = $url2->generate('default', 'index', $params, null, 'path_info');
$link6 = $url2->generate('default', 'index', $params, null, 'rewrite');

$get = var_export($_GET, true);
$post = var_export($_POST, true);
if (isset($_SERVER['SERVER_PROTOCOL']))
echo <<<END
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>Welcome LotusPHP</title>
<style>
body { padding:12px; margin:12px; font-size:1em; }
ul { margin:0;padding:0;list-style:none;}
ul li { line-height:1.5em;}
.messagebox {margin:24px auto;padding:12px;text-align:left;border:1px solid #8BB2D9;background:#F2F7FF;-moz-border-radius:10px;-webkit-border-radius:10px; }
.infobox { padding:12px;background:#FFFFFF;border:1px solid #E3EFFC; }
.listtable { margin-bottom:6px; width: 100%; border-collapse: collapse; border: solid; border-color: #86B9D6 #D8DDE5 #D8DDE5; border-width: 1px 1px 1px; }
.listtable caption { color:#000; background:#F1F5F8; padding:8px; border: solid; border-color: #D8DDE5 #D8DDE5 #D8DDE5; border-width: 1px 1px 0px; font-weight:bolder; }
.listtable th { background: #F3F7FF; color: #6774A8; border-bottom: 1px solid #86B9D6; padding: 6px; }
.listtable td { border: 1px solid #D8DDE5; padding: 6px;  }
</style>
</head>
<body>
<div class="messagebox">
<ul>
	<li><a href="{$_SERVER['PHP_SELF']}">{$_SERVER['PHP_SELF']}</a></li>
	<li><a href="{$link1}">{$link1}</a></li>
	<li><a href="{$link2}">{$link2}</a></li>
	<li><a href="{$link3}">{$link3}</a></li>
	<li>--------</li>
	<li><a href="{$link4}">{$link4}</a></li>
	<li><a href="{$link5}">{$link5}</a></li>
	<li><a href="{$link6}">{$link6}</a></li>
</ul>
<pre class="infobox">
\$_GET={$get}

\$_POST={$post}
</pre>
  <table class="listtable">
	<tr>
		<td width="150">LtRouter</td>
		<td>{$router}</td>
	</tr>
	<tr>
		<td>SERVER_PROTOCOL</td>
		<td>{$_SERVER['SERVER_PROTOCOL']}</td>
	</tr>
	<tr>
		<td>REQUEST_URI</td>
		<td>{$_SERVER['REQUEST_URI']}</td>
	</tr>
	<tr>
		<td>SCRIPT_NAME</td>
		<td>{$_SERVER['SCRIPT_NAME']}</td>
	</tr>
	<tr>
		<td>PHP_SELF</td>
		<td>{$_SERVER['PHP_SELF']}</td>
	</tr>
  </table>
  <pre class="infobox">
概述
--------------------------------------------------------------------------
standard   不需要 webserver 作特殊处理, php 自动生成 \$_GET 数组
path_info  不需要 webserver 支持 PATH_INFO
rewrite      需要 webserver 加载 REWRITE 模块，把所有请求定向到入口文件

router处理path_info和rewrite请求前，先将?&=替换成相应的分隔符号。
通过检查是否存在 ".php?module=" "/?module=" 字符串判断是否为标准请求。

url生成path_info或rewrite链接时，变量名和值如果包含分隔符号，那么此分隔符号被替换成%FF。
router解析时将%FF替换成分隔符号。

router
--------------------------------------------------------------------------
按配置文件指定的链接形式（standard,path_info,rewrite）解析出 模块、动作，参数列表。
解析出内容保存在\$_GET全局数组。
可以随时解析标准形式的url，不关心配置文件指定成什么。
不对解析出的内容作过滤，例如不会判断参数是否包含非法字符。
对参数进行rawurldecode解码。

url
--------------------------------------------------------------------------
输入信息必需包含：module,action
输入信息可选包含：参数列表，域名端口路径，链接形式（standard,path_info,rewrite）。
输出配置文件中指定的链接形式（standard,path_info,rewrite）。
可随时输出标准形式的get链接，不关心配置文件指定成什么。
如果入口文件不在网站根目录，生成链接可以选择是否包含相对路径（相对于DocumentRoot路径）。
生成url不对参数进行过滤，应用层解决参数的有效性。
生成的url要对参数进行rawurlencode编码，空格编码为%20。而不是urlencode编码将空格变为+号。


示例：
--------------------------------------------------------------------------
输出get链接
http://detail.kinhom.com/index.php?module=sku&action=detail&id=10001162

输出path_info链接，可以添加后缀伪装成html
http://detail.kinhom.com/index.php/sku/detail/id/10001162 
http://detail.kinhom.com/index.php/sku/detail/id/10001162.html 

输出rewrite链接，可以添加后缀伪装成html，可以指定分隔符号伪装成单个文件
http://detail.kinhom.com/sku/detail/id/10001162
http://detail.kinhom.com/sku/detail/id/10001162.html

http://detail.kinhom.com/sku-detail-id-10001162
http://detail.kinhom.com/sku-detail-id-10001162.html


复杂一点的链接
/index.php?module=sku&action=search&q=&cat_id=2&min=0&max=0&page=2

输出path_info链接，可以添加后缀伪装成html
/index.php/sku/search/q//cat_id/2/min/0/max/0/page/2
/index.php/sku/search/q//cat_id/2/min/0/max/0/page/2.html

输出rewrite方式为目录，可以添加后缀伪装成html，可以指定分隔符号伪装成单个文件
http://search.kinhom.com/sku/search/q//cat_id/2/min/0/max/0/page/2
http://search.kinhom.com/sku/search/q//cat_id/2/min/0/max/0/page/2.html

http://search.kinhom.com/sku-search-q--cat_id-2-min-0-max-0-page-2
http://search.kinhom.com/sku-search-q--cat_id-2-min-0-max-0-page-2.html

--------------------------------------------------------------------------
router 
对于以上请求能还原成 \$_GET 数组
不管配置在那种模式下，必需能接收标准的get请求。
http://search.kinhom.com/sku-search-q--cat_id-2-min-0-max-0-page-2.html
\$_GET = array(
    'module'=>'sku',
    'action'=>'search',
    'q'=>'',
    'cat_id'=>'2',
    'min'=>'0',
    'max'=>'0',
    'page'=>'2',
);
  </pre>
  <p>nginx启用rewrite配置如下，通过try_files命令指定，当文件、目录不存在时，入口文件位置。
  根据实际情况修改，例如/index.php改成/Router/index.php。</p>
  <pre class="infobox">
location / {
    try_files \$uri \$uri/ /index.php\$is_args\$args;
}
location ~ \.php\$ {
    include fastcgi.conf;
    fastcgi_pass 127.0.0.1:9000;
}
  </pre>
  <p>apache支持的.htaccess文件与入口文件放相同目录</p>
  <pre class="infobox">
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

RewriteRule . index.php
  </pre>
</div>
</body>
</html>
END;
if (isset($_SERVER['argv']))
{
	print_r($_SERVER['argv']);
	print_r($_GET);
	echo $router;
}