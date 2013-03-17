<?php
/**
 * Cache Adapter Factory
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: CacheAdapterFactory.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * 缓存 适配器工厂
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\Cache
 */
class LtCacheAdapterFactory
{
	/**
	 * 返回一个连接适配器实例
	 * @param string $adapter
	 * @return null|LtCacheAdapter adapterClassName
	 */
	public function getConnectionAdapter($adapter)
	{	
		$adapterClassName = "LtCacheAdapter" . ucfirst($adapter);
		if(!class_exists($adapterClassName))
		{
			trigger_error("Invalid adapter: $adapter");
			return null;
		}
		return new $adapterClassName;
	}
}