<?php
/**
 * The Pagination class
 * @author Yi Zhao <zhao5908@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: Pagination.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * The Pagination class
 * @author Yi Zhao <zhao5908@gmail.com>
 * @category runtime
 * @package   Lotusphp\Pagination
 */
class LtPagination
{
	/** @var LtConfig config handle */
	public $configHandle;
	
	/** @var array config */
	public $conf;
	public $confGroup;

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
		$this->confGroup = $this->configHandle->get("pagination.pager");
		if (empty($this->confGroup['default']))
		{
			$this->confGroup['default']['per_page'] = 25; //每个页面中希望展示的项目数量 
			$this->confGroup['default']['num_links_show'] = 9; //数字链接显示数量 
			$this->confGroup['default']['num_point_start_end'] = 2; //“点”前边和后边的链接数量
			$this->confGroup['default']['show_first'] = true;
			$this->confGroup['default']['show_prev'] = true;
			$this->confGroup['default']['show_next'] = true;
			$this->confGroup['default']['show_last'] = true;
			$this->confGroup['default']['show_goto'] = false;
			$this->confGroup['default']['show_info'] = false;
			$this->confGroup['default']['show_point'] = true;
			$this->confGroup['default']['show_empty_button'] = false;

			$this->confGroup['default']['first_text'] = 'First';
			$this->confGroup['default']['prev_text'] = 'Prev';
			$this->confGroup['default']['next_text'] = 'Next';
			$this->confGroup['default']['last_text'] = 'Last';
			$this->confGroup['default']['point_text'] = '...';

			$this->confGroup['default']['full_tag_open'] = '<div class="pages">';
			$this->confGroup['default']['full_tag_close'] = '</div>';
			$this->confGroup['default']['num_tag_open'] = '';
			$this->confGroup['default']['num_tag_close'] = '';
			$this->confGroup['default']['link_tag_open'] = '<a href=":url">';
			$this->confGroup['default']['link_tag_close'] = '</a>';
			$this->confGroup['default']['link_tag_cur_open'] = '<strong>';
			$this->confGroup['default']['link_tag_cur_close'] = '</strong>';
			$this->confGroup['default']['button_tag_open'] = '<a href=":url" style="font-weight:bold">';
			$this->confGroup['default']['button_tag_close'] = '</a>';
			$this->confGroup['default']['button_tag_empty_open'] = '<span>';
			$this->confGroup['default']['button_tag_empty_close'] = '</span>';
			$this->confGroup['default']['point_tag_open'] = '<span>';
			$this->confGroup['default']['point_tag_close'] = '</span>';
			
			$this->conf = $this->confGroup['default'];
		}
		else
		{
			$this->conf = $this->confGroup['default'];
		}
	}
	/**
	 * 切换分页样式
	 * @param string $pagername 确保分页样式定义存在，不存在将调用默认样式输出分页
	 */
	public function setPager($pagername)
	{
		$this->confGroup[$pagername]? $this->conf = $this->confGroup[$pagername] : $this->conf = $this->confGroup['default'];
	}
	/**
	 * 输出html格式分布代码
	 * @param int $page 当前页
	 * @param int $count 总数，例如你查询数据库得到的数据总量
	 * @param string $url baseurl使用 :page 表示页参数 不在意位置 如/a/:page/c/e
	 * @return string
	 */
	public function pager($page, $count, $url)
	{
		$per_page = empty($this->conf['per_page']) ? 25 : $this->conf['per_page'];
		$pagecount = ceil($count / $per_page);
		$pager = $this->renderPager($page, $pagecount, $url);
		if ($this->conf['show_goto'])
		{
			$pager .= $this->renderButton('goto', $page, $pagecount, $url);
		}
		if ($this->conf['show_info'])
		{
			$pager .= $this->renderButton('info', $page, $pagecount, $url);
		}
		return $this->conf['full_tag_open'] . $pager . $this->conf['full_tag_close'];
	}

	/**
	 * render pager
	 * @param int $pagenumber
	 * @param int $pagecount
	 * @param string $baseurl
	 * @return string
	 */
	private function renderPager($pagenumber, $pagecount, $baseurl = '?page=:page')
	{
		$baseurl = urldecode($baseurl);
		$pager = $this->conf['num_tag_open'];

		if ($this->conf['show_first'])
		{
		$pager .= $this->renderButton('first', $pagenumber, $pagecount, $baseurl);
		}
		if ($this->conf['show_prev'])
		{
			$pager .= $this->renderButton('prev', $pagenumber, $pagecount, $baseurl);
		}

		$startPoint = 1;
		$endPoint = $this->conf['num_links_show'];
		$num_links = ceil($this->conf['num_links_show'] / 2) - 1;
		if ($pagenumber > $num_links)
		{
			$startPoint = $pagenumber - $num_links;
			$endPoint = $pagenumber + $num_links;
		}

		if ($endPoint > $pagecount)
		{
			$startPoint = $pagecount + 1 - $this->conf['num_links_show'];
			$endPoint = $pagecount;
		}

		if ($startPoint < 1)
		{
			$startPoint = 1;
		}

		$currentButton = '';
		if ($this->conf['show_point'])
		{
			for($page = 1; $page < $startPoint; $page++)
			{
				$url = str_replace(':page', $page, $baseurl);
				if ($page > $this->conf['num_point_start_end'])
				{
					$currentButton .= $this->conf['point_tag_open'] . $this->conf['point_text'] . $this->conf['point_tag_close'];
					break;
				}
				$currentButton .= str_replace(':url', $url, $this->conf['link_tag_open']) . $page . $this->conf['link_tag_close'];
			}
		}
		for ($page = $startPoint; $page <= $endPoint; $page++)
		{
			$url = str_replace(':page', $page, $baseurl);
			if ($page == $pagenumber)
			{
				$currentButton .= $this->conf['link_tag_cur_open'] . $page . $this->conf['link_tag_cur_close'];
			}
			else
			{
				$currentButton .= str_replace(':url', $url, $this->conf['link_tag_open']) . $page . $this->conf['link_tag_close'];
			}
		}
		if ($this->conf['show_point'])
		{
			$page = $pagecount - $this->conf['num_point_start_end'];
			if ($page > $endPoint)
			{
				$currentButton .= $this->conf['point_tag_open'] . $this->conf['point_text'] . $this->conf['point_tag_close'];
			}

			for($page += 1; $page >= $endPoint && $page <= $pagecount; $page++)
			{
				if ($page == $endPoint) continue;
				$url = str_replace(':page', $page, $baseurl);
				$currentButton .= str_replace(':url', $url, $this->conf['link_tag_open']) . $page . $this->conf['link_tag_close'];
			}
		}
		$pager .= $currentButton;
		if ($this->conf['show_next'])
		{
			$pager .= $this->renderButton('next', $pagenumber, $pagecount, $baseurl);
		}
		if ($this->conf['show_last'])
		{
			$pager .= $this->renderButton('last', $pagenumber, $pagecount, $baseurl);
		}
		$pager .= $this->conf['num_tag_close'];

		return $pager;
	}

	/**
	 * render button
	 * @param string $buttonLabel 显示文字
	 * @param int $pagenumber 当前页
	 * @param int $pagecount 总页数
	 * @param string $baseurl
	 * @return string
	 */
	public function renderButton($buttonLabel, $pagenumber, $pagecount, $baseurl = '?page=:page')
	{
		$destPage = 1;
		if ('goto' == $buttonLabel)
		{
			$button = "goto <input type=\"text\" size=\"3\" onkeydown=\"javascript: if(event.keyCode==13){ location='{$baseurl}'.replace(':page',this.value);return false;}\" />";
			return $button;
		}
		if ('info' == $buttonLabel)
		{
			$button = " $pagenumber/$pagecount ";
			return $button;
		}
		switch ($buttonLabel)
		{
			case "first":
				$destPage = 1;
				$bottenText = $this->conf['first_text'];
				break;
			case "prev":
				$destPage = $pagenumber - 1;
				$bottenText = $this->conf['prev_text'];
				break;
			case "next":
				$destPage = $pagenumber + 1;
				$bottenText = $this->conf['next_text'];
				break;
			case "last":
				$destPage = $pagecount;
				$bottenText = $this->conf['last_text'];
				break;
		}
		$url = str_replace(':page', $destPage, $baseurl);
		$button = str_replace(':url', $url, $this->conf['button_tag_open']) . $bottenText . $this->conf['button_tag_close'];

		if ($buttonLabel == "first" || $buttonLabel == "prev")
		{
			if ($pagenumber <= 1)
			{
				$button = $this->conf['show_empty_button'] ? $this->conf['button_tag_empty_open'] . $bottenText . $this->conf['button_tag_empty_close'] : '';
			}
		}
		else
		{
			if ($pagenumber >= $pagecount)
			{
				$button = $this->conf['show_empty_button'] ? $this->conf['button_tag_empty_open'] . $bottenText . $this->conf['button_tag_empty_close'] : '';
			}
		}
		return $button;
	}
}

