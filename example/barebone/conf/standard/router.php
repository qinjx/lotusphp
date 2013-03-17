<?php
$config["router.routing_table"] = array('pattern' => ":module-:action-*",
					'default' => array('module' => 'default', 'action' => 'index'),
					'reqs' => array('module' => '[a-zA-Z0-9\.\-_]+', 'action' => '[a-zA-Z0-9\.\-_]+'),
					'varprefix' => ':',
					'delimiter' => '-',
					'postfix' => '.htm',
					'protocol' => 'STANDARD',
);