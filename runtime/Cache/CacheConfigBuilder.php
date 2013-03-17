<?php
/**
 * Cache Config Builder
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: CacheConfigBuilder.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * 缓存 配置工具
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\Cache
 */
class LtCacheConfigBuilder
{
	/** @var array servers */
	protected $servers = array();

	/** @var array default config */
	protected $defaultConfig = array(
		"adapter"    => "phps",     //apc,xcach,ea; file, phps; memcached
	//"prefix"     => ""
	//"host"       => "localhost", //some ip, hostname
	//"port"       => 3306,
	);

	/**
	 * 单台缓存服务器
	 * @param array $hostConfig
	 */
	public function addSingleHost($hostConfig)
	{
		$this->addHost("group_0", "node_0", "master", $hostConfig);
	}

	/**
	 * 添加缓存服务器
	 * @param string $groupId
	 * @param string $nodeId
	 * @param string $role
	 * @param array $hostConfig
	 */
	public function addHost($groupId, $nodeId = "node_0", $role = "master", $hostConfig = array())
	{
		if (isset($this->servers[$groupId][$nodeId][$role]))
		{//以相同role的第一个host为默认配置
			$ref = $this->servers[$groupId][$nodeId][$role][0];
		}
		else if ("slave" == $role && isset($this->servers[$groupId][$nodeId]["master"]))
		{//slave host以master的第一个host为默认配置
			$ref = $this->servers[$groupId][$nodeId]["master"][0];
		}
		else if (isset($this->servers[$groupId]) && count($this->servers[$groupId]))
		{//以本group第一个node的master第一个host为默认配置
			$refNode = key($this->servers[$groupId]);
			$ref = $this->servers[$groupId][$refNode]["master"][0];
		}
		else
		{
			if (!isset($hostConfig["adapter"]))
			{
				trigger_error("No db adapter specified");
			}
			$ref = $this->defaultConfig;
		}
		$conf = array_merge($ref, $hostConfig);
		$this->servers[$groupId][$nodeId][$role][] = $conf;
	}

	/**
	 * 查询缓存服务器列表
	 * @return array
	 */
	public function getServers()
	{
		return $this->servers;
	}
}