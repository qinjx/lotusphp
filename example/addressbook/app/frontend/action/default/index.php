<?php
class DefaultIndexAction extends MyAction
{
	public function __construct()
	{
		parent::__construct();
		$this->responseType = 'tpl';
		$this->layout = 'index';
	}

	public function execute()
	{
		$page = $this->context->get('page');
		$page = max(intval($page), 1);
		$page_size = $this->configHandle->get('page_size');
		if (empty($page_size))
		{
			$page_size = 25;
		}
		$param['limit'] = $page_size;
		$param['offset'] = ($page-1) * $page_size;
		
		$param['op'] = $this->context->get('op');
		$param['gid'] = $this->context->post('gid');
		$param['q'] = $this->context->post('q');
		$param['field'] = $this->context->post('field');
		
		// userid
		$uid = $this->data['uid'];

		$addressBookService = new AddressBookService();
		
		// 取当前用户通讯录的所有分组
		$this->data['groups'] = $addressBookService->getAllGroups($uid);

		$this->data['data'] = $addressBookService->getAddressBookListByUserId($uid, $param);
		$count = $this->data['data']['count'];

		// 分页  :page 会自动被替换掉
		$base_url = LtObjectUtil::singleton('LtUrl')->generate('Default', 'Index', array('page' => ':page')); 
		$pagination = new LtPagination;
		$pagination->init();
		$this->data['pages'] = $pagination->pager($page, $count, $base_url);

		// 页面标题
		$this->data['title'] = 'addressbook';

		// 入口文件url路径
		$this->data['baseurl'] = $this->configHandle->get('baseurl');
	}
}
