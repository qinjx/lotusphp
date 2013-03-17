<?php
/**
 * 加工工厂类由开发者自行开发，继承自这个类
 * DB QueryEngine SqlMap LtAbstractDbSqlMapFilterObject
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: AbstractDbSqlMapFilterObject.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * abstract LtAbstractDbSqlMapFilterObject
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\DB\QueryEngine
 * @subpackage SqlMap
 * @abstract
 */
abstract class LtAbstractDbSqlMapFilterObject {

	/** @var array|object query()方法返回的结果集，用于加工的原料 */
	public $result;

	/**
	 * 需要被继承，实现逻辑的操作类，输入query()方法返回的结果集
	 * 经过处理后返回开发者定义的对象或结构
	 * @abstract
	 */
	abstract protected function process();
}

