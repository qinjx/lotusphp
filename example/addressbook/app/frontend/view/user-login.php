<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登陆系统</title>
<link href="{$this->data['baseurl']}css/default.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="top"><a href="{url('User','Register')}">注册</a> <span style="float:right;margin-right:24px;"><a href="/">返回首页</a></span></div>
<div class="area">

<div id="loginbox" class="right">
  <form  action="{url('User','DoLogin')}" method="post" name="myform" id="myform">
    <h3>登陆系统</h3>
    <table border="0" cellpadding="0" cellspacing="0" summary="登陆">
      <tbody>
        <tr>
          <td>帐　号:</td>
          <td><input name="username" type="text" id="username" size="20"/></td>
        </tr>
        <tr>
          <td>密　码:</td>
          <td><input name="password" type="password" id="password" size="21"/></td>
        </tr>
<!--
        <tr>
          <td></td>
          <td><img align="absmiddle" alt="验证码,看不清楚?请点击刷新验证码" style="cursor: pointer;" onclick="this.src='{url('Captcha','Image',array('seed'=>123456))}&tmp='+Math.random();" id="checkcode" src="{url('Captcha','Image',array('seed'=>123456))}"></td>
        </tr>
        <tr>
          <td></td>
          <td><a href="javascript:showck();">看不清，换一张。</a></td>
        </tr>
        <tr>
          <td>验证码:</td>
          <td><input name="checkcode" type="text" id="checkcode" size="21"/></td>
        </tr>
-->
        <tr>
          <td></td>
          <td><input type="submit" name="dosubmit" id="login" value="登 陆"/></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>

<div class="messagebox left">
	<div class="infobox">
		<h3>多用户通讯录</h3>
		<p>多用户通讯录。如果你还没有注册，请 <a href="{url('User','Register')}">注册</a> 后再登陆。基于好用又强大的PHP开发框架<b>Lotusphp</b>。这是做为一个演示目的创建的应用。为了方便，使用了sqlite数据库。而实际上<b>Lotusphp</b>的Cache和Db组件都是支持分布的。</p>
		<h3>Lotusphp说明</h3>
		<p>做好用又强大的PHP框架</p> 
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


</div>
<div id="footer">&copy; 2010 <span id="debug_info"></span></div>
<script type="text/javascript">
//<![CDATA[
//document.getElementById("username").focus();
//function showck(){
//    var ck = document.getElementById('checkcode');
//    ck.src = '{url('Captcha','Image',array('seed'=>123456))}&tmp='+ Math.random();//每次产生不同的url才能刷新
//}
//]]>
</script>
</body>
</html>
