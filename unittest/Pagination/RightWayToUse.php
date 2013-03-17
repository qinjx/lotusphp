<?php
/**
 * 本测试文档演示了LtPagination的正确使用方法 
 * 按本文档操作一定会得到正确的结果
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
/**
 * 
 * @todo 应该将计算分页和输出html分开，允许用户指定展现层handle，可以提供默认handle
 */
class RightWayToUsePagination extends PHPUnit_Framework_TestCase
{
	/**
	 * 最常用的使用方式
	 */
	public function testMostUsedWay()
	{
		$pagination = new LtPagination;

		/**
		 * 基本配置
		 */
		$conf['per_page'] = 25; //每个页面中希望展示的项目数量 
		$conf['num_links_show'] = 9; //数字链接显示数量 
		$conf['num_point_start_end'] = 2; //“点”前边和后边的链接数量
		
		/**
		 * 是否显示上一页,下一页...
		 */
		$conf['show_first'] = true;
		$conf['show_prev'] = true;
		$conf['show_next'] = true;
		$conf['show_last'] = true;
		$conf['show_goto'] = false;
		$conf['show_info'] = false;
		$conf['show_point'] = true;
		$conf['show_empty_button'] = false;
		/**
		 * 上一页,下一页...的显示文字内容
		 */
		$conf['first_text'] = 'First';
		$conf['prev_text'] = 'Prev';
		$conf['next_text'] = 'Next';
		$conf['last_text'] = 'Last';
		$conf['point_text'] = '...';
		/**
		 * 生成html代码部分, :url表示生成链接
		 */
		$conf['full_tag_open'] = '<div class="pages">';
		$conf['full_tag_close'] = '</div>';
		$conf['num_tag_open'] = '';
		$conf['num_tag_close'] = '';
		$conf['link_tag_open'] = '<a href=":url">';
		$conf['link_tag_close'] = '</a>';
		$conf['link_tag_cur_open'] = '<strong>';
		$conf['link_tag_cur_close'] = '</strong>';
		$conf['button_tag_open'] = '<a href=":url" style="font-weight:bold">';
		$conf['button_tag_close'] = '</a>';
		$conf['button_tag_empty_open'] = '<span>';
		$conf['button_tag_empty_close'] = '</span>';
		$conf['point_tag_open'] = '<span>';
		$conf['point_tag_close'] = '</span>';
		/**
		 * 配置保存在文件中, 生产环境下自动从缓存读取配置, 不需要下一行代码
		 */
		$pagination->configHandle->addConfig($conf);
		/**
		 * 初始化
		 */
		$pagination->init();
		/**
		 * 显示第一页, 共1000条, 
		 * 每页显示多少条使用配置文件, 
		 * url中:page表示当前页
		 */
		$pager = $pagination->pager(1, 1000, '?page=:page');
		$this->assertEquals($pager, '<div class="pages"><strong>1</strong><a href="?page=2">2</a><a href="?page=3">3</a><a href="?page=4">4</a><a href="?page=5">5</a><a href="?page=6">6</a><a href="?page=7">7</a><a href="?page=8">8</a><a href="?page=9">9</a><span>...</span><a href="?page=39">39</a><a href="?page=40">40</a><a href="?page=2" style="font-weight:bold">Next</a><a href="?page=40" style="font-weight:bold">Last</a></div>');
	}

	protected function setUp()
	{
	}

	protected function tearDown()
	{
	}
}
