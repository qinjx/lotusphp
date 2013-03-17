<?php
/**
 * 示例代码
 * <code>
 * class CartDAO extends LtBaseDao
 * {
 *   public function __construct()
 *   {
 *     parent::__construct();
 *     $this->table = $this->db->getTDG('cart');
 *     $this->primaryKey = 'id';
 *   }
 * }
 * </code>
 */
abstract class LtBaseDao
{
	/**
	 *
	 *
	 * @var LtDbTableDataGateway
	 */
	protected $table;

	/**
	 * 主键名称
	 *
	 * @var string
	 */
	protected $primaryKey;

	/**
	 *
	 *
	 * @var LtDbHandle
	 */
	protected $dbh;

	/**
	 *
	 * @var LtDbSqlMapClient
	 */
	protected $smc;

	/**
	 *
	 *
	 * @var LtDb
	 */
	protected $db;

	public function __construct()
	{
		$this->db = LtObjectUtil::singleton('LtDb');

		$this->dbh = $this->db->getDbHandle();

		$this->smc = $this->db->getSqlMapClient();

		//必需在子类赋值
		//$this->table = $db->getTDG('your-table-name');
		//必需在子类赋值
		//$this->primaryKey = 'primary-key';
	}
	
	/**
	 * 使用SqlMapClient
	 * 
	 * 自定义SQL，不受任何限制；SQL语句统一存储在配置文件里，便于DBA审查、管理
	 * 
	 * @param string $mapId
	 * @param array $bind
	 */
	public function smcExecute($mapId, $bind = null)
	{
		return $this->smc->execute($mapId, $bind);
	}
	
	/**
	 * 直接执行SQL, 建议少用。
	 * 
	 * @param string $sql
	 * @param array $bind
	 * @param boolean $forceUseMaster
	 */
	public function query($sql, $bind=null, $forceUseMaster=false)
	{
		return $this->dbh->query($sql, $bind, $forceUseMaster);
	}

	/**
	 * 插入
	 *
	 * @param array $data
	 */
	public function insert($data)
	{
		return $this->table->insert($data);
	}

	/**
	 * 按主键更新
	 *
	 * @param array $data
	 */
	public function updateByPrimaryKey($data)
	{
		$primaryKey = $this->primaryKey;
		$primaryKeyId = $data[$primaryKey];
		unset($data[$primaryKey]);
		return $this->table->update($primaryKeyId, $data);
	}

	/**
	 * 按条件更新
	 *
	 * @param array $data
	 * @example $condition['where'] = array('expression' => 'id < :id', 'value' => array('id' => 10));
	 * @param array $condition
	 */
	public function updateByCondition($data, $condition)
	{
		return $this->table->updateRows($condition, $data);
	}

	/**
	 * 按主键查询
	 *
	 * @param integer $primaryKeyId
	 */
	public function selectByPrimaryKey($primaryKeyId)
	{
		return $this->table->fetch($primaryKeyId);
	}

	/**
	 * 按条件查询
	 *
	 * @example $condition['where'] = array('expression' => 'id < :id', 'value' => array('id' => 10));
	 * @param array $condition
	 */
	public function selectByCondition($condition)
	{
		return $this->table->fetchRows($condition);
	}

	/**
	 * 按主键删除
	 *
	 * @param integer $primaryKeyId
	 */
	public function deleteByPrimaryKey($primaryKeyId)
	{
		return $this->table->delete($primaryKeyId);
	}

	/**
	 * 按条件删除,注意这里不需要where，此处与查询不同。
	 *
	 * @example $condition = array('expression' => 'id < :id', 'value' => array('id' => 10));
	 * @param array $condition
	 */
	public function deleteByCondition($condition)
	{
		return $this->table->deleteRows($condition);
	}

	/**
	 * 按条件统计
	 *
	 * @example $condition['where'] = array('expression' => 'id < :id', 'value' => array('id' => 10));
	 * @param array $condition
	 */
	public function countByCondition($condition)
	{
		return $this->table->count($condition);
	}
}
