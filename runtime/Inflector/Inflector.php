<?php
/**
 * Inflector
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: Inflector.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * Inflector
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\Inflector
 */
class LtInflector
{
	/** @var array config */
	public $conf = array("separator" => "_");

	/**
	 * camelize
	 * @param string $uncamelized_words
	 * @return string
	 */
	public function camelize($uncamelized_words)
	{
		$uncamelized_words = $this->conf["separator"] . str_replace($this->conf["separator"] , " ", strtolower($uncamelized_words));
		return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $this->conf["separator"] );
	}

	/**
	 * uncamelize
	 * @param string $camelCaps
	 * @return string
	 */
	public function uncamelize($camelCaps)
	{
		return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $this->conf["separator"] . "$2", $camelCaps));
	}
}