<?php
/**
 * The Rbac class
 * @author Yi Zhao <zhao5908@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: Rbac.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * The Rbac class
 * @author Yi Zhao <zhao5908@gmail.com>
 * @category runtime
 * @package   Lotusphp\RBAC
 */
class LtRbac {

	/** @var LtConfig config handle */
	public $configHandle;

	/** @var array acl */
	protected $acl; 

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
		$this->acl = $this->configHandle->get('rbac.acl');
	}

	/**
	 * check acl
	 * @param array $roles
	 * @param string $resource
	 * @return boolean
	 */
	public function checkAcl($roles, $resource)
	{
		$allow = false;
		// deny priority
		foreach (array("allow", "deny") as $operation) 
		{
			foreach($roles as $role) 
			{
				if (isset($this->acl[$operation][$role])) 
				{
					// everyone *
					if (in_array($resource, $this->acl[$operation]['*'])) 
					{
						$allow = "allow" == $operation ? true : false;
						break;
					} 
					if (in_array($resource, $this->acl[$operation][$role])) 
					{
						$allow = "allow" == $operation ? true : false;
						break;
					}
					else 
					{
						$res = explode('/', trim($resource, '/'));
						for ($i = count($res)-1; $i >= 0; $i--) 
						{
							$res[$i] = '*';
							$tmp = implode('/', $res);
							if (in_array($tmp, $this->acl[$operation][$role])) 
							{
								$allow = "allow" == $operation ? true : false;
								break;
							}
							unset($res[$i]);
						}
					}
				}
			}
		}
		return $allow;
	}
/*
	private function __set($p,$v)
	{
		$this->$p = $v;
	}

	private function __get($p)
	{
		if(isset($this->$p))
		{
			return($this->$p);
		}
		else
		{
			return(NULL);
		}
	}
*/
}
