<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="chrome=1,IE=edge">
    {{-- 默认使用谷歌浏览器内核--}}
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title>@if(! empty($header)){{ $header }} | @endif {{ Dcat\Admin\Admin::title() }}</title>

    @if(! config('admin.disable_no_referrer_meta'))
        <meta name="referrer" content="no-referrer"/>
    @endif

    @if(! empty($favicon = Dcat\Admin\Admin::favicon()))
        <link rel="shortcut icon" href="{{$favicon}}">
    @endif

    {!! admin_section(Dcat\Admin\Admin::SECTION['HEAD']) !!}

    {!! Dcat\Admin\Admin::asset()->headerJsToHtml() !!}

    {!! Dcat\Admin\Admin::asset()->cssToHtml() !!}

    <style>
        #app section.content > .row {
            margin-right: 0;
        }

        #app .dcat-box {
            overflow: revert;
        }
    </style>
</head>

<body class="dcat-admin-body full-page {{ $configData['body_class'] }}">

<script>
    var Dcat = CreateDcat({!! Dcat\Admin\Admin::jsVariables() !!});
    var storage = window.parent.localStorage || {
            setItem: function () {
            }, getItem: function () {
            }
        },
        key = 'dcat-admin-theme-mode',
        mode = storage.getItem(key)

    Dcat.darkMode.display(mode === 'dark');

    window.parent.$(window.parent.document).on('dark-mode.shown', function () {
        Dcat.darkMode.display(true);
    });

    window.parent.$(window.parent.document).on('dark-mode.hide', function () {
        Dcat.darkMode.display(false);
    });
</script>

{{-- 页面埋点 --}}
{!! admin_section(Dcat\Admin\Admin::SECTION['BODY_INNER_BEFORE']) !!}

<div class="app-content content">
    <div class="wrapper" id="{{ $pjaxContainerId }}"
         style="min-height:calc(100vh - 45px);box-sizing: border-box;padding: 1.5rem 3rem 3rem 3rem">
        @yield('app')
    </div>
    @if(!isset(config('iframe_tab')['footer_setting']['use_menu'])||!config('iframe_tab')['footer_setting']['use_menu'])
        <footer style="margin-left: 0" class="main-footer pt-1">
            <p class="clearfix blue-grey lighten-2 mb-0 text-center">
            <span class="text-center d-block d-md-inline-block mt-25">
                Powered by
                @if(isset(config('iframe_tab')['footer_setting'])&&config('iframe_tab')['footer_setting']['copyright']!='')
                    <a target=""
                       href="javascript:void 0">{{ config('iframe_tab')['footer_setting']['copyright'] }}</a>
                @else
                    <a target="_blank" href="https://github.com/jqhph/dcat-admin">Dcat Admin</a>
                @endif
                <span>&nbsp;·&nbsp;</span>
                @if(isset(config('iframe_tab')['footer_setting'])&&config('iframe_tab')['footer_setting']['app_version']!='')
                    v{{ config('iframe_tab')['footer_setting']['app_version'] }}
                @else
                    v{{ Dcat\Admin\Admin::VERSION }}
                @endif

            </span>

                <button class="btn btn-primary btn-icon scroll-top pull-right"
                        style="position: fixed;bottom: 2%; right: 10px;display: none">
                    <i class="feather icon-arrow-up"></i>
                </button>
            </p>
        </footer>
    @endif
</div>

{!! admin_section(Dcat\Admin\Admin::SECTION['BODY_INNER_AFTER']) !!}

{!! Dcat\Admin\Admin::asset()->jsToHtml() !!}

<script>Dcat.boot();</script>
<script src="{{asset('/vendor/iframe-tab/js/extend.js')}}"></script>
</body>
</html>
