<?php
/**
 * Config
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: Config.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * 配置类
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package Lotusphp\Config
 */
class LtConfig
{
	/**
	 * LtStore接口实例句柄
	 * @var LtStore
	 */
	public $storeHandle;

	/**
	 * construct
	 */
	public function __construct()
	{
		if (!is_object($this->storeHandle))
		{
			$this->storeHandle = new LtStoreMemory;
		}
	}

	/**
	 * init
	 */
	public function init()
	{
		//don't remove me, I am the placeholder
	}

	/**
	 * 根据名称读取配置内容
	 * @param string $key
	 * @return mixed
	 */
	public function get($key)
	{
		$storedConfig = $this->storeHandle->get($key);
		if ($storedConfig instanceof LtConfigExpression)
		{
			$str = $storedConfig->__toString();
			if ($storedConfig->autoRetrived)
			{
				eval("\$value=$str;");
				return $value;
			}
			else
			{
				return $str;
			}
		}
		else
		{
			return $storedConfig;
		}
	}

	/**
	 * 警告
	 * 这里会包含两个用户定义的配置文件，为了不和配置文件里的变量名发生重名
	 * 本方法不定义和使用变量名
	 * @param string $configFile
	 */
	public function loadConfigFile($configFile)
	{
		if (0 == $this->storeHandle->get(".config_total"))
		{
			if (null === $configFile || !is_file($configFile))
			{
				trigger_error("no config file specified or invalid config file");
			}
			$this->conf = include($configFile);
			if (!is_array($this->conf))
			{
				trigger_error("config file do NOT return array: $configFile");
			}
			elseif (!empty($this->conf))
			{
				if (0 == $this->storeHandle->get(".config_total"))
				{
					$this->storeHandle->add(".config_total", 0);
				}
				$this->addConfig($this->conf);
			}
		}
	}

	/**
	 * 保存或者更新配置
	 * @param array $configArray
	 */
	public function addConfig($configArray)
	{
		foreach($configArray as $key => $value)
		{
			if (!$this->storeHandle->update($key, $value))
			{
				if ($this->storeHandle->add($key, $value))
				{
					$this->storeHandle->update(".config_total", $this->storeHandle->get(".config_total") + 1, 0);
				}
			}
		}
	}
}
