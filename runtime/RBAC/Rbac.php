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
 * @todo init()时转换数组，checkAcl()时使用isset()代替in_array()提升性能
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
					if (isset($this->acl[$operation]['*']) && in_array($resource, $this->acl[$operation]['*']))
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
                        /**
                         * to check if [module / *] or [* / action] or [* / *] is allowed
                         */
                        $tmpArray = explode('/', trim($resource, '/'));
                        foreach (array($tmpArray[0] . "/*", "*/" . $tmpArray[1], "*/*") as $tmp)
						{
							if (in_array($tmp, $this->acl[$operation][$role])) 
							{
								$allow = "allow" == $operation ? true : false;
								break;
							}
						}
					}
				}
			}
		}
		return $allow;
	}
}
