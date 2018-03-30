<?php
return array(
	//'配置项'=>'配置值'

    //URL地址不区分大小写
    'URL_CASE_INSENSITIVE' =>true,
    'URL_MODEL'=>0,
    'LOAD_EXT_CONFIG'=> 'db,own_config',
    'MD5_PRE'=> 'sing_cms',
    'HTML_FILE_SUFFIX' => '.html',
    'SHOW_PAGE_TRACE' => true,

    'URL_ROUTER_ON' => true,
    'URL_ROUTE_RULES' => [
        'test' => '/home/index/test',
    ],
);