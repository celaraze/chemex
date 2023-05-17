<?php

use Mosiboom\DcatIframeTab\Controllers\IframeController;

if (config('iframe_tab.enable')) {
    $attributes = [
        'prefix'        => config('admin.route.prefix'),
        'middleware'    => config('admin.route.middleware'),
        'domain'        => config('iframe_tab.domain', null)
    ];
    app('router')->group($attributes, function ($router) {
        $controller = IframeController::class;
        $router->get(config('iframe_tab.router','/'), $controller . '@index');
    });
}
