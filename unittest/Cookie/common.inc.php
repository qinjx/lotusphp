<?php
$lotusHome = substr(__FILE__, 0, strpos(__FILE__, "unittest"));
require_once $lotusHome . "runtime/Config.php";
require_once $lotusHome . "runtime/Store.php";
require_once $lotusHome . "runtime/StoreMemory.php";
require_once $lotusHome . "runtime/Cookie/Cookie.php";

class CookieProxy extends LtCookie
{
	public function decrypt($seed)
	{
		return parent::decrypt($seed);
	}

	public function encrypt($seed)
	{
		return parent::encrypt($seed);
	}
}