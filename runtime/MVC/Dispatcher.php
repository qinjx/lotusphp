<?php

/**
 * The Dispatcher class
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: Dispatcher.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * The Dispatcher class
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\MVC
 */
class LtDispatcher
{
	/** @var LtConfig config handle */
	public $configHandle;
	
	/** @var string view dir */
	public $viewDir;
	
	/** @var string view template dir */
	public $viewTplDir;
	
	/** @var boolean is view template auto compile */
	public $viewTplAutoCompile;
	
	/** @var array data */
	public $data;
	
	/** @var string application dir */
	public $appDir;
	
	/** @var string project dir */
	public $projDir;

	/**
	 * construct
	 */
	public function __construct()
	{
	}

	/**
	 * Disptach the module/action calling.
	 *
	 * @param string $module
	 * @param string $action
	 * @param LtContext $context
	 * @return void
	 * @todo allow one action dispatch another action
	 */
	public function dispatchAction($module, $action, $context = null)
	{
		$this->_dispatch($module, $action, $context);
	}

	/**
	 * Disptach the module/component calling.
	 *
	 * @param string $module
	 * @param string $component
	 * @param LtContext $context
	 * @return void
	 */
	public function dispatchComponent($module, $component, $context = null)
	{
		$cloneOfContext = clone $context;
		$this->_dispatch($module, $component, $cloneOfContext, "Component");
	}

	/**
	 * dispatch
	 * @param string $module
	 * @param string $action
	 * @param LtContext $context
	 * @param string $classType
	 */
	protected function _dispatch($module, $action, $context = null, $classType = "Action")
	{
		$classType = ucfirst($classType);
		$actionClassName = $module . $action . $classType;
		if (!class_exists($actionClassName))
		{
			$this->error_404($actionClassName);
			//DebugHelper::debug("{$classType}_CLASS_NOT_FOUND", array(strtolower($classType) => $action));
			//trigger_error("{$actionClassName} CLASS NOT FOUND! module={$module} action={$action} classType={$classType}");
		}
		else
		{
			if (!($context instanceof LtContext))
			{
				$newContext = new LtContext;
			}
			else
			{
				$newContext = clone $context;
			}
			$newContext->uri['module'] = $module;
			$newContext->uri[strtolower($classType)] = $action;
			$actionInstance = new $actionClassName();
			$actionInstance->configHandle = $this->configHandle;
			$actionInstance->context = $newContext;
			$actionInstance->viewDir = $this->viewDir;
			$actionInstance->viewTplDir = $this->viewTplDir; // 模板编译目录
			$actionInstance->viewTplAutoCompile = $this->viewTplAutoCompile;
			$actionInstance->appDir = $this->appDir;
			$actionInstance->projDir = $this->projDir;
			$actionInstance->executeChain();
			$this->data = $actionInstance->data;
		}
	}
	
	/**
	 * error 404
	 */
	protected function error_404($actionClassName)
	{
		header("HTTP/1.0 404 Not Found");
		header("Status: 404 Not Found");
		if ($this->configHandle instanceof LtConfig)
		{
			$filename = $this->configHandle->get('error_404');
			if(is_file($filename))
			{
				include $filename;
				exit();
			}
		}
		// 必需大于 512 bytes，否则404在某些浏览器中不显示
		echo "<!DOCTYPE html ><html><head><title>Error 404</title></head><body>Action Class Not Found: $actionClassName
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
            </body></html>";
		exit();
	}
}
