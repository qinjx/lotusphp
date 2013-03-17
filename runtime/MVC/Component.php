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
			case 'json':
				echo json_encode(array("code" => $this->code,
						"message" => $this->message,
						"data" => $this->data
						));
				exit;
				break;
			case 'tpl':
				if (null === $this->view)
				{
					$this->view = new LtTemplateView;
				}
				$this->view->component = true; // 是否组件
				$this->view->context = $this->context;
				$this->view->code = $this->code;
				$this->view->message = $this->message;
				$this->view->data = $this->data;
				$this->view->layoutDir = $this->viewDir . "layout/";
				$this->view->layout = $this->layout;
				$this->view->templateDir = $this->viewDir . "component/";
				$this->view->compiledDir = $this->viewTplDir . "component/";
				$this->view->autoCompile = $this->viewTplAutoCompile;
				if (empty($this->template))
				{
					$this->template = $this->context->uri["module"] . "-" . $this->context->uri["action"];
				}
				$this->view->template = $this->template;
				$this->view->render();
				break;

			case 'html':
			case 'wml':
			default:
				if (null === $this->view)
				{
					$this->view = new LtView;
				}
				$this->view->context = $this->context;
				$this->view->code = $this->code;
				$this->view->message = $this->message;
				$this->view->data = $this->data;
				$this->view->layoutDir = $this->viewDir . "layout/";
				$this->view->layout = $this->layout;
				$this->view->templateDir = $this->viewDir . "component/";
				if (empty($this->template))
				{
					$this->template = $this->context->uri["module"] . "-" . $this->context->uri["action"];
				}
				$this->view->template = $this->template;
				$this->view->render();
				break;
		}
	}
}
