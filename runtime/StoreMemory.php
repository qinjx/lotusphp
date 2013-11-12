<?php
/**
 * StoreMemory
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: StoreMemory.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * LtStore Memory
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package Lotusphp\Store
 */
class LtStoreMemory implements LtStore
{
	/**
	 * 存储配置
	 * @var array
	 */
	protected $stack;

    /**
     * init method
     */
    public function init()
    {
        //do nothing
    }

	/**
	 * add
	 * @param string $key
	 * @param string|array|object $value
	 * @return boolean
	 */
	public function add($key, $value)
	{
		if (isset($this->stack[$key]))
		{
			return false;
		}
		else
		{
			$this->stack[$key] = $value;
			return true;
		}
	}

	/**
	 * del
	 * @param string $key
	 * @return boolean
	 */
	public function del($key)
	{
		if (isset($this->stack[$key]))
		{
			unset($this->stack[$key]);
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * get
	 * @param string $key
	 * @return mixed | null
	 */
	public function get($key)
	{
		return isset($this->stack[$key]) ? $this->stack[$key] : null;
	}

	/**
	 * key不存在返回false
	 * 
	 * @return bool 
	 */
	/**
	 * update
	 * @param string $key
	 * @param string|array|object $value
	 * @return boolean
	 */
	public function update($key, $value)
	{
		if (!isset($this->stack[$key]))
		{
			return false;
		}
		else
		{
			$this->stack[$key] = $value;
			return true;
		}
	}
}
