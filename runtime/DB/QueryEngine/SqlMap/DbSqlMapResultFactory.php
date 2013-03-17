<?php

/**
 * LtDbSqlMapResultFactory
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: DbSqlMapResultFactory.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * LtDbSqlMapResultFactory
 * 用于加工DB句柄query方法返回的数组
 * 开发者在一次会话中可配置多个Filter
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\DB\QueryEngine
 * @subpackage SqlMap
 */
class LtDbSqlMapResultFactory
{

	/** @var array Filter列表 */
	public $filters;

	/** @var LtConfig config handle */
	public $configHandle;

	/**
	 * init
	 */
	public function init()
	{
		
	}

	/**
	 * 工厂入口，sql map client调用的方法
	 * 在这个方法中调用开发者自定义的
	 * LtAbstractSqlMapFilterObject.process()方法
	 * 可配置多个process方法
	 */
	public function run()
	{
		
	}

}

