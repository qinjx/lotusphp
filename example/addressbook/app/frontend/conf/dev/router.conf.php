<?php
$config['router.routing_table'] = array('pattern' => ":module/:action/*",
	'default' => array('module' => 'Default', 'action' => 'Index'),
	'reqs' => array('module' => '[a-zA-Z0-9\.\-_]+', 'action' => '[a-zA-Z0-9\.\-_]+'),
	'varprefix' => ':',
	'delimiter' => '/',
	'postfix' => '/',
	'protocol' => 'standard', // standard rewrite path_info
	);
