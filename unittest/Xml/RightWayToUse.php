<?php
/**
 * 本测试文档演示了Xml的正确使用方法 
 * 按本文档操作一定会得到正确的结果
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class RightWayToUseXml extends PHPUnit_Framework_TestCase
{
	/**
	 * Xml文件说明
	 * 测试用Xml文件为 example.xml，有三级标签（<catalog>, <book>, <title>等）。
	 *
	 * 测试从xml字符串转换为数组
	 */
	public function testXmlStringToArray() { 
		// 初始化LtXml
		$xml = new LtXml;
		$xml->init(); 
		// 初始化结束
		// 读取xml字符串
		$xmlStr = <<<XML
<?xml version="1.0"?>
<catalog>
 <book id="bk101">
      <author>Gambardella, Matthew</author>
      <title>XML Developer's Guide</title>
      <description>An in-depth look at creating applications 
      with XML.</description>
   </book>
   <book id="bk102">
      <author>Ralls, Kim</author>
      <title>Midnight Rain</title>
      <description>A former architect battles corporate zombies, 
      an evil sorceress, and her own childhood to become queen 
      of the world.</description>
   </book>
</catalog>
XML;
		// 根据字符串获取xml数组
		// 数组格式如下：
		// tag 标签名
		// cdata 数据
		// attr 属性列表
		// sub 子标签列表
		$xmlArr = $xml->getArray($xmlStr);

		$this->assertEquals('catalog', $xmlArr["tag"]);
		$this->assertEquals(2, count($xmlArr["sub"]));

		$this->assertEquals('book', $xmlArr["sub"][0]["tag"]);
		$this->assertEquals(1, count($xmlArr["sub"][0]["attributes"]));
		$this->assertEquals('bk101', $xmlArr["sub"][0]["attributes"]["id"]);
		$this->assertEquals(3, count($xmlArr["sub"][0]["sub"]));
		$this->assertEquals('author', $xmlArr["sub"][0]["sub"][0]["tag"]);
		$this->assertEquals('Gambardella, Matthew', $xmlArr["sub"][0]["sub"][0]["cdata"]);

		$this->assertEquals('book', $xmlArr["sub"][1]["tag"]);
		$this->assertEquals(1, count($xmlArr["sub"][1]["attributes"]));
		$this->assertEquals('bk102', $xmlArr["sub"][1]["attributes"]["id"]);
		$this->assertEquals(3, count($xmlArr["sub"][1]["sub"]));
		$this->assertEquals('title', $xmlArr["sub"][1]["sub"][1]["tag"]);
		$this->assertEquals('Midnight Rain', $xmlArr["sub"][1]["sub"][1]["cdata"]);
	}

	/**
	 * Xml数组说明
	 * 手动生成上一个测试中返回的数组
array(4) {
  ["tag"]=>
  string(7) "catalog"
  ["attributes"]=>
  array(0) {
  }
  ["sub"]=>
  array(2) {
    [0]=>
    array(4) {
      ["tag"]=>
      string(4) "book"
      ["attributes"]=>
      array(1) {
        ["id"]=>
        string(5) "bk101"
      }
      ["sub"]=>
      array(3) {
        [0]=>
        array(4) {
          ["tag"]=>
          string(6) "author"
          ["attributes"]=>
          array(0) {
          }
          ["sub"]=>
          array(0) {
          }
          ["cdata"]=>
          string(20) "Gambardella, Matthew"
        }
        [1]=>
        array(4) {
          ["tag"]=>
          string(5) "title"
          ["attributes"]=>
          array(0) {
          }
          ["sub"]=>
          array(0) {
          }
          ["cdata"]=>
          string(21) "XML Developer's Guide"
        }
        [2]=>
        array(4) {
          ["tag"]=>
          string(11) "description"
          ["attributes"]=>
          array(0) {
          }
          ["sub"]=>
          array(0) {
          }
          ["cdata"]=>
          string(58) "An in-depth look at creating applications 
      with XML."
        }
      }
      ["cdata"]=>
      string(0) ""
    }
    [1]=>
    array(4) {
      ["tag"]=>
      string(4) "book"
      ["attributes"]=>
      array(1) {
        ["id"]=>
        string(5) "bk102"
      }
      ["sub"]=>
      array(3) {
        [0]=>
        array(4) {
          ["tag"]=>
          string(6) "author"
          ["attributes"]=>
          array(0) {
          }
          ["sub"]=>
          array(0) {
          }
          ["cdata"]=>
          string(10) "Ralls, Kim"
        }
        [1]=>
        array(4) {
          ["tag"]=>
          string(5) "title"
          ["attributes"]=>
          array(0) {
          }
          ["sub"]=>
          array(0) {
          }
          ["cdata"]=>
          string(13) "Midnight Rain"
        }
        [2]=>
        array(4) {
          ["tag"]=>
          string(11) "description"
          ["attributes"]=>
          array(0) {
          }
          ["sub"]=>
          array(0) {
          }
          ["cdata"]=>
          string(130) "A former architect battles corporate zombies, 
      an evil sorceress, and her own childhood to become queen 
      of the world."
        }
      }
      ["cdata"]=>
      string(0) ""
    }
  }
  ["cdata"]=>
  string(0) ""
}
	 *
	 * 测试从xml数组转换为可以写入文件的字符串
	 */
	public function testXmlArrayToString() { 
		// 初始化LtXml
		$xml = new LtXml;
		$xml->init(WRITEMODE); 
		// 初始化结束
		$xmlArray = $xml->createTag("catalog");

		$subArray = $xml->createTag("book", "", array("id" => "bk101"));
		$subArray["sub"][] = $xml->createTag("author", "Gambardella, Matthew");
		$subArray["sub"][]= $xml->createTag("title", "XML Developer's Guide");
		$subArray["sub"][]= $xml->createTag("description", "An in-depth look at creating applications \nwith XML.");
		$xmlArray["sub"][] = $subArray;

		$subArray = $xml->createTag("book", "", array("id" => "bk102"));
		$subArray["sub"][] = $xml->createTag("author", "Ralls, Kim");
		$subArray["sub"][]= $xml->createTag("title", "Midnight Rain");
		$subArray["sub"][]= $xml->createTag("description", "A former architect battles corporate zombies, \nan evil sorceress, and her own childhood to become queen \nof the world.");
		$xmlArray["sub"][] = $subArray;

		$xmlString = $xml->getString($xmlArray);

		$expectedResult = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n<catalog>\n<book id=\"bk101\">\n<author>\nGambardella, Matthew\n</author>\n<title>\nXML Developer&apos;s Guide\n</title>\n<description>\nAn in-depth look at creating applications \nwith XML.\n</description>\n</book>\n<book id=\"bk102\">\n<author>\nRalls, Kim\n</author>\n<title>\nMidnight Rain\n</title>\n<description>\nA former architect battles corporate zombies, \nan evil sorceress, and her own childhood to become queen \nof the world.\n</description>\n</book>\n</catalog>\n";
		$this->assertEquals($expectedResult, $xmlString);
	}

	protected function setUp() {
	}
	protected function tearDown() {
	}
}
