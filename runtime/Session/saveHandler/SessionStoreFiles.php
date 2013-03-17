<?php
/**
 * The Session file
 * @author Yi Zhao <zhao5908@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: SessionStoreFiles.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * The Session file
 * @author Yi Zhao <zhao5908@gmail.com>
 * @category runtime
 * @package   Lotusphp\Session
 * @subpackage saveHandler
 */
class LtSessionFiles
{
	/** @var string session save path */
	public $sessionSavePath;

	/**
	 * init
	 */
	public function init()
	{
		ini_set('session.save_handler', 'files');
		if (empty($this->sessionSavePath))
		{
			$this->sessionSavePath = '/tmp/Lotusphp.session';			
		}
		if (!is_dir($this->sessionSavePath))
		{
			if (!@mkdir($this->sessionSavePath, 0777, true))
			{
				trigger_error("Can not create $this->sessionSavePath");
			}
		}
		ini_set('session.save_path', $this->sessionSavePath);
		session_start();
	}
}