<?php
$dcb = new LtDbConfigBuilder;
$dcb->addHost("user_group", "user_node_1", "master", array("host" => "10.0.1.1", "password" => "123456", "adapter" => "mysqli", "dbname" => "member_1"));
$dcb->addHost("group_1", "node_1", "master", array("adapter" => "sqlite", "host" => '/tmp/Lotus/unittest/DBSqlite/', "port" => '', "password" => "", "dbname" => 'sqlite_test1.db', 'pconnect' => ''));
$dcb->addHost("group_8", "node_8", "master", array("adapter" => "mysql", "host" => 'localhost', "port" => '', 'username' => 'root', "password" => "123456", "dbname" => 'test'));
$config["db.servers"] = $dcb->getServers();
