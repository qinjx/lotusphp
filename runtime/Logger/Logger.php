<?php
/**
 * Logger
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: Logger.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * Logger
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\Logger
 */
class LtLogger
{
	/** @var array config */
	public $conf = array(
		"separator" => "\t",
		"log_file" => ""
	);

	/** @var resource file handle */
	private $fileHandle;

	/**
	 * get file handle
	 * @return resource
	 */
	protected function getFileHandle()
	{
		if (null === $this->fileHandle)
		{
			if (empty($this->conf["log_file"]))
			{
				trigger_error("no log file spcified.");
			}
			$logDir = dirname($this->conf["log_file"]);
			if (!is_dir($logDir))
			{
				mkdir($logDir, 0777, true);
			}
			$this->fileHandle = fopen($this->conf["log_file"], "a");
		}
		return $this->fileHandle;
	}

	/**
	 * log
	 * @param array|string $logData
	 * @return boolean
	 */
	public function log($logData)
	{
		if ("" == $logData || array() == $logData)
		{
			return false;
		}
		if (is_array($logData))
		{
			$logData = implode($this->conf["separator"], $logData);
		}
		$logData = $logData . PHP_EOL;
		return fwrite($this->getFileHandle(), $logData);
	}
}