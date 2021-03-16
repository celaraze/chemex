<?php

namespace Celaraze\DcatPlus;

use Celaraze\DcatPlus\Http\Middleware\AfterInjectDcatPlus;
use Celaraze\DcatPlus\Http\Middleware\BeforeInjectDcatPlus;
use Celaraze\DcatPlus\Http\Middleware\MiddleInjectDcatPlus;
use Dcat\Admin\Extend\ServiceProvider as BaseServiceProvider;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends BaseServiceProvider
{
    protected $js = [
        'js/index.js',
    ];
    protected $css = [
        'css/index.css',
    ];
    protected $middleware = [
        'before' => [
            BeforeInjectDcatPlus::class,
        ],
        'middle' => [
            MiddleInjectDcatPlus::class,
        ],
        'after' => [
            AfterInjectDcatPlus::class,
        ],
    ];
    protected $menu = [
        [
            'title' => 'Dcat Plus',
            'uri'   => 'dcat-plus/site',
            'icon'  => 'feather icon-settings',
        ],
    ];

    public function register()
    {
        //
    }

    public function settingForm(): Setting
    {
        return new Setting($this);
    }

    public function init()
    {
        parent::init();
    }
}
