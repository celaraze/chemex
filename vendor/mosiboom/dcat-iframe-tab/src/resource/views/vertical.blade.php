<body
        class="dcat-admin-body sidebar-mini layout-fixed {{ $configData['body_class']}} {{ $configData['sidebar_class'] }}
        {{ $configData['navbar_class'] === 'fixed-top' ? 'navbar-fixed-top' : '' }} ">

<script>
    var Dcat = CreateDcat({!! Dcat\Admin\Admin::jsVariables() !!});
</script>
{!! admin_section(Dcat\Admin\Admin::SECTION['BODY_INNER_BEFORE']) !!}
<div class="wrapper">
    {{--    {{dump(isset(config('admin.layout')['iframe_tab_cache']))}}--}}
    @include('admin::partials.sidebar')
    @include('admin::partials.navbar')
    <div class="app-content content">
        <input type="hidden" id="iframe_tab_cache" value="
       @if(isset(config('admin.layout')['iframe_tab_cache']))
        {{config('admin.layout')['iframe_tab_cache']}}
        @else
        {{config('iframe_tab.cache')?1:0}}
        @endif
                ">
        <input type="hidden" id="iframe_tab_lazy_load" value="
       @if(isset(config('admin.layout')['iframe_tab_lazy_load']))
        {{config('admin.layout')['iframe_tab_lazy_load']}}
        @else
        {{config('iframe_tab.lazy_load')?1:0}}
        @endif
                ">
        <input type="hidden" id="use_id" value="{{Admin::user()->id}}">
        {{--右键菜单监控--}}
        <div class="mouse-click-menu">
            <ul>
                <li><a href="javascript:;" class="menu-item tab-close-all">关闭所有标签页</a></li>
                <li><a href="javascript:;" class="menu-item tab-close-other">关闭其他标签页</a></li>
                <li><a href="javascript:;" class="menu-item tab-refresh">刷新当前标签页</a></li>
                @if(isset(config('admin.layout')['iframe_tab_cache'])&&config('admin.layout')['iframe_tab_cache']==1)
                    <li><a href="javascript:;" class="menu-item tab-clear-cache">清空标签页缓存</a></li>
                @else
                    @if(config('iframe_tab.cache'))
                        <li><a href="javascript:;" class="menu-item tab-clear-cache">清空标签页缓存</a></li>
                    @endif
                @endif

                <li class="li_separate"></li>
                <li><a href="javascript:;" class="menu-item tab-copy-link">复制标签页链接</a></li>
                <li><a href="javascript:;" class="menu-item tab-open-link">新标签页中打开</a></li>
            </ul>
        </div>
        <div
                class="iframe-tab-container {{mosi_iframeTabBodyClass(config('admin.layout')['body_class'])}}"
                id="iframe-tab-container">
            <div class="swiper-container">
                <ul class="nav nav-pills mb-3 swiper-wrapper" id="iframe-tab" role="tablist"></ul>
            </div>
            <div class="swiper-button-prev"><i class="fa fa-angle-double-left"></i></div>
            <div class="swiper-button-next"><i class="fa fa-angle-double-right"></i></div>
        </div>
        <div class="content-wrapper iframe-tab-wrapper" id="{{ $pjaxContainerId }}">
            @yield('app')
        </div>
    </div>
</div>
<div id="footer-template" style="display: none">
    <div
            style="text-align:center;width: 100%;position: absolute;bottom: 0;height: 45px;line-height: 45px;background: #efefef">
        <span class="text-center d-block d-md-inline-block mt-25">
                &copy;
                @if(isset(config('iframe_tab')['footer_setting'])&&config('iframe_tab')['footer_setting']['copyright']!='')
                <a target=""
                   href="javascript:void 0">{{ config('iframe_tab')['footer_setting']['copyright'] }}</a>
            @else
                <a target="_blank" href="https://github.com/jqhph/dcat-admin">Dcat Admin</a>
            @endif
                <span>&nbsp;·&nbsp;</span>
                {{date('Y')}}
        </span>
    </div>

</div>

{!! admin_section(Dcat\Admin\Admin::SECTION['BODY_INNER_AFTER']) !!}

{!! Dcat\Admin\Admin::asset()->jsToHtml() !!}

<script>Dcat.boot();</script>
<script src="{{asset('/vendor/iframe-tab/js/md5.js')}}"></script>
<script src="{{asset('/vendor/iframe-tab/js/swiper.min.js')}}"></script>
<script src="{{asset('/vendor/iframe-tab/js/base.js')}}"></script>
<script src="{{asset('/vendor/iframe-tab/js/extend.js')}}"></script>
@if(isset(config('iframe_tab')['footer_setting']['use_menu'])&&config('iframe_tab')['footer_setting']['use_menu']==true)
    <script>
        let html = $('#footer-template').html()
        $('.main-sidebar').append(html);
    </script>
@endif
</body>

</html>
