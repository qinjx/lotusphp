<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'common.inc.php';

$opcodeCacheAdapter = $_REQUEST['adapter'];
$operation = $_REQUEST['operation'];
$tableName = $_REQUEST['table_name'];
$key = $_REQUEST['key'];

/**
 * 构造缓存配置
 */
$ccb = new LtCacheConfigBuilder;
$ccb->addSingleHost(array('adapter' => $opcodeCacheAdapter,
		));

/**
 * 实例化组件入口类
 */
$cache = new LtCache;
$cache->configHandle->addConfig(array('cache.servers' => $ccb->getServers()));
$cache->init();

/**
 * 初始化完毕, 测试其效果, 使用不同的tableName防止key冲突
 */
$ch = $cache->getTDG($tableName);

switch ($operation)
{
	case 'add':
	case 'update':
		$value = $_REQUEST['value'];
		if (isset($_REQUEST['ttl']))
		{
			$ttl = $_REQUEST['ttl'];
			$result = $ch->$operation($key, $value, $ttl);
		}
		else
		{
			$result = $ch->$operation($key, $value);
		}
		break;
	case 'get':
	case 'del':
		$result = $ch->$operation($key);
		break;
}
echo serialize($result);
