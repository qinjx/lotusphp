<?php
/**
 * validator
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: Validator.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * validator
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\Validator
 */
class LtValidator
{
	/** @var LtConfig config handle */
	public $configHandle;
	
	/** @var array error message */
	protected $errorMessages;

	/**
	 * construct
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
	 */
	public function init()
	{
		$this->errorMessages = $this->configHandle->get('validator.error_messages');
	}

	/**
	 * Validate an element
	 * 
	 * @param mixed $value 
	 * @param array $dtd 
	 * @return array 
	 */
	public function validate($value, $dtd)
	{
		$errorMessages = array();
		$label = $dtd->label;

		if (is_array($dtd->rules) && count($dtd->rules))
		{
			$messages = isset($dtd->messages) ? $dtd->messages : array();
			foreach ($dtd->rules as $key => $val)
			{ 
				// callback_user_function
				if ('callback_' == substr($key, 0, 9))
				{
					$method = substr($key, 9); 
					// 定义了过程函数
					if (function_exists($method))
					{
						if (!$method($value, $dtd->rules[$key]))
						{
							if (isset($this->errorMessages[$key]))
							{
								$messages[$key] = $this->errorMessages[$key];
							}
							else
							{
								$messages[$key] = "validator.error_messages[$key] empty";
							}
							$errorMessages[$key] = sprintf($messages[$key], $label, $dtd->rules[$key]);
						}
						continue;
					} 
					// 定义了类方法
					$rc = new ReflectionClass($val);
					if ($rc->hasMethod($method))
					{
						$rcMethod = $rc->getMethod($method);
						if ($rcMethod->isStatic())
						{
							$ret = $rcMethod->invoke(null, $value, $dtd->rules[$key]);
						}
						else
						{ 
							// 非静态方法需要一个实例 有待考虑单例
							$rcInstance = $rc->newInstance();
							$ret = $rcMethod->invoke($rcInstance, $value, $dtd->rules[$key]);
						}
						if (!$ret)
						{
							if (empty($messages[$key]))
							{
								if (isset($this->errorMessages[$key]))
								{
									$messages[$key] = $this->errorMessages[$key];
								}
								else
								{
									$messages[$key] = "validator.error_messages[$key] empty";
								}
							}
							$errorMessages[$key] = sprintf($messages[$key], $label, $dtd->rules[$key]);
						}
						continue;
					}
					continue;
				} 
				// end callback_user_function
				$validateFunction = '_' . $key;
				if ((is_bool($dtd->rules[$key]) || 0 < strlen($dtd->rules[$key])) && !$this->$validateFunction($value, $dtd->rules[$key]))
				{
					if (empty($messages[$key]))
					{
						if (isset($this->errorMessages[$key]))
						{
							$messages[$key] = $this->errorMessages[$key];
						}
						else
						{
							$messages[$key] = "validator.error_messages[$key] empty";
						}
					}
					$errorMessages[$key] = sprintf($messages[$key], $label, $dtd->rules[$key]);
				}
			}
		}
		return $errorMessages;
	}

	/**
	 * ban
	 * @param string $value
	 * @param string $ruleValue
	 * @return boolean
	 */
	protected function _ban($value, $ruleValue)
	{
		return !preg_match($ruleValue, $value);
	}

	/**
	 * mask
	 * @param string $value
	 * @param string $ruleValue
	 * @return boolean
	 */
	protected function _mask($value, $ruleValue)
	{
		return preg_match($ruleValue, $value);
	}

	/**
	 * equal
	 * @param string $value
	 * @param string $ruleValue
	 * @return boolean
	 */
	protected function _equal_to($value, $ruleValue)
	{
		return $value === $ruleValue;
	}

	/**
	 * max length
	 * @param string $value
	 * @param string $ruleValue
	 * @return boolean
	 */
	protected function _max_length($value, $ruleValue)
	{
		return mb_strlen($value) <= $ruleValue;
	}

	/**
	 * min length
	 * @param string $value
	 * @param string $ruleValue
	 * @return boolean
	 */
	protected function _min_length($value, $ruleValue)
	{
		return mb_strlen($value) >= $ruleValue;
	}

	/**
	 * max value
	 * @param int $value
	 * @param int $ruleValue
	 * @return boolean
	 */
	protected function _max_value($value, $ruleValue)
	{
		return $value <= $ruleValue;
	}

	/**
	 * min value
	 * @param int $value
	 * @param int $ruleValue
	 * @return boolean
	 */
	protected function _min_value($value, $ruleValue)
	{
		return $value >= $ruleValue;
	}

	/**
	 * min selected
	 * @param array $value
	 * @param int $ruleValue
	 * @return boolean
	 */
	protected function _min_selected($value, $ruleValue)
	{
		return count($value) >= $ruleValue;
	}

	/**
	 * max selected
	 * @param array $value
	 * @param int $ruleValue
	 * @return boolean
	 */
	protected function _max_selected($value, $ruleValue)
	{
		return count($value) <= $ruleValue;
	}

	/**
	 * required
	 * @param array|string $value
	 * @param boolean $ruleValue
	 * @return boolean
	 */
	protected function _required($value, $ruleValue)
	{
		if (false == $ruleValue)
		{
			return true;
		}
		else
		{
			return is_array($value) && count($value) || strlen($value);
		}
	}
}
