<?php
// 在使用path_info或者rewrite模式时，要设置网站根目录访问路径。
$config['baseurl'] = rtrim(pathinfo($_SERVER['PHP_SELF'],PATHINFO_DIRNAME), '\\/') . '/';
