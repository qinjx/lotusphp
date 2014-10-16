<?php
/**
 * validatorDtd
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: ValidatorDtd.php 965 2014-09-15 16:21:32 iuyes@qq.com $
 */

/**
 * validatorDtd
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package Lotusphp\Validator
 */
class LtValidatorDtd
{
	/** @var form elements */
	public $form;
	/** @var string label */
	public $label;
	
	/** @var array rules */
	public $rules;
	
	/** @var array messages */
	public $messages;

	/**
	 * construct
	 * @param string $label
	 * @param array $rules
	 * @param array|null $messages
	 */
	public function __construct($form,$label, $rules, $messages = null)
	{
		$this->form = $form;
		$this->label = $label;
		foreach($rules as $key => $rule)
		{
			$this->rules[$key] = $rule;
		}
		if ($messages)
		{
			foreach($messages as $key => $message)
			{
				$this->messages[$key] = $message;
			}
		}
	}
}
