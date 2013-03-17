<?php
/**
 * The Session class
 * @author Yi Zhao <zhao5908@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: Session.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * The Session class
 * @author Yi Zhao <zhao5908@gmail.com>
 * @category runtime
 * @package   Lotusphp\Session
 * @todo complete session code
 */
class LtSession
{
	/** @var LtConfig config handle */
	public $configHandle;
	
	/** @var boolean is session start */
	protected static $started = false;

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
		$sessionSaveHandle = $this->configHandle->get('session.save_handler');
		if(empty($sessionSaveHandle))
		{
			$sessionSaveHandle = 'files';
		}
		if(!self::$started)
		{
			$sessionClass = 'LtSession'.ucfirst($sessionSaveHandle);
			if(!class_exists($sessionClass))
			{
				trigger_error("$sessionClass Not Found!");
			}
			else
			{
				$session = new $sessionClass();
				$session->sessionSavePath = $this->configHandle->get('session.save_path');
				//session.name
				//session.gc_maxlifetime
				$session->init();
				self::$started = true;
			}
		}
	}
}
