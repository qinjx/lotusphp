<?php

/**
 * The Action class
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: Action.php 966 2012-12-20 02:35:33Z talkativedoggy@gmail.com $
 */

/**
 * The Action class
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\MVC
 * @abstract
 */
abstract class LtAction
{
	/** @var LtContext The context object */
	public $context;
	
	/** @var LtConfig config handle*/
	public $configHandle;
	
	/** @var string view dir */
	public $viewDir;
	
	/** @var string view template dir */
	public $viewTplDir;
	
	/** @var boolean is view template auto compile */
	public $viewTplAutoCompile;
	
	/** @var string layour dir */
	public $layoutDir;
	
	/** @var string template name */
	public $template;
	
	/** @var string application dir */
	public $appDir;
	
	/** @var string project dir */
	public $projDir;

	/** @var array The dtd config for validator */
	protected $dtds = array();

	/** @var array The Access Control List */
	protected $acl;

	/** @var array The current user's roles */
	protected $roles = array();

	/** @var boolean A flag to indicate if subclass call LtAction::__construct() */
	protected $constructed = false;

	/** @var string The response type */
	protected $responseType = "html";

	/** @var int code */
	protected $code;

	/** @var string message */
	protected $message;

	/** @var array data */
	public $data;

	/** @var LtView|LtTemplateView view instance */
	protected $view;

	/** @var string layout name */
	protected $layout;

	/**
	 * The constructor function, initialize the URI property
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
			trigger_error('SUBCLASS_NOT_CALL_PARENT_CONSTRUCTOR');
		}
		$this->afterConstruct();
		$validateResult = $this->validateInput();
		if (0 == $validateResult["error_total"])
		{
			if ($this->checkPrivilege())
			{
				$this->beforeExecute();
				$this->execute();
			}
			else
			{
				$this->code = 403;
				$this->message = "Access denied";
			}
		}
		else
		{
			$this->code = 407;
			$this->message = "Invalid input";
			$this->data['error_messages'] = $validateResult["error_messages"];
		}
		$this->writeResponse();
	}

	/**
	 * Do something after subClass::__construct().
	 */
	protected function afterConstruct()
	{

	}

	/**
	 * Validate the data from client
	 * 
	 * @return array 
	 */
	protected function validateInput()
	{
		$validateResult = array("error_total" => 0, "error_messages" => array());
		if (!empty($this->dtds) && class_exists('LtValidator'))
		{
			$validator = new LtValidator;
			$validator->init();
			foreach ($this->dtds as $variable => $dtd)
			{
				$from = isset($dtd->from) ? $dtd->from : 'request';

				foreach ($dtd->rules as $ruleKey => $ruleValue)
				{
					if ($ruleValue instanceof LtConfigExpression)
					{
						eval('$_ruleValue = ' . $ruleValue->__toString());
						$dtd->rules[$ruleKey] = $_ruleValue;
					}
				}
				$error_messages = $validator->validate($this->context->$from($variable), $dtd);
				if (!empty($error_messages))
				{
					$validateResult['error_total'] ++;
					$validateResult['error_messages'][$variable] = $error_messages;
				}
			}
		}
		return $validateResult;
	}

	/**
	 * Check if current user have privilege to do this
	 * 
	 * @return boolen 
	 */
	protected function checkPrivilege()
	{
		$allow = true;
		if (!empty($this->roles) && class_exists('LtRbac'))
		{
			$module = $this->context->uri["module"];
			$action = $this->context->uri["action"];
			$roles = array_merge(array("*"), $this->roles);
			$rbac = new LtRbac();
			$rbac->init();
			$allow = $rbac->checkAcl($roles, "$module/$action");
		}
		return $allow;
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
				exit; //
				break;
			case 'tpl':
				if (null === $this->view)
				{
					$this->view = new LtTemplateView;
				}
				$this->view->configHandle = $this->configHandle;
				$this->view->component = false; // 是否组件
				$this->view->context = $this->context;
				$this->view->code = $this->code;
				$this->view->message = $this->message;
				$this->view->data = $this->data;
				$this->view->layoutDir = !empty($this->layoutDir) ? $this->layoutDir : $this->viewDir . "layout/";
				$this->view->layout = $this->layout;
				$this->view->templateDir = $this->viewDir;
				$this->view->compiledDir = $this->viewTplDir;
				$this->view->autoCompile = $this->viewTplAutoCompile;
				if (empty($this->template))
				{/*
				  * 兼容end-user手工输入的大小写不区分的module和action名字
				  * 如module=User&action=Login, module=user&action=login都会对应到user-login这个view template
				  */
					$this->template = strtolower($this->context->uri["module"]) . "-" . strtolower($this->context->uri["action"]);
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
				$this->view->configHandle = $this->configHandle;
				$this->view->context = $this->context;
				$this->view->code = $this->code;
				$this->view->message = $this->message;
				$this->view->data = $this->data;
				$this->view->layoutDir = !empty($this->layoutDir) ? $this->layoutDir : $this->viewDir . "layout/";
				$this->view->layout = $this->layout;
				$this->view->templateDir = $this->viewDir;
				if (empty($this->template))
				{
					$this->template = $this->template = strtolower($this->context->uri["module"]) . "-" . strtolower($this->context->uri["action"]);;
				}
				$this->view->template = $this->template;
				$this->view->render();
				break;
		}
	}
}
