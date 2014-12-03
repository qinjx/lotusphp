<?php
/**
 * Cache
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: Cache.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * 缓存
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\Cache
 */
class LtCache
{
	/** @var LtConfig config handle */
	public $configHandle;

	/** @var string group */
	public $group;
	
	/**  @var string node */
	public $node;

	/** @var LtCacheHandle cache handle */
	protected $ch;

	/**
	 * constructor
	 */
	public function __construct()
	{
		if (! $this->configHandle instanceof LtConfig)
		{
			if (class_exists("LtObjectUtil", false))
			{
				$this->configHandle = LtObjectUtil::singleton("LtConfig");
			}
			else
			{
				$this->configHandle = new LtConfig;
			}
		}
	}

	/**
	 * init
	 * @return void 
	 */
	public function init()
	{
		$this->ch = new LtCacheHandle;
		$this->ch->configHandle = $this->configHandle;
		$this->ch->init();
		$this->ch->group = $this->getGroup();
		$this->ch->node = $this->getNode();
	}

	/**
	 * 表数据入口
	 * @param string $tableName
	 * @return \LtCacheTableDataGateway
	 */
	public function getTDG($tableName)
	{
		$tdg = new LtCacheTableDataGateway;
		$tdg->tableName = $tableName;
		$tdg->ch = $this->ch;
		return $tdg;
	}

	/**
	 * 变更节点
	 * @param string $node
	 */
	public function changeNode($node)
	{
		$this->node = $node;
		$this->ch->node = $node;
	}

	/**
	 * 查询当前 组 名称
	 * @return boolean
	 */
	protected function getGroup()
	{
		if ($this->group)
		{
			return $this->group;
		}
		$servers = $this->configHandle->get("cache.servers");
		if (1 == count($servers))
		{
			return key($servers);
		}
		return false;
	}

	/**
	 * 查询当前 节点 名称
	 * @return boolean
	 */
	protected function getNode()
	{
		if ($this->node)
		{
			return $this->node;
		}
		$servers = $this->configHandle->get("cache.servers");
		if (1 == count($servers[$this->getGroup()]))
		{
			return key($servers[$this->getGroup()]);
		}
		return false;
	}
}