<?php
/**
 * cookie
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: Cookie.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * cookie
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com> Yi Zhao <zhao5908@gmail.com>
 * @category runtime
 * @package   Lotusphp\Cookie
 */
class LtCookie
{
	/** @var LtConfig config handle */
	public $configHandle;
	
	/** @var string secret key */
	private $secretKey;

    /** @var boolean encrypt flag */
    private $disableEncrypt = false;

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
		if (true == $this->configHandle->get("cookie.disable_encrypt")) {
			$this->disableEncrypt = true;
		} else {
			$this->secretKey = $this->configHandle->get("cookie.secret_key");
			if (empty($this->secretKey))
			{
				trigger_error("cookie.secret_key empty");
			}
		}
	}

	/**
	 * Decrypt the encrypted cookie
	 * 
	 * @param string $encryptedText 
	 * @return string 
	 */
	protected function decrypt($encryptedText)
	{
        if (false == $this->disableEncrypt) {
            $key = $this->secretKey;
            $cryptText = base64_decode($encryptedText);
            $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
            $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
            $decryptText = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $cryptText, MCRYPT_MODE_ECB, $iv);
            return trim($decryptText);
        } else {
            return $encryptedText;
        }

	}

	/**
	 * Encrypt the cookie
	 * 
	 * @param string $plainText 
	 * @return string 
	 */
	protected function encrypt($plainText)
	{
        if (false == $this->disableEncrypt) {
            $key = $this->secretKey;
            $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
            $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
            $encryptText = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $plainText, MCRYPT_MODE_ECB, $iv);
            return trim(base64_encode($encryptText));
        } else {
            return $plainText;
        }
	}

	/**
	 * Set cookie value to deleted with $name
	 * 
	 * @param string $name
	 * @param string $path
	 * @param string $domain
	 */
	public function delCookie($name, $path = '/', $domain = null)
	{
		if (isset($_COOKIE[$name]))
		{
			if (is_array($_COOKIE[$name]))
			{
				foreach($_COOKIE[$name] as $k => $v)
				{
					setcookie($name . '[' . $k . ']', '', time() - 86400, $path, $domain);
				}
			}
			else
			{
				setcookie($name, '', time() - 86400, $path, $domain);
			}
		}
	}

	/**
	 * Get cookie value with $name
	 * 
	 * @param string $name 
	 * @return string|array 
	 */
	public function getCookie($name)
	{
		$ret = null;
		if (isset($_COOKIE[$name]))
		{
			if (is_array($_COOKIE[$name]))
			{
				$ret = array();
				foreach($_COOKIE[$name] as $k => $v)
				{
					$v = $this->decrypt($v);
					$ret[$k] = $v;
				}
			}
			else
			{
				$ret = $this->decrypt($_COOKIE[$name]);
			}
		}
		return $ret;
	}

	/**
	 * Set cookie
	 * 
	 * @param string $name
	 * @param string|array $value
	 * @param int $expire
	 * @param string $path
	 * @param string $domain
	 * @param int $secure
	 */
	public function setCookie($name, $value = '', $expire = null, $path = '/', $domain = null, $secure = 0)
	{
		if (is_array($value))
		{
			foreach($value as $k => $v)
			{
				$v = $this->encrypt($v);
				setcookie($name . '[' . $k . ']', $v, $expire, $path, $domain, $secure);
			}
		}
		else
		{
			$value = $this->encrypt($value);
			setcookie($name, $value, $expire, $path, $domain, $secure);
		}
	}
}
