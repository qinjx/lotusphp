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
		 * 基本配置，默认分页样式
		 */
		$conf['default']['per_page'] = 25; //每个页面中希望展示的项目数量 
		$conf['default']['num_links_show'] = 9; //数字链接显示数量 
		$conf['default']['num_point_start_end'] = 2; //“点”前边和后边的链接数量
		
		/**
		 * 是否显示上一页,下一页...
		 */
		$conf['default']['show_first'] = true;
		$conf['default']['show_prev'] = true;
		$conf['default']['show_next'] = true;
		$conf['default']['show_last'] = true;
		$conf['default']['show_goto'] = false;
		$conf['default']['show_info'] = false;
		$conf['default']['show_point'] = true;
		$conf['default']['show_empty_button'] = false;
		/**
		 * 上一页,下一页...的显示文字内容
		 */
		$conf['default']['first_text'] = 'First';
		$conf['default']['prev_text'] = 'Prev';
		$conf['default']['next_text'] = 'Next';
		$conf['default']['last_text'] = 'Last';
		$conf['default']['point_text'] = '...';
		/**
		 * 生成html代码部分, :url表示生成链接
		 */
		$conf['default']['full_tag_open'] = '<div class="pages">';
		$conf['default']['full_tag_close'] = '</div>';
		$conf['default']['num_tag_open'] = '';
		$conf['default']['num_tag_close'] = '';
		$conf['default']['link_tag_open'] = '<a href=":url">';
		$conf['default']['link_tag_close'] = '</a>';
		$conf['default']['link_tag_cur_open'] = '<strong>';
		$conf['default']['link_tag_cur_close'] = '</strong>';
		$conf['default']['button_tag_open'] = '<a href=":url" style="font-weight:bold">';
		$conf['default']['button_tag_close'] = '</a>';
		$conf['default']['button_tag_empty_open'] = '<span>';
		$conf['default']['button_tag_empty_close'] = '</span>';
		$conf['default']['point_tag_open'] = '<span>';
		$conf['default']['point_tag_close'] = '</span>';
		
		
		/**
		 * 分页样式2
		 */
		 
		$conf['example']['per_page'] = 25; //每个页面中希望展示的项目数量 
		$conf['example']['num_links_show'] = 9; //数字链接显示数量 
		$conf['example']['num_point_start_end'] = 2; //“点”前边和后边的链接数量
		
		/**
		 * 是否显示上一页,下一页...
		 */
		$conf['example']['show_first'] = true;
		$conf['example']['show_prev'] = true;
		$conf['example']['show_next'] = true;
		$conf['example']['show_last'] = true;
		$conf['example']['show_goto'] = false;
		$conf['example']['show_info'] = false;
		$conf['example']['show_point'] = true;
		$conf['example']['show_empty_button'] = false;
		/**
		 * 上一页,下一页...的显示文字内容
		 */
		$conf['example']['first_text'] = 'First';
		$conf['example']['prev_text'] = 'Prev';
		$conf['example']['next_text'] = 'Next';
		$conf['example']['last_text'] = 'Last';
		$conf['example']['point_text'] = '...';
		/**
		 * 生成html代码部分, :url表示生成链接
		 */
		$conf['example']['full_tag_open'] = '<div class="pages">';
		$conf['example']['full_tag_close'] = '</div>';
		$conf['example']['num_tag_open'] = '';
		$conf['example']['num_tag_close'] = '';
		$conf['example']['link_tag_open'] = '<a href=":url">';
		$conf['example']['link_tag_close'] = '</a>';
		$conf['example']['link_tag_cur_open'] = '<strong>';
		$conf['example']['link_tag_cur_close'] = '</strong>';
		$conf['example']['button_tag_open'] = '<a href=":url" style="font-weight:bold">';
		$conf['example']['button_tag_close'] = '</a>';
		$conf['example']['button_tag_empty_open'] = '<span>';
		$conf['example']['button_tag_empty_close'] = '</span>';
		$conf['example']['point_tag_open'] = '<span>';
		$conf['example']['point_tag_close'] = '</span>';
		
		/**
		 * 配置保存在文件中, 生产环境下自动从缓存读取配置, 不需要下一行代码
		 */
		$pagination->configHandle->addConfig($conf);
		/**
		 * 初始化
		 */
		 
		$pagination->init();
		
		/**
		 * 切换分页样式,如果是调用默认分页样式，这句可以不加
		 */
		$pagination->setPager('example');
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
