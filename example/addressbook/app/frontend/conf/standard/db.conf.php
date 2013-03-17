<?php
$host = substr(dirname(__FILE__), 0, strpos(__FILE__, "app")).'data/';
$dbname = 'addressbook.sdb';

$singlehost = array("adapter" => "sqlite", "host" => $host, "port"=>'', "password" => "", "dbname" => $dbname, 'pconnect' => false);
$dcb = new LtDbConfigBuilder;
$dcb->addSingleHost($singlehost);
$config["db.servers"] = $dcb->getServers();
