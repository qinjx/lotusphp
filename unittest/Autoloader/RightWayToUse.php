<?php
/**
 * 本测试文档演示了LtAutoloader的正确使用方法 
 * 按本文档操作一定会得到正确的结果
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class RightWayToUseAutoloader extends PHPUnit_Framework_TestCase
{
	/**
	 * -------------------------------------------------------------------
	 * LtAutoloader要求：
	 *  # 需要被自动加载的文件都以.php或者.inc结尾
	 *    如果既有php文件，又有html文件，html文件将被忽略，php文件正常加载
	 *    可配置，详情参见LtAutoloaderCofig
	 * 
	 * -------------------------------------------------------------------
	 * LtAutoloader不在意：
	 *  # 目录名有没有拖尾斜线
	 *  # 目录下面有无子目录
	 *  # 文件名和文件路径跟类名有无关联
	 *  # 定义和使用类时，类名是大写还是小写 
	 * 
	 * -------------------------------------------------------------------
	 * LtAutoloader不支持（出错演示和不支持的原因参见WrongWayToUse.php）：
	 *  # 传入的参数不是真实存在的目录（如http://some_dir这样的） 
	 *  # 目录名或者文件名带空格（如"Zend Framework"这样的目录名） 
	 *  # 类或接口重名，函数和函数重名
	 * 
	 * -------------------------------------------------------------------
	 * LtAutoloader建议（不强求）：
	 *  # 传入autoloadPath时使用绝对路径
	 *  # 目录名和文件名只由字母、数字、下划线、中横线、小数点组成
	 *  # 使用class而不是function来封装你的逻辑
	 *  # 每个class都放在单独的一个文件中，且不要在已经定义了类的文件里再定义函数
	 *  # class/function里不要使用__FILE__魔术变量
	 * 
	 * -------------------------------------------------------------------
	 * 本测试用例期望效果：
	 * 在new CLASS_NAME, class_exists("CLASS_NAME"), extends CLASS_NAME的时候 
	 * 自动把包含该类的文件加载进来
	 */
	public function testMostUsedWay()
	{
		/**
		 * Lotus组件初始化三步曲
		 */
		// 1. 实例化
		$autoloader = new LtAutoloader;

		// 2. 设置属性
		$autoloader->autoloadPath = array(
			dirname(__FILE__) . "/test_data/class_dir_1",
			dirname(__FILE__) . "/test_data/class_dir_2",
			dirname(__FILE__) . "/test_data/function_dir_1",
			dirname(__FILE__) . "/test_data/function_dir_2"
		);

		// 3. 调init()方法
		$autoloader->init();

		//初始化完毕，测试其效果
		$this->assertTrue(new Goodbye instanceof GoodBye);
		$this->assertTrue(class_exists("HelloWorld"));
		$this->assertEquals("hello", HelloLotus::sayHello());
		$this->assertEquals("hello", say_hello());
		$this->assertEquals("hello_2", say_hello_2());
	}

	/**
	 * 本用例展示了怎样给LtAutoloader->autoloadPath传递需要自动加载的目录
	 * 
	 * 添加新的测试条请增加一个数组 
	 * array('autoloadPath', '正确结果')
	 */
	public function autoloadPathDataProvider()
	{
		$cd = dirname(__FILE__); //current dir, 当前目录
		chdir($cd);
		return array(
			// 用一个数组传递多个目录，绝对路径，不带拖尾斜线
			array(
				array("$cd/test_data/class_dir_1", "$cd/test_data/class_dir_2"),
				array("$cd" . DIRECTORY_SEPARATOR . "test_data" . DIRECTORY_SEPARATOR . "class_dir_1", "$cd" . DIRECTORY_SEPARATOR . "test_data" . DIRECTORY_SEPARATOR . "class_dir_2"),
				), 
			// 只有一个目录，可以不用数组传
			array(
				"$cd/test_data/class_dir_1",
				array("$cd" . DIRECTORY_SEPARATOR . "test_data" . DIRECTORY_SEPARATOR . "class_dir_1"),
				), 
			// 用二维数组传递多个目录（不推荐）
			array(
				array("test_data/class_dir_1", array("test_data/class_dir_2")),
				array("$cd" . DIRECTORY_SEPARATOR . "test_data" . DIRECTORY_SEPARATOR . "class_dir_1", "$cd" . DIRECTORY_SEPARATOR . "test_data" . DIRECTORY_SEPARATOR . "class_dir_2"),
				), 
			// 相对路径（不推荐）
			array(
				array("test_data/class_dir_1", "test_data/class_dir_2"),
				array("$cd" . DIRECTORY_SEPARATOR . "test_data" . DIRECTORY_SEPARATOR . "class_dir_1", "$cd" . DIRECTORY_SEPARATOR . "test_data" . DIRECTORY_SEPARATOR . "class_dir_2"),
				), 
			// 带拖尾斜线
			array(
				array("test_data/class_dir_1/", "test_data/class_dir_2\\"),
				array("$cd" . DIRECTORY_SEPARATOR . "test_data" . DIRECTORY_SEPARATOR . "class_dir_1", "$cd" . DIRECTORY_SEPARATOR . "test_data" . DIRECTORY_SEPARATOR . "class_dir_2"),
				), 
			// 目录分隔符\/任意
			array(
				array("$cd\\test_data\\class_dir_1", "$cd/test_data/class_dir_1"),
				array("$cd" . DIRECTORY_SEPARATOR . "test_data" . DIRECTORY_SEPARATOR . "class_dir_1", "$cd" . DIRECTORY_SEPARATOR . "test_data" . DIRECTORY_SEPARATOR . "class_dir_1"),
				), 
			// 可以是文件
			array(
				"$cd/test_data/class_dir_1/GoodBye.php",
				array("$cd" . DIRECTORY_SEPARATOR . "test_data" . DIRECTORY_SEPARATOR . "class_dir_1" . DIRECTORY_SEPARATOR . 'GoodBye.php')
				),
			// 去除重复目录分隔符\/
			array(
				array("$cd\\test_data\\class_dir_1", "$cd//test_data//class_dir_1"),
				array("$cd" . DIRECTORY_SEPARATOR . "test_data" . DIRECTORY_SEPARATOR . "class_dir_1", "$cd" . DIRECTORY_SEPARATOR . "test_data" . DIRECTORY_SEPARATOR . "class_dir_1"),
				), 
			);
	}

	/**
	 * 本用例展示了LtAutoloader能识别哪些类和函数定义 
	 * 
	 * 添加新的测试条请增加一个数组 
	 * array('定义类或函数的代码','正确结果')
	 */
	public function parseLibNamesDataProvider()
	{
		return array(
			// 最常用的Class写法
			array("<?php
				class Src
				{
					public function parseSrc()//this function should not be parsed
					{
					}
				}",
				array("class" => array("Src"))
			), 
			// class关键字大写，class和类名间有多个空格或者tab，带PHP闭合标签
			array("<?php abstract Class   	Source{
				public \$string = 'the source is: class ClassInString {}, haha';
				}
				/**
				 * class ClassCommented {}
				 * function function_commented() {}
				 */
				?>",
				array("class" => array("Source"))
			),
			// 接口，interface和接口名间有换行
			array("<?php Interface
				Trade{}", array("interface" => array("Trade"))
			),
			// 函数
			array("<?php function
				function1(){}", array("function" => array("function1"))
			), 
			// 综合 示例
			array("<?php
			class TestClass {}
			abstract class TestAbstractClass {}
			interface TestInterface {}
			function test_function ()", array("class" => array("TestClass", "TestAbstractClass"), "interface" => array("TestInterface"), "function" => array("test_function"))
			),
			array(file_get_contents('../../runtime/Autoloader/Autoloader.php'), array("class" => array("LtAutoloader"))),
			array(file_get_contents('../../runtime/Cache/Adapter/CacheAdapterFile.php'), array("class" => array("LtCacheAdapterFile"))),
			);
	}

	/**
	 * 本用例展示了怎样设置允许加载的文件类型 
	 * 
	 * 添加新的测试条请增加一个数组 
	 * array('允许加载的文件类型', '用于测试的目录', '期望结果')
	 */
	public function allowedFileDataProvider()
	{
        $cd = dirname(__FILE__);
		return array(
            array(
                array("php"),
                array("$cd/test_data/is_allowed_file"),
                true,
            ),
			array(
				array("php3"),
				array("$cd/test_data/is_allowed_file"),
				false,
				),

			array(
				array("inc", "php5"),
                array("$cd/test_data/is_allowed_file"),
				true,
				),
			);
	}

	/**
	 * 本用例展示了怎样设置禁止扫描的子目录名称
	 * 
	 * 添加新的测试条请增加一个数组 
	 * array(array('禁止扫描的目录'), '用于测试的目录', '期望结果')
	 */
	public function skippedDirDataProvider()
	{
		$cd = dirname(__FILE__); //current dir, 当前目录
		return array(
			array(
				array(".svn", "subdir"),
				array("$cd/test_data/class_dir_2"),
				false,
				),

			array(
				array(".svn", "bak"),
				array("$cd/test_data/class_dir_2"),
				true,
				),
			);
	} 



