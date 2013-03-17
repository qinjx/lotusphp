<?php
/**
 *
 * 主机信息
 * 存放网站用到的各种主机信息，便于通过函数xx来调用
 * 用于输出url的主机头部分
 *
 */
$config['my_hosts'] = array(
    'home' => array(
        'host'      => 'www.example.com.cn',
        'port'      => '80', //如果是默认80端口可以不填写
        'protocol'  => 'http',
        'base'      => '/', //相对网站根路径，如果位于跟路径可以不填写
        'name'          => '主站点'
    ),
    'passport' => array(
        'host'      => 'passport.example.com.cn',
        'port'      => '443',
        'protocol'  => 'https',
        'base'      => '/',
        'name'      => '图片服务器一'
    )
);