<?php
/**
 * Store
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: Store.php 969 2013-01-10 07:52:33Z talkativedoggy@gmail.com $
 */

/**
 * LtStore Interface
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package Lotusphp\Store
 * @todo 增加setNameSpace()方法取代LtStore->prefix=""
 */
Interface LtStore
{
    /**
     * init method
     */
    public function init();

	/**
	 * add
	 * @param string $key
	 * @param string|array $value
	 */
	public function add($key, $value);

	/**
	 * del
	 * @param string $key
	 */
	public function del($key);

	/**
	 * get
	 * @param string $key
	 * @return mixed | null
	 */
	public function get($key);

	/**
	 * update
	 * @param string $key
	 * @param string|array $value
	 */
	public function update($key, $value);
}