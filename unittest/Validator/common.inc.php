<?php
$lotusHome = substr(__FILE__, 0, strpos(__FILE__, "unittest"));
require_once $lotusHome . "runtime/Config.php";
require_once $lotusHome . "runtime/Store.php";
require_once $lotusHome . "runtime/StoreMemory.php";
require_once $lotusHome . "runtime/Validator/Validator.php";
require_once $lotusHome . "runtime/Validator/ValidatorDtd.php";

/**
 * 用这个类把LtValidator的public属性和方法暴露出来测试
 */
class LtValidatorProxy extends LtValidator
{
	public $conf;

	public function __construct()
	{
		parent::__construct();
	}
	
	public function __get($prop)
	{
		if (isset($this->$prop))
		{
			return $this->$prop;
		}
	}

	public function _ban($value, $ruleValue)
	{
		return parent::_ban($value, $ruleValue);
	}

	public function _mask($value, $ruleValue)
	{
		return parent::_mask($value, $ruleValue);
	}

	public function _equal_to($value, $ruleValue)
	{
		return parent::_equal_to($value, $ruleValue);
	}

	public function _max_length($value, $ruleValue)
	{
		return parent::_max_length($value, $ruleValue);
	}

	public function _min_length($value, $ruleValue)
	{
		return parent::_min_length($value, $ruleValue);
	}

	public function _max_value($value, $ruleValue)
	{
		return parent::_max_value($value, $ruleValue);
	}

	public function _min_value($value, $ruleValue)
	{
		return parent::_min_value($value, $ruleValue);
	}

	public function _min_selected($value, $ruleValue)
	{
		return parent::_min_selected($value, $ruleValue);
	}

	public function _max_selected($value, $ruleValue)
	{
		return parent::_max_selected($value, $ruleValue);
	}

	public function _required($value, $ruleValue)
	{
		return parent::_required($value, $ruleValue);
	}

}