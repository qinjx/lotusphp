<?php
/**
 * 默认的路由表
 */
$config['router.routing_table'] = array('pattern' => ":module/:action/*",
	'default' => array('module' => 'Default', 'action' => 'Index'),
	'reqs' => array('module' => '[a-zA-Z0-9\.\-_]+', 'action' => '[a-zA-Z0-9\.\-_]+'),
	'varprefix' => ':',
	'delimiter' => '/',
	'postfix' => '',
	'protocol' => 'rewrite', // standard rewrite path_info
	);

/**
 * url传递模式
 * standard, rewrite, path_info
 */
$config['my_url_option'] = array('url_mode' => 'path_info'
	);

/**
 * 路由匹配规则
 */
$config['my_routers'] = array('book' => array('module' => 'book',
		'action' => 'list',
		'pattern' => 'book/:category/:id',
		'suffix' => 'html'
		),
	'passport' => array('module' => 'User',
		'action' => 'Signin',
		'pattern' => 'UserSignin'
		)
	);
