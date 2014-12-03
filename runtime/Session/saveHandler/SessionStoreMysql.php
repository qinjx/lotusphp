<?php
/**
 * The Session mysql
 * @author iuyes <iuyes@qq.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: SessionStoreMysql.php 964 2014-09-25 14:02:32Z zhao5908@gmail.com $
 */

/**
 * The Session mysql
 * @author  iuyes <iuyes@qq.com>
 * @category runtime
 * @package   Lotusphp\Session
 * @subpackage saveHandler
 */
class LtSessionMysql
{
	/** @var array config */
	public $configHandle;

	/** @var int life time */
	private $lifeTime; //session.gc_maxlifetime
	
	/** @var resource db handle */
	private $db;
	private $dbHandle;
	
	/** @var string teble name */
	private $tableName;
	
	
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
	 * @return boolean
	 */
	public function init()
	{
		$this->lifeTime=$this->configHandle->get('gc_maxlifetime');
		if (empty($this->lifeTime))
		{
			$this->lifeTime = get_cfg_var("session.gc_maxlifetime");
		}
		
		$this->tableName=$this->configHandle->get('table_name');
		if (empty($this->tableName))
		{
			$this->tableName = 'lotus_session';
		}
		
		$this->db = new LtDb;
		$this->db->init();
		$this->dbHandle = $this->db->getDbHandle();
		
		session_set_save_handler(
			array(&$this, 'open'),
			array(&$this, 'close'),
			array(&$this, 'read'),
			array(&$this, 'write'),
			array(&$this, 'destroy'),
			array(&$this, 'gc')
		);
		return true;
	}

	/**
	 * open
	 * @param string $savePath
	 * @param string $sessName
	 * @return boolean
	 */
	public function open($savePath, $sessName)
	{
		return true;
	}

	/**
	 * close
	 */
	public function close()
	{
		$this->gc($this->lifeTime);
	}

	/**
	 * read
	 * @param string $sessID
	 * @return string
	 */
	public function read($sessID)
	{
		$res = $this->dbHandle->query("SELECT session_data AS d FROM $this->tableName WHERE session_id = '$sessID' AND session_expires > " . time());		   
		if (!empty($res))
		{
			return $res[0]['d'];
		}
		else
		{
			return '';
		}
	}

	/**
	 * write
	 * @param string $sessID
	 * @param string|array|object $sessData
	 * @return boolean
	 */
	public function write($sessID, $sessData)
	{
		$newExp = time() + $this->lifeTime;
		$res = $this->dbHandle->query("SELECT * FROM $this->tableName WHERE session_id = '$sessID'");
		if (!empty($res))
		{
			return $this->dbHandle->query("UPDATE $this->tableName SET session_expires = '$newExp',session_data = '$sessData' WHERE session_id = '$sessID'");
		}
		else
		{
			return $this->dbHandle->query("INSERT INTO $this->tableName (session_id,session_expires,session_data)VALUES('$sessID','$newExp','$sessData')");
		}
		return false;
	}

	/**
	 * destroy
	 * @param string $sessID
	 * @return boolean
	 */
	public function destroy($sessID)
	{
		return $this->dbHandle->query("DELETE FROM $this->tableName WHERE session_id = '$sessID'");
	}

	/**
	 * gc
	 * @param int $sessMaxLifeTime
	 * @return boolean
	 */
	public function gc($sessMaxLifeTime)
	{
		return $this->dbHandle->query("DELETE FROM $this->tableName WHERE session_expires < " . time());
	}

	/**
	 * run once
	 * @return boolean
	 */
	public function runOnce()
	{
		return $this->dbHandle->query("CREATE TABLE IF NOT EXISTS $this->tableName (`session_id` char(32) NOT NULL,`session_expires` int(10) NOT NULL DEFAULT '0',`session_data` varchar(500) NOT NULL DEFAULT '',PRIMARY KEY (`session_id`)) ENGINE=InnoDB;");
	}
}
