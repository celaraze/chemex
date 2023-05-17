# dcat-iframe-tab

## 介绍

这个扩展包基于laravel框架和dcat-admin框架，为解决dcat-admin没有自带兼容iframe架构。使用此扩展包可以构建出一个iframe架构并带有标签页管理的后台框架。

## 功能

1. 双击关闭标签页
2. 当标签页过多时，可通过鼠标滚轮选择或者按住鼠标拖动
3. 支持右键操作（目前支持的操作有：关闭所有标签、关闭其他标签、刷新当前标签、复制标签页链接）

## 安装

运行以下命令：

```
$ composer require mosiboom/dcat-iframe-tab
```

然后运行：

```
# 发布扩展必备文件
$ php artisan vendor:publish --tag=iframe-tab
# 发布扩展配置文件
$ php artisan vendor:publish --tag=iframe-tab.config
# 发布扩展的视图文件(如想自定义某些内容可发布出去，建议不要使用)
$ php artisan vendor:publish --tag=iframe-tab.view
```

`php artisan vendor:publish --tag=iframe-tab` 会将css和js发布`public/vendor/iframe-tab`

## 更新
相关更新内容请关注github的`tag`，里面有每个版本详细的更新：[https://github.com/mosiboom/dcat-iframe-tab/releases](https://github.com/mosiboom/dcat-iframe-tab/releases)

基本迭代更新命令：
```apacheconfig
composer remove mosiboom/dcat-iframe-tab
composer require mosiboom/dcat-iframe-tab:版本号
php artisan vendor:publish --tag=iframe-tab --force
```

其他文件覆盖更新：
```
$ php artisan vendor:publish --tag=iframe-tab --force
$ php artisan vendor:publish --tag=iframe-tab.config --force
```

This will override css and js files to `/public/vendor/laravel-admin-ext/iframe-tabs/`

此操作会覆盖css和js还有配置文件，配置文件可以根据自己的需要来选择是否强制覆盖

## 配置

配置文件在 `config/iframe_tab.php`下dcat-Iframe-tab可提供的配置并不多，根据自己的需要去配置：

```php
return [
    # 是否开启iframe_tab
    'enable'                => env('START_IFRAME_TAB', true),
    # 底部设置
    'footer_setting'        => [
        'copyright'         => env('APP_NAME', ''),
        'app_version'       => env('APP_VERSION', ''),
        # 是否将底部置于菜单下
        'use_menu'          => false
    ],
    # 是否开启标签页缓存
    'cache'                 => env('IFRAME_TAB_CACHE', false),
    # 更改dialog表单默认宽高
    'dialog_area_width'     => env('IFRAME_TAB_DIALOG_AREA_WIDTH', '50%'),
    'dialog_area_height'    => env('IFRAME_TAB_DIALOG_AREA_HEIGHT', '90vh'),
    # iframe-tab占用的路由 默认 '/'
    'router'                => '/',
    'domain'                => null,
    # 是否开启懒加载模式
    'lazy_load'              => true
];
```

## 新增扩展接口和扩展功能

1. 用户可以在子页面引入 `public/vendor/iframe-tab/js/extend.js`文件，或者通过调用`window.iframeTabParent`全局对象来调用父级页面的iframe-tab
2. 引入新功能：超链接监听打开新页面加入iframe-tab：用户可自行定义超链接按钮，以此来打开新标签页页面，通过添加`iframe-extends=true` 和 `iframe-tab=true` 两个属性
```html
<a iframe-extends=true iframe-tab=true href="https://github.com/mosiboom/dcat-iframe-tab">添加新的标签页</a>
```
    
