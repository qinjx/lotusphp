<?php
$config['page_size'] = 10;
/**
 * 分页
 */
$config['pagination.pager']['per_page'] = 10; //每个页面中希望展示的项目数量 
$config['pagination.pager']['num_links_show'] = 9; //数字链接显示数量 
$config['pagination.pager']['num_point_start_end'] = 2; //“点”前边和后边的链接数量
/**
 * 是否显示上一页,下一页...
 */
$config['pagination.pager']['show_first'] = true;
$config['pagination.pager']['show_prev'] = true;
$config['pagination.pager']['show_next'] = true;
$config['pagination.pager']['show_last'] = true;
$config['pagination.pager']['show_goto'] = false;
$config['pagination.pager']['show_info'] = false;
$config['pagination.pager']['show_point'] = true;
$config['pagination.pager']['show_empty_button'] = false;
/**
 * 上一页,下一页...的显示文字内容
 */
$config['pagination.pager']['first_text'] = '首页';
$config['pagination.pager']['prev_text'] = '上一页';
$config['pagination.pager']['next_text'] = '下一页';
$config['pagination.pager']['last_text'] = '尾页';
$config['pagination.pager']['point_text'] = '...';
/**
 * 生成html代码部分, :url表示生成链接
 */
$config['pagination.pager']['full_tag_open'] = '<div class="pages">';
$config['pagination.pager']['full_tag_close'] = '</div>';
$config['pagination.pager']['num_tag_open'] = '';
$config['pagination.pager']['num_tag_close'] = '';
$config['pagination.pager']['link_tag_open'] = '<a href=":url">';
$config['pagination.pager']['link_tag_close'] = '</a>';
$config['pagination.pager']['link_tag_cur_open'] = '<strong>';
$config['pagination.pager']['link_tag_cur_close'] = '</strong>';
$config['pagination.pager']['button_tag_open'] = '<a href=":url" style="font-weight:bold">';
$config['pagination.pager']['button_tag_close'] = '</a>';
$config['pagination.pager']['button_tag_empty_open'] = '<span>';
$config['pagination.pager']['button_tag_empty_close'] = '</span>';
$config['pagination.pager']['point_tag_open'] = '<span>';
$config['pagination.pager']['point_tag_close'] = '</span>';
