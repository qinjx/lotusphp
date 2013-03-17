<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>Welcome LotusPHP</title>
<style>
body { padding:0px; margin:0px; font-size:12px;text-align:center; }
.messagebox {width:640px;margin:24px auto;padding:12px;text-align:left;border:1px solid #8BB2D9;background:#F2F7FF;-moz-border-radius:10px;-webkit-border-radius:10px; }
.messagebox h3 { margin:0;padding:0;height:28px; line-height:28px; font-size:14px;border:2px solid #FFFFFF;background:#F2F7FF;text-indent:12px; }
.messagebox p { margin:12px;padding:0;font-size:14px;color:#000; }
.infobox { background:#FFFFFF;border:1px solid #E3EFFC; }
#debug_info { text-align:center;color:#666;}
</style>
<base target="_blank" />
</head>
<body>
<!--[if IE]>
<div style="text-align:center;background:#F2F7FF;height:32px;line-height:32px;">
Support browsers that contribute to open source, try <a href="http://www.firefox.com">Firefox</a> or <a href="http://www.google.com/chrome">Google Chrome</a>.
</div>
<![endif]-->
<div class="messagebox">
	<div class="infobox">
		<h3><?php echo $this->message;?></h3>
		<p>The page you are looking at is being generated dynamically by <?php echo $this->data['name'];?>.</p>
		<p>你正在看的网页是由 <?php echo $this->data['name'];?> 动态生成。</p>
		<p>Lotusphp 做好用又强大的PHP框架</p> 
		<p>论坛：<a href="http://bbs.lotusphp.com">bbs.lotusphp.com</a></p>
		<p>需求：<a href="http://code.google.com/p/lotusphp/wiki/lotus_todo">code.google.com/p/lotusphp/wiki/lotus_todo</a></p>
		<p>SVN提交动态：<a href="http://code.google.com/p/lotusphp/source/list">code.google.com/p/lotusphp/source/list</a></p>
		<h3>kiwiphp用户看这里：</h3>
		<p>kiwiphp官方网站：<a href="http://www.kiwiphp.com">www.kiwiphp.com</a> 推荐使用 0.2.0版本</p>
		<p>文档：</p>
		<p><a href="http://wiki.kiwiphp.com">wiki.kiwiphp.com</a></p>
		<p><a href="http://manual.kiwiphp.com">manual.kiwiphp.com</a></p>
	</div>
</div>
<div id="debug_info"></div>
</body>
</html>