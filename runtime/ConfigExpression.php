<?php
/**
 * ConfigExpression
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: ConfigExpression.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * LtConfigExpression
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package  Lotusphp\Config
 */
class LtConfigExpression
{
	/** @var string expression */
	private $_expression;
	/** @var boolean auto retrived */
	public $autoRetrived;
	
	/**
	 * constructor
	 * @param string $string
	 * @param boolean $autoRetrived
	 */
	public function __construct($string, $autoRetrived = true)
	{
		$this->_expression = (string) $string;
		$this->autoRetrived = $autoRetrived;
	}
	
	/**
	 * toString
	 * @return string
	 */
	public function __toString()
	{
		return $this->_expression;
	}
}