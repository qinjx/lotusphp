<?php
$lotusHome = substr(dirname(__FILE__), 0, strpos(__FILE__, "example"));
include($lotusHome . "runtime/Lotus.php");
$lotus = new Lotus;
$lotus->option["proj_dir"] = dirname(dirname(dirname(__FILE__)));
$lotus->option["app_name"] = "frontend";
$lotus->init();