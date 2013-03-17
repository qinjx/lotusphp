<?php
/**
 * captcha
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: Captcha.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * 验证码
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\Captcha
 */
class LtCaptcha
{
	/** @var LtConfig config handle */
	public $configHandle;
	
	/** @var LtStore store handle */
	public $storeHandle;

	/** @var LtCaptchaImageEngine or other your defined */
	public $imageEngine;

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
		if (!is_object($this->storeHandle))
		{
			$this->storeHandle = new LtStoreFile;
			$this->storeHandle->prefix = 'LtCaptcha-seed-';
			$this->storeHandle->init();
		}
	}

	/**
	 * get image resource
	 * @param string $seed
	 * @return boolean
	 */
	public function getImageResource($seed)
	{
		if (empty($seed))
		{
			trigger_error("empty seed");
			return false;
		}
		if (!is_object($this->imageEngine))
		{
			/** @var string */
			$imageEngine = $this->configHandle->get("captcha.image_engine");
			if ($imageEngine)
			{
				if (class_exists($imageEngine))
				{
					$this->imageEngine = new $imageEngine;
					$this->imageEngine->conf = $this->configHandle->get("captcha.image_engine_conf");
				}
				else
				{
					trigger_error("captcha.image_engine : $imageEngine not exists");
				}
			}
			else
			{
				trigger_error("empty captcha.image_engine");
				return false;
			}
		}
		$word = $this->generateRandCaptchaWord($seed);
		$this->storeHandle->add($seed, $word);
		return $this->imageEngine->drawImage($word);
	}

	/**
	 * verify
	 * @param string $seed
	 * @param string $userInput
	 * @return boolean
	 */
	public function verify($seed, $userInput)
	{
		/** @var string */
		$word = $this->storeHandle->get($seed);
		if ($word)
		{
			$this->storeHandle->del($seed);
			return $userInput === $word;
		}
		else
		{
			return false;
		}
	}

	/**
	 * generate rand captcha word
	 * @return string
	 */
	protected function generateRandCaptchaWord()
	{
		$allowChars = $this->configHandle->get("captcha.allow_chars");
		$length = $this->configHandle->get("captcha.length");
		$allowedSymbolsLength = strlen($allowChars) - 1;
		$captchaWord = "";
		for ($i = 0; $i < $length; $i ++)
		{
			$captchaWord .= $allowChars[mt_rand(0, $allowedSymbolsLength)];
		}
		return $captchaWord;
	}
}
