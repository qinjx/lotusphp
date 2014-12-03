<?php
/**
 * The View class
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: View.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * The View class
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\MVC
 */
class LtView
{
	/** @var LtConfig config handle */
	public $configHandle;
	
	/** @var string layout dir */
	public $layoutDir;

	/** @var string template dir */
	public $templateDir;

	/** @var string layout name*/
	public $layout;

	/** @var string template name such as module-action */
	public $template;

	/**
	 * render
	 */
	public function render()
	{
        $this->layoutDir = rtrim($this->layoutDir, '\\/') . DIRECTORY_SEPARATOR ;
        $this->templateDir = rtrim($this->templateDir, '\\/') . DIRECTORY_SEPARATOR;
		if (!empty($this->layout))
		{
			include($this->layoutDir . $this->layout . '.php');
		}
		else
		{
			include($this->templateDir . $this->template . '.php');
		}
	}
}
