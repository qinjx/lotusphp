<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--{if !empty($this->data['forward']) && $this->data['forward'] != "goback"}-->
<meta http-equiv="refresh" content="2;URL={$this->data['forward']}" />
<!--{/if}-->
<title>{$this->data['title']}</title>
<link href="{$this->data['baseurl']}css/default.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="top"><span style="float:right;margin-right:24px;"><a href="/">返回首页</a></span></div>
<div class="messagebox">
<div class="infobox">
<?php 
// 根据代码替换成中文信息
if (empty($this->message))
{
	switch ($this->code)
	{
		case '403':
			$this->message = '无权访问';
			break;
		case '406':
			$this->message = '参数验证出错';
			break;
		case '407':
			$this->message = '无效的输入';
			break;
		case '200':
			$this->message = '操作成功';
			break;
	}
}
?>
<div>
<h3>{$this->message}</h3>
<?php
if (isset($this->data['error_messages']) && is_array($this->data['error_messages']))
{
	foreach($this->data['error_messages'] as $dtd)
	{
		foreach($dtd as $msg)
		{
			echo "<p>$msg</p>";
		}
	}
}
echo '<pre>';
print_r($this->data);
echo '</pre>';
?>
</div>
<p style="text-align:center;">
<!--{if $this->data['forward'] == "goback"}-->
  <a href="javascript:history.go(-1);" >[ 返回 ]</a>
<!--{elseif $this->data['forward']}-->
  <a href="{$this->data['forward']}">[ 确定 ]</a>
<!--{/if}-->
</p>
</div>
</div>

<div id="footer"><span id="copyright">powered by Lotusphp</span> <span id="debug_info"></span></div>
</body>
</html>
