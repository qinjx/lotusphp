<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$this->data['title']}</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="{$this->data['baseurl']}css/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{$this->data['baseurl']}js/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function(){ 
  $(".stripe tr").mouseover(function(){ 
    $(this).addClass("over");}).mouseout(function(){ 
    $(this).removeClass("over");})
    $(".stripe tr:even").addClass("darkrow");
});
function confirmurl(url,message)
{
	if(confirm(message)) location.href = url;
}

function confirmform(form,message)
{
	if(confirm(message)) form.submit();
}
</script>
</head>
<body>
<div id="sitenav"><?php if(!empty($this->data['uid'])) {?><span>登陆帐号:{$this->data['username']} <a onclick="return confirm('确定要退出系统么？');" href="{url('User', 'DoLogout')}" target="_self">退出系统</a><?php }?> <a href="javascript:void(0)">关于</a></span><a href="{$this->data['baseurl']}">首页</a></div>
<div id="header">
  <h1>addressbook</h1>
</div>
<div class="area">
  <div class="nav">
	<ul>
	<li><a href="{$this->data['baseurl']}">首页</a></li>
	<li><a href="javascript:void(0)">关于</a></li>
	</ul>
  </div>
</div>
<div class="blank12"></div>
{include $this->templateDir . $this->template . '.php'}
<div id="footer"><span id="copyright">powered by Lotusphp</span> <span id="debug_info"></span></div>
</body>
</html>
