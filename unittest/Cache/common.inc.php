<?php
$lotusHome = substr(__FILE__, 0, strpos(__FILE__, "unittest"));
require_once $lotusHome . "runtime/Config.php";
require_once $lotusHome . "runtime/Store.php";
require_once $lotusHome . "runtime/StoreMemory.php";
require_once $lotusHome . "runtime/StoreFile.php";
require_once $lotusHome . "runtime/Cache/Cache.php";
require_once $lotusHome . "runtime/Cache/CacheAdapterFactory.php";
require_once $lotusHome . "runtime/Cache/CacheConfigBuilder.php";
require_once $lotusHome . "runtime/Cache/CacheConnectionManager.php";
require_once $lotusHome . "runtime/Cache/CacheHandle.php";
require_once $lotusHome . "runtime/Cache/Adapter/CacheAdapter.php";
require_once $lotusHome . "runtime/Cache/Adapter/CacheAdapterApc.php";
require_once $lotusHome . "runtime/Cache/Adapter/CacheAdapterEAccelerator.php";
require_once $lotusHome . "runtime/Cache/Adapter/CacheAdapterFile.php";
require_once $lotusHome . "runtime/Cache/Adapter/CacheAdapterMemcache.php";
require_once $lotusHome . "runtime/Cache/Adapter/CacheAdapterMemcached.php";
require_once $lotusHome . "runtime/Cache/Adapter/CacheAdapterXcache.php";
require_once $lotusHome . "runtime/Cache/QueryEngine/TableDataGateway/CacheTableDataGateway.php";