<?php
$ccb = new LtCacheConfigBuilder;
$ccb->addSingleHost(
	array("adapter" => "phps",
		"host" => "/tmp/Lotus/unittest/cache/phps"
		));

$config["cache.servers"] = $ccb->getServers();
