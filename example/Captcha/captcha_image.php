<?php
include("./captcha.inc.php");

//绘制验证码图片
$imageResource = $captcha->getImageResource($_GET["seed"]);
header("Content-type: image/png");
imagepng($imageResource);