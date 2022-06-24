<?php

return [
    'labels' => [
        'title' => '配置',
        'description' => '与站点相关的配置',
        'setting' => '配置',
        'Site Url Help' => '站点域名决定了静态资源（头像、图片等）的显示路径，必须和env文件里面的APP地址路径一样，可以包含端口号，例如 http://www.baidu.com:8000 。',
        'Site Logo Text' => '文本LOGO显示的优先度低于图片，当没有上传图片作为LOGO时，此项将生效。',
        'Site Debug Help' => '开启 debug 模式后将会显示异常捕获信息，关闭则只返回 500 状态码。',
    ],
    'fields' => [
        'site_url' => '站点域名',
        'site_title' => '站点标题',
        'site_logo_text' => '站点文字LOGO',
        'site_logo' => '站点图片LOGO',
        'site_logo_mini' => '站点图片LOGO（微缩）',
        'site_debug' => '调试模式',
        'site_lang' => '站点语言',
    ],
    'options' => [
    ],
];
