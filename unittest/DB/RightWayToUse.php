<?php
/**
 * 本测试文档演示了LtDb的正确使用方法 
 * 按本文档操作一定会得到正确的结果
 * 
 * 使用分布式数据注意事项：
 *   1. 同一节点下相同角色的服务器必须使用同一种数据系统
 *      不能master1用oracle, master2用pgsql
 *   2. 同一节点的master和slave服务器可以使用不同的数据库系统
 *      比如所有master都用oracle,所有slave都用mysql
 *      这种情况下，使用DbHandle和SqlMap查询DB前
 *      必须手工指定DbHandle->role是master还是slave
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class RightWayToUseDb extends PHPUnit_Framework_TestCase
{
	/**
	 * 单机单库用法示例
	 */
	public function testMostUsedWay()
	{
		/**
		 * 配置数据库连接信息
		 */
		$dcb = new LtDbConfigBuilder;
		$dcb->addSingleHost(array("adapter" => "mysql", "username"=>"test", "password" => "", "dbname" => "test"));

		/**
		 * 实例化组件入口类
		 */
		$db = new LtDb;
		$db->configHandle->addConfig(array("db.servers" => $dcb->getServers()));
		$db->init();

		/**
		 * 用法 1： 直接操作数据库
		 * 
		 * 优点：学习成本低，快速入门
		 * 
		 * 适用场景：
		 *     1. 临时写个脚本操作数据库，不想花时间学习LtDb的查询引擎
		 *     2. 只写少量脚本，不是一个完整持续的项目，不需要SqlMap来管理SQL语句
		 */
		$dbh = $db->getDbHandle();
		$dbh->query("DROP TABLE IF EXISTS test_user");
		$dbh->query("
			CREATE TABLE test_user (
			id INT NOT NULL AUTO_INCREMENT,
			name VARCHAR( 20 ) NOT NULL ,
			age INT NOT NULL ,
			PRIMARY KEY ( id ) 
		)");

		/**
		 * 用法 2： 使用Table Gateway查询引擎
		 * 
		 * 优点：自动生成SQL语句
		 * 
		 * 适用场景：
		 *     1. 对数据表进行增简单的删查改操作，尤其是单条数据的操作
		 *     2. 简单的SELECT，动态合成WHERE子句
		 */
		$tg = $db->getTDG("test_user");
		$this->assertEquals(2, $id = $tg->insert(array("id" => 2, "name" => "kiwiphp", "age" => 4)));
		$this->assertEquals(array("id" => 2, "name" => "kiwiphp", "age" => 4), $tg->fetch($id));
		$this->assertEquals(3, $id = $tg->insert(array("name" => "chin", "age" => 28)));
		$this->assertEquals(array(array("id" => 2, "name" => "kiwiphp", "age" => 4),array("id" => 3, "name" => "chin", "age" => 28)), $tg->fetchRows());
		$this->assertEquals(1, $tg->update(3, array("name" => "Qin")));
		$this->assertEquals(array("id" => 3, "name" => "Qin", "age" => 28), $tg->fetch($id));
		$this->assertEquals(2, $tg->count());
		$this->assertEquals(1, $tg->delete(3));
		$this->assertEquals(array(array("id" => 2, "name" => "kiwiphp", "age" => 4)), $tg->fetchRows());
		
		/**
		 * 用法3：使用SqlMapClient
		 * 
		 * 优点：自定义SQL，不受任何限制；SQL语句统一存储在配置文件里，便于DBA审查、管理
		 * 
		 * 适用场景：
		 *     1. Table Gateway无法实现的查询，尤其是复杂SELECT、子查询
		 *     2. 动态传入表名
		 */
		$smc = $db->getSqlMapClient();
		// 准备测试数据
		$dbh->query("INSERT INTO `test_user` (`name`,`age`) VALUE ('SqlMapClient',33)");
		// 实际使用时是从配置文件里获取 sql
		$db->configHandle->addConfig(array("db.sqlmap." . $dbh->group . '.getName' => array("sql"=>"SELECT `name` FROM `test_user` WHERE `age`=33","force_use_master"=>false)));
		$this->assertEquals(array(0 => array("name" => "SqlMapClient")), $smc->execute("getName"));

		// ====================为下面的测试准备数据====================
		$dbh->query("DROP TABLE IF EXISTS test_user");
		$dbh->query("
			CREATE TABLE test_user (
			id INT NOT NULL AUTO_INCREMENT,
			name VARCHAR( 20 ) NOT NULL ,
			age INT NOT NULL ,
			PRIMARY KEY ( id ) 
		)");
		$tg = $db->getTDG("test_user");
		$tg->insert(array("id" => 2, "name" => "kiwiphp", "age" => 4));
		// ======================数据准备完成==========================
	}

	/**
	 * 经过上面的测试,表test_user中有数据
	 * array("id" => 2, "name" => "kiwiphp", "age" => 4)
	 * 下边尝试直接读取这个数据
	 */
	public function testFirstFetch()
	{
		/**
		 * 配置数据库连接信息
		 */
		$dcb = new LtDbConfigBuilder;
		$dcb->addSingleHost(array("adapter" => "mysql", "username"=>"test", "password" => "", "dbname" => "test"));

		/**
		 * 实例化组件入口类
		 */
		$db = new LtDb;
		$db->configHandle->addConfig(array("db.servers" => $dcb->getServers()));
		$db->init();
		$tg = $db->getTDG("test_user");
		$tg->fetch(1); // ===========第一次是fetch操作出错=================
	}

	/**
	 * 测试Mysql
	 */
	public function testMysql()
	{
		$host = array("username"=>"test", "password" => "", "dbname" => "test");
		foreach (array("mysql", "mysqli", "pdo_mysql") as $adapter)
		{
			$host["adapter"] = $adapter;
			/**
			 * 配置数据库连接信息
			 */
			$dcb = new LtDbConfigBuilder;
			$dcb->addSingleHost($host);

			/**
			 * 实例化组件入口类
			 */
			$db = new LtDb;
			$db->configHandle->addConfig(array("db.servers" => $dcb->getServers()));
			$db->init();

			$dbh = $db->getDbHandle();
			foreach (array(
				//array("SQL语句", 参数,  正确结果)
				array("SELECT 'ok'", null, array(0 => array("ok" => "ok"))),
				array("DROP TABLE IF EXISTS test_user", null, true),
				array("USE test", null, true),
				array("CREATE TABLE test_user (
						id INT NOT NULL ,
						name VARCHAR( 20 ) NOT NULL ,
						age INT NOT NULL ,
						PRIMARY KEY ( id ) 
				)", null, true),
				array("ALTER TABLE test_user CHANGE id id INT( 11 ) NOT NULL AUTO_INCREMENT", null, true),
				array("INSERT INTO test_user VALUES (:id, :name, :age)", array("id" => 1, "name" => "lotus", "age" => 5), 1),
				array("UPDATE test_user SET age = :age", array("age" => new LtDbSqlExpression("age+45")), 1),
				array("SELECT * FROM test_user WHERE id = :id", array("id" => 1), array("0" => array("id" => 1, "name" => "lotus", "age" => 50))),
				array("DELETE FROM test_user", null, 1),
				array("SELECT * FROM test_user WHERE id = :id", array("id" => 1), null),
			) as $testData)
			{
				$this->assertEquals($testData[2], $dbh->query($testData[0], $testData[1]));
			}
		}
	}

	/**
	 * 分布式数据库操作测试
	 * 本例演示了垂直切分（多个Group）和水平切分（一个Group下多个节点）
	 * 由于实际测试环境的限制，本例中不同的Group和Node只用dbname来区分，共享一个mysql server
	 *    Group 1：系统数据组，存储系统数据，因为数据较少，只包含一个节点
	 *        Node 1：dbname=sys_data
	 *    Group 2: 用户数据组，存储用户生产的数据，因为数据量可能会大，包含两个节点
	 *        Node 1：dbname=member_1
	 *        Node 2：dbname=member_2
	 */
	public function testDistDb()
	{
		$dcb = new LtDbConfigBuilder;
		/**
		 * 配置系统数据组
		 * 一个节点， 一主零从
		 */
		$dcb->addHost("sys_group", "sys_node_1", "master", array("username"=>"test", "password" => "", "dbname" => "sys_data", "adapter" => "mysql"));

		/**
		 * 配置用户数据组
		 * 两个节点
		 * 每个节点一主零从
		 * 都在同一台机器上，不同节点数据库名不同
		 */
		$dcb->addHost("user_group", "user_node_1", "master", array("username"=>"test", "password" => "", "dbname" => "member_1", "adapter" => "mysql"));
		$dcb->addHost("user_group", "user_node_2", "master", array("dbname" => "member_2"));

		/**
		 * ========== LtDb的第一个实例，仅用于操作sys_group ==========
		 */
		$db1 = new LtDb;
		$db1->configHandle->addConfig(array("db.servers" => $dcb->getServers()));
		$db1->group = "sys_group";
		$db1->init();

		//用DbHandle直接操作数据库
		$dbh1 = $db1->getDbHandle();
		$this->assertEquals(true, $dbh1->query("DROP TABLE IF EXISTS sys_category"));
		$this->assertEquals(true, $dbh1->query("CREATE TABLE sys_category (
			id INT NOT NULL auto_increment,
			name VARCHAR( 20 ) NOT NULL ,
			PRIMARY KEY ( id ) 
		)"));

		//使用Table Gateway查询引擎
		/**
		@todo 当sys_data库不存在时插入到了test库, 这是个bug?
		*/
		$tg1 = $db1->getTDG("sys_category");
		$this->assertEquals(1, $id = $tg1->insert(array("id" => 1, "name" => "PHP")));
		$this->assertEquals(array("id" => 1, "name" => "PHP"), $tg1->fetch($id));

		//使用SqlMapClient
		$smc1 = $db1->getSqlMapClient();

		// 实际使用时是从配置文件里获取 sql
		$db1->configHandle->addConfig(array("db.sqlmap." . $dbh1->group . '.sys.getSysCateTotal' => array("sql"=>"SELECT count(`id`) as 'category_total' FROM `sys_category`","force_use_master"=>false)));

		$this->assertEquals(array(0 => array("category_total" => 1)), $smc1->execute("sys.getSysCateTotal"));

		/**
		 * ========== LtDb的第二个实例，仅用于操作user_group ==========
		 */
		$db2 = new LtDb;
		$db2->configHandle->addConfig(array("db.servers" => $dcb->getServers()));
		$db2->group = "user_group";
		$db2->node = "user_node_1";
		$db2->init();

		//用DbHandle直接操作数据库
		$dbh2 = $db2->getDbHandle();
		$this->assertEquals(true, $dbh2->query("DROP TABLE IF EXISTS user_account"));
		$this->assertEquals(true, $dbh2->query("CREATE TABLE user_account (
			id INT NOT NULL auto_increment,
			username VARCHAR( 20 ) NOT NULL ,
			PRIMARY KEY ( id ) 
		)"));

		//使用Table Gateway查询引擎
		$tg2 = $db2->getTDG("user_account");
		$this->assertEquals(1, $id = $tg2->insert(array("id" => 1, "username" => "lotusphp")));
		$this->assertEquals(array("id" => 1, "username" => "lotusphp"), $tg2->fetch($id));

		/**
		 * 重新设置要操作的节点
		 * 重新设置节点后，DbHanlde和Table Gateway都会去操作新的节点
		 */
		$db2->changeNode("user_node_2");

		//用DbHandle直接操作数据库
		$dbh2 = $db2->getDbHandle();
		$this->assertEquals(true, $dbh2->query("DROP TABLE IF EXISTS user_account"));
		$this->assertEquals(true, $dbh2->query("CREATE TABLE user_account (
			id INT NOT NULL auto_increment,
			username VARCHAR( 20 ) NOT NULL ,
			PRIMARY KEY ( id ) 
		)"));

		//使用Table Gateway查询引擎
		$tg2 = $db2->getTDG("user_account");
		$this->assertEquals(2, $id = $tg2->insert(array("id" => 2, "username" => "talkativedoggy")));
		$this->assertEquals(array("id" => 2, "username" => "talkativedoggy"), $tg2->fetch($id));
		
		//使用Table Gateway查询引擎 Group By 参数
		$tg2 = $db2->getTDG("user_account");
		$this->assertEquals(3, $id = $tg2->insert(array("id" => 3, "username" => "laoliu")));
		$this->assertEquals(4, $id = $tg2->insert(array("id" => 4, "username" => "laoliu")));
		$this->assertEquals(5, $id = $tg2->insert(array("id" => 5, "username" => "laoliu")));
		$this->assertEquals(1, $tg2->fetchRows(array("groupby" => "username")));
		$this->assertEquals(1, $tg2->count(array("groupby" => "username")));
	}

	/**
	 * 测试单机单库的配置方法
	 */
	public function configBuilderDataProvider()
	{
		$singleHost1 = array(
			"password"       => "123456",
			"dbname"         => "test",
			"adapter"        => "mysql",
		);
		$expected1["group_0"]["node_0"]["master"][] = array(
			"host"           => "localhost",
			"port"           => 3306,
			"username"       => "root",
			"password"       => "123456",
			"adapter"        => "mysql",
			"charset"        => "UTF-8",
			"pconnect"       => false,
			"connection_ttl" => 30,
			"dbname"         => null,
			"schema"         => "test",
			"connection_adapter" => "mysql",
		  "sql_adapter"        => "mysql",
		);
		$singleHost2 = array(
			"password"       => "123456",
			"dbname"         => "test",
			"adapter"        => "pdo_dblib",
			"port"           => 1433,
		);
		$expected2["group_0"]["node_0"]["master"][] = array(
			"host"           => "localhost",
			"port"           => 1433,
			"username"       => "root",
			"password"       => "123456",
			"adapter"        => "pdo_dblib",
			"charset"        => "UTF-8",
			"pconnect"       => true,
			"connection_ttl" => 3600,
			"dbname"         => null,
			"schema"         => "test",
			"connection_adapter" => "pdo",
		  "sql_adapter"        => "mssql",
		);
		$singleHost3 = array(
			"password"       => "123456",
			"dbname"         => "test",
			"schema"         => "sys_data",
			"adapter"        => "pdo_pgsql",
			"port"           => 5432,
		);
		$expected3["group_0"]["node_0"]["master"][] = array(
			"host"           => "localhost",
			"port"           => 5432,
			"username"       => "root",
			"password"       => "123456",
			"adapter"        => "pdo_pgsql",
			"charset"        => "UTF-8",
			"pconnect"       => true,
			"connection_ttl" => 3600,
			"dbname"         => "test",
			"schema"         => "sys_data",
			"connection_adapter" => "pdo",
		  "sql_adapter"        => "pgsql",
		);
		return array(
			array($singleHost1, $expected1),
			array($singleHost2, $expected2),
			array($singleHost3, $expected3),
		);
	}

	/**
	 * @dataProvider configBuilderDataProvider
	 */
	public function testConfigBuilder($singleHost, $expected)
	{
		$dcb = new LtDbConfigBuilder;
		$dcb->addSingleHost($singleHost);
		$this->assertEquals($expected, $dcb->getServers());
	}

	/**
	 * 测试分布式数据库的配置方法
	 */
	public function testConfigBuilderDistDb()
	{
		$dcb = new LtDbConfigBuilder;
		/**
		 * 配置系统数据组
		 * 一个节点， 一主两从，分布在三台不同的机器上
		 */
		$dcb->addHost("sys_group", "sys_node_1", "master", array("host" => "10.0.0.1", "password" => "123456", "dbname" => "sys_data", "schema" => "public", "adapter" => "pgsql"));
		$dcb->addHost("sys_group", "sys_node_1", "slave", array("host" => "10.0.0.2", "adapter" => "pdo_pgsql"));
		$dcb->addHost("sys_group", "sys_node_1", "slave", array("host" => "10.0.0.3"));
		
		/**
		 * 配置用户数据组
		 * 两个节点
		 * 每个节点一主一从
		 * 都在同一台机器上，不同节点数据库名不同，主从服务器的端口不同
		 */
		$dcb->addHost("user_group", "user_node_1", "master", array("host" => "10.0.1.1", "password" => "123456", "adapter" => "mysqli", "dbname" => "member_1"));
		$dcb->addHost("user_group", "user_node_1", "slave", array("port" => 3307));
		$dcb->addHost("user_group", "user_node_2", "master", array("dbname" => "member_2"));
		$dcb->addHost("user_group", "user_node_2", "slave", array("port" => 3307));

		/**
		 * 配置交易数据组
		 * 三个节点
		 * 每个节点两台机器互为主从
		 */
		$dcb->addHost("trade_group", "trade_node_1", "master", array("host" => "10.0.2.1", "password" => "123456", "adapter" => "oci", "dbname" => "finance", "schema" => "trade"));
		$dcb->addHost("trade_group", "trade_node_1", "master", array("host" => "10.0.2.2"));
		$dcb->addHost("trade_group", "trade_node_2", "master", array("host" => "10.0.2.3"));
		$dcb->addHost("trade_group", "trade_node_2", "master", array("host" => "10.0.2.4"));
		$dcb->addHost("trade_group", "trade_node_3", "master", array("host" => "10.0.2.5"));
		$dcb->addHost("trade_group", "trade_node_3", "master", array("host" => "10.0.2.6"));

		$this->assertEquals(
		array(
			"sys_group" => array(
				"sys_node_1" => array(
					"master" => array(
						array(
									"host"           => "10.0.0.1",
									"port"           => 5432,
									"username"       => "root",
									"password"       => "123456",
									"adapter"        => "pgsql",
									"charset"        => "UTF-8",
									"pconnect"       => true,
									"connection_ttl" => 3600,
									"dbname"         => "sys_data",
									"schema"         => "public",
									"connection_adapter" => "pgsql",
								  "sql_adapter"        => "pgsql",
						),
					),
					"slave" => array(
						array(
									"host"           => "10.0.0.2",
									"port"           => 5432,
									"username"       => "root",
									"password"       => "123456",
									"adapter"        => "pdo_pgsql",
									"charset"        => "UTF-8",
									"pconnect"       => true,
									"connection_ttl" => 3600,
									"dbname"         => "sys_data",
									"schema"         => "public",
									"connection_adapter" => "pdo",
								  "sql_adapter"        => "pgsql",
						),
						array(
									"host"           => "10.0.0.3",
									"port"           => 5432,
									"username"       => "root",
									"password"       => "123456",
									"adapter"        => "pdo_pgsql",
									"charset"        => "UTF-8",
									"pconnect"       => true,
									"connection_ttl" => 3600,
									"dbname"         => "sys_data",
									"schema"         => "public",
									"connection_adapter" => "pdo",
								  "sql_adapter"        => "pgsql",
						),
					),
				),
			),
			"user_group" => array(
				"user_node_1" => array(
					"master" => array(
						array(
									"host"           => "10.0.1.1",
									"port"           => 3306,
									"username"       => "root",
									"password"       => "123456",
									"adapter"        => "mysqli",
									"charset"        => "UTF-8",
									"pconnect"       => false,
									"connection_ttl" => 30,
									"dbname"         => null,
									"schema"         => "member_1",
									"connection_adapter" => "mysqli",
								  "sql_adapter"        => "mysql",
						),
					),
					"slave" => array(
						array(
									"host"           => "10.0.1.1",
									"port"           => 3307,
									"username"       => "root",
									"password"       => "123456",
									"adapter"        => "mysqli",
									"charset"        => "UTF-8",
									"pconnect"       => false,
									"connection_ttl" => 30,
									"dbname"         => null,
									"schema"         => "member_1",
									"connection_adapter" => "mysqli",
								  "sql_adapter"        => "mysql",
						),
					),
				),
				"user_node_2" => array(
					"master" => array(
						array(
									"host"           => "10.0.1.1",
									"port"           => 3306,
									"username"       => "root",
									"password"       => "123456",
									"adapter"        => "mysqli",
									"charset"        => "UTF-8",
									"pconnect"       => false,
									"connection_ttl" => 30,
									"dbname"         => null,
									"schema"         => "member_2",
									"connection_adapter" => "mysqli",
								  "sql_adapter"        => "mysql",
						),
					),
					"slave" => array(
						array(
									"host"           => "10.0.1.1",
									"port"           => 3307,
									"username"       => "root",
									"password"       => "123456",
									"adapter"        => "mysqli",
									"charset"        => "UTF-8",
									"pconnect"       => false,
									"connection_ttl" => 30,
									"dbname"         => null,
									"schema"         => "member_2",
									"connection_adapter" => "mysqli",
								  "sql_adapter"        => "mysql",
						),
					),
				),
			),
			"trade_group" => array(
				"trade_node_1" => array(
					"master" => array(
						array(
									"host"           => "10.0.2.1",
									"port"           => 1521,
									"username"       => "root",
									"password"       => "123456",
									"adapter"        => "oci",
									"charset"        => "UTF-8",
									"pconnect"       => true,
									"connection_ttl" => 3600,
									"dbname"         => "finance",
									"schema"         => "trade",
									"connection_adapter" => "oci",
								  "sql_adapter"        => "oracle",
						),
						array(
									"host"           => "10.0.2.2",
									"port"           => 1521,
									"username"       => "root",
									"password"       => "123456",
									"adapter"        => "oci",
									"charset"        => "UTF-8",
									"pconnect"       => true,
									"connection_ttl" => 3600,
									"dbname"         => "finance",
									"schema"         => "trade",
									"connection_adapter" => "oci",
								  "sql_adapter"        => "oracle",
						),
					),
				),
				"trade_node_2" => array(
					"master" => array(
						array(
									"host"           => "10.0.2.3",
									"port"           => 1521,
									"username"       => "root",
									"password"       => "123456",
									"adapter"        => "oci",
									"charset"        => "UTF-8",
									"pconnect"       => true,
									"connection_ttl" => 3600,
									"dbname"         => "finance",
									"schema"         => "trade",
									"connection_adapter" => "oci",
								  "sql_adapter"        => "oracle",
						),
						array(
									"host"           => "10.0.2.4",
									"port"           => 1521,
									"username"       => "root",
									"password"       => "123456",
									"adapter"        => "oci",
									"charset"        => "UTF-8",
									"pconnect"       => true,
									"connection_ttl" => 3600,
									"dbname"         => "finance",
									"schema"         => "trade",
									"connection_adapter" => "oci",
								  "sql_adapter"        => "oracle",
						),
					),
				),
				"trade_node_3" => array(
					"master" => array(
						array(
									"host"           => "10.0.2.5",
									"port"           => 1521,
									"username"       => "root",
									"password"       => "123456",
									"adapter"        => "oci",
									"charset"        => "UTF-8",
									"pconnect"       => true,
									"connection_ttl" => 3600,
									"dbname"         => "finance",
									"schema"         => "trade",
									"connection_adapter" => "oci",
								  "sql_adapter"        => "oracle",
						),
						array(
									"host"           => "10.0.2.6",
									"port"           => 1521,
									"username"       => "root",
									"password"       => "123456",
									"adapter"        => "oci",
									"charset"        => "UTF-8",
									"pconnect"       => true,
									"connection_ttl" => 3600,
									"dbname"         => "finance",
									"schema"         => "trade",
									"connection_adapter" => "oci",
								  "sql_adapter"        => "oracle",
						),
					),
				),
			),
		),
		$dcb->getServers()
		);//end $this->assertEquals
	}
	protected function setUp()
	{
	}
	protected function tearDown()
	{
	}
}
