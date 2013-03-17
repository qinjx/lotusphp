<?php
/**
 * url传递模式
 * standard, rewrite, path_info
 */
$config['my_url_option'] = array(
    'url_mode' => 'path_info'
);

/**
 * 路由匹配规则
 */
$config['my_routers'] = array(
    'book' => array(
        'module' => 'book',
        'action' => 'list',
        'pattern' => 'book/:category/:id',
        'suffix' => 'html'
    ),
    'passport' => array(
        'module' => 'User',
        'action' => 'Signin',
        'pattern' => 'UserSignin'
    )
);