/**
 * ============================================================
 * 下面是内部接口的测试用例，是给开发者保证质量用的
 * 使用者可以不往下看
 * ============================================================
 */
    /**
     * 测试自动加载function文件
     *
     * 添加新的测试条请增加一个数组
     * array('文件', '函数名')
     */
    public function loadFunctionDataProvider()
    {
        $cd = dirname(__FILE__); //current dir, 当前目录
        return array(
            array(
                array("$cd/test_data/is_load_func/sub_dir"),
                'welcome',
            ),
            array(
                array("$cd/test_data/is_load_func/sub_dir2"),
                'welcome2',
            ),
        );
    }

    /**
	 * 测试scanDirs()扫描目录 
	 * 
	 * 添加新的测试条请增加一个数组 
	 * array('目录', '类名或者函数名小写字母', '正确结果')
	 */
	public function scanDirsDataProvider()
	{
		$cd = dirname(__FILE__); //current dir, 当前目录
		return array(
			array(
				array("$cd/test_data/class_dir_1", "$cd/test_data/class_dir_2"),
				array('goodbye', 'helloworld', 'hellolotus'),
				array("$cd" . DIRECTORY_SEPARATOR . "test_data" . DIRECTORY_SEPARATOR . "class_dir_1" . DIRECTORY_SEPARATOR . 'GoodBye.php',
					"$cd" . DIRECTORY_SEPARATOR . "test_data" . DIRECTORY_SEPARATOR . "class_dir_2" . DIRECTORY_SEPARATOR . 'HelloWorld.php',
					"$cd" . DIRECTORY_SEPARATOR . "test_data" . DIRECTORY_SEPARATOR . "class_dir_2" . DIRECTORY_SEPARATOR . 'subdir' . DIRECTORY_SEPARATOR . 'anotherClass.inc',
					),
				),
			);
	} 

	/**
	 * 测试loadClass() 
	 * 
	 * 添加新的测试条请增加一个数组 
	 * array('文件', '类名或者函数名')
	 */
	public function loadClassDataProvider()
	{
		$cd = dirname(__FILE__); //current dir, 当前目录
		return array(
			array(
				"$cd/test_data/class_dir_1/LoadClass.php", 
				'isLoadClass',
				),
			);
	}

	/**
	 * 测试LtAutoloader初始化的时候 
	 * 能否正确的将传给autoloadPath的值转为合法的不重复的的绝对路径
	 * 
	 * @dataProvider autoloadPathDataProvider
	 */
	public function testPreparePath($userParameter, $expected)
	{
		$ap = new LtAutoloaderProxy;
		$path = $ap->preparePath($userParameter);
		$this->assertEquals($expected, $path);
	}

	/**
	 * 测试parseLibNames()能否正确的识别源文件中定义的类,接口,函数
	 * 
	 * @dataProvider parseLibNamesDataProvider
	 */
	public function testParseLibNames($src, $expected)
	{
		$ap = new LtAutoloaderProxy;
		$this->assertEquals($expected, $ap->parseLibNames($src));
	}

	/**
	 * 测试addFileMap()能否正确的识别允许自动加载的文件 
	 * addFileMap()依赖parseLibNames,addClass,addFunction
	 * 
	 * @dataProvider allowedFileDataProvider
	 */
	public function testAllowedFile($extArray, $dir, $expected)
	{
		$ap = new LtAutoloaderProxy;
		$ap->allowFileExtension = $extArray;
        $ap->scanDirs($dir);
        $scanned = $ap->getFilePathByClassName("HelloWorld") ?  true : false;
        $this->assertEquals($expected, $scanned);
	}

	/**
	 * 测试scanDirs()能否正确的识别允许扫描的子目录 
	 * scanDirs()依赖preparePath,addFileMap
	 * 本测试依赖storeHandle->get
	 * 
	 * @dataProvider skippedDirDataProvider
	 */
	public function testSkippedDir($dirBlackListArray, $dir, $expected)
	{
		$ap = new LtAutoloaderProxy;
		$ap->skipDirNames = $dirBlackListArray;
		$ap->scanDirs($dir);
        $scanned = $ap->getFilePathByClassName("HelloLotus") ?  true : false;
		$this->assertEquals($expected, $scanned);
	}

	/**
	 * 测试能否加载函数文件
	 * 
	 * @dataProvider loadFunctionDataProvider
	 */
	public function testLoadFunction($dir, $function)
	{
		$ap = new LtAutoloaderProxy;
        $ap->scanDirs($dir);
		$ap->loadFunctionFiles();
		$this->assertEquals(true, function_exists($function));
	}

	/**
	 * 测试scanDirs能否正确的扫描目录包括子目录
	 * scanDirs()依赖 addFileMap()
	 * 本测试依赖storeHandle->get
	 * 
	 * @dataProvider scanDirsDataProvider
	 */
	public function testScanDirs($path, $classORfunction, $pathFile)
	{
		$ap = new LtAutoloaderProxy;
		$path = $ap->preparePath($path);
		$ap->scanDirs($path);
		foreach($classORfunction as $key=>$value)
		{
			$this->assertEquals($pathFile[$key], $ap->getFilePathByClassName($value));
		}
	}

	/**
	 * 测试loadClass()
	 * 
	 * @dataProvider loadClassDataProvider
	 */
	public function testLoadClass($pathfile, $class)
	{
		$ap = new LtAutoloaderProxy;
		$ap->addFileMap($pathfile);
		$ap->loadClass($class);
		$this->assertTrue(class_exists($class));
	}

	protected function setUp()
	{
	}
	protected function tearDown()
	{
	}
}
