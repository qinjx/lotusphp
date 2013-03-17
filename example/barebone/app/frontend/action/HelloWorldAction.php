<?php
/**
 * 最简单的hello world示例
 * action类是自动加载 的，类名和目录/文件名没有必然的联系，只要你自己能看懂就可以了
 * @author wuxiao
 *
 */
class HelloWorldAction extends FrontendAction
{
	public function execute()
	{
		echo "hello world";exit;
	}
}