<?php
if (!isset($_POST["captcha_word"]))
{
	$seed = uniqid();
	echo "<!doctype html>
<html>
<head>
<meta charset=\"utf-8\" />
<title>Captcha LotusPHP</title>
</head>
<body>
<form action='simplest.php' method='post'>
<img src='captcha_image.php?seed=$seed' />
<input type='hidden' name='seed' value='$seed' />
<br />
请输入上图中的验证码：<input type='text' name='captcha_word' />
<input type='submit' />
</form>
</body>
</html>
";
}
else
{
	/*
	 * 校验用户输入的验证码是否正确
	 */
	include("./captcha.inc.php");
	if ($captcha->verify($_POST["seed"], $_POST["captcha_word"]))
	{
		echo "验证码输入正确";
	}
	else
	{
		echo "验证码输入错误，请<a href='simplest.php'>重试</a>";
	}
}