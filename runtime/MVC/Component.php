<?php

/**
 * The Component class
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: Component.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * The Component class
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\MVC
 * @abstract
 */
abstract class LtComponent
{
	/** @var object The context object */
	public $context;

	/** @var string view dir */
	public $viewDir;
	
	/** @var string view template dir */
	public $viewTplDir;
	
	/** @var boolean is view template auto compile */
	public $viewTplAutoCompile;
	
	/** @var boolean A flag to indicate if subclass call LtComponent::__construct() */
	public $constructed = false;
	
	/** @var string The response type */
	protected $responseType = "html";

	/** @var int code Result properties */
	protected $code;

	/** @var string message */
	protected $message;

	/** @var array data */
	public $data;

	/** @var LtView|LtTemplateView view instance */
	protected $view;

	/** @var string layour name */
	protected $layout;

	/**
	 * The constructor function
	 */
	public function __construct()
	{
		$this->constructed = true;
	}
	
	/**
	 * execute chain
	 */
	public function executeChain()
	{
		if (!$this->constructed)
		{
			//DebugHelper::debug('SUBCLASS_NOT_CALL_PARENT_CONSTRUCTOR', array('class' => $actionClassName));
		}
		$this->afterConstruct();
		$this->beforeExecute();
		$this->execute();
		$this->writeResponse();
	}

	/**
	 * after construct
	 */
	protected function afterConstruct()
	{

	}
	/**
	 * Do something before subClass::execute().
	 */
	protected function beforeExecute()
	{
	}

	/**
	 * execute
	 */
	protected function execute()
	{
	}

	/**
	 * write response
	 */
	protected function writeResponse()
	{
		switch ($this->responseType)
		{
			//去除这里的所有方法，因为之前已经将Component模板编译，此处如果再编译，就会造成二次编译
			case 'json':
			case 'tpl':
			case 'html':
			case 'wml':
			default:
		}
	}
}
