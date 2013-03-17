<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title>{$this->data[title]}Using View</title>
	<style type="text/css">
/* Global CSS */

<!-- 
	* {
		font-size: 96%;
		font-family: verdana;
	} 
-->
	/*
	注释内容被模板引擎删除
	模板引擎中css{}内要有空格,防止解析出错
	*/
<!--[if IE]>
body{ margin:0;padding:0; }
<![endif]-->

	</style>
<script type="text/javascript">
// 注释内容被模板引擎删除
//<![CDATA[
var logged_in_user_email = null;
//]]>

	/*
	注释内容被模板引擎删除
	*/
<!--//<![CDATA[
 var codesite_token = null;
//]]>-->
</script>

</head>
<body>
<!--[if IE 6]>
<div style="text-align:center;">
Support browsers that contribute to open source, try <a href="http://www.firefox.com">Firefox</a> or <a href="http://www.google.com/chrome">Google Chrome</a>.
</div>
<![endif]-->
<hr />
Navigator:
<a href="simplest_tpl.php?module=User&action=Signin">User Signin</a>
<a href="simplest_tpl.php?module=test&action=UsingComponent">Using Component</a>
<a href="simplest_tpl.php?module=test&action=UsingBlankLayout">Using Blank Layout</a>
<a href="simplest_tpl.php?module=test&action=PassData">Pass Data from Action</a>
<a href="simplest_tpl.php?module=test&action=UsingTitle">Using Title</a>

<pre>
	Action file: {$this->context->uri['module']}{$this->context->uri['action']}Action.php
	Layout file: {__FILE__}{LF}
	Template file: {$this->templateDir}{$this->template}.php
</pre>
<hr />
{include $this->templateDir . $this->template}
<hr />

<!-- 
中文 数字123之类, 字母abcDEF之类，注释内容被删除。	
	 -->

<!-- test < delete -->
<!-- test > delete -->
<!-- test { delete -->
<!-- test } delete -->
<pre>
// 单行注释
/*
多行
注释
有可能是常规显示内容
保留
*/
<!--  -->
</pre>

<script type="text/javascript">
// 注释内容被模板引擎删除
//<![CDATA[
var logged_in_user_email = null;
//]]>

	/*
	注释内容被模板引擎删除
	*/
<!--//<![CDATA[
 var codesite_token = null;
//]]>-->
</script>

</body>
</html>