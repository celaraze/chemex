<div class="grid-dropdown-actions dropdown">
    {!! $view !!} {!! $edit !!}

    {{--如果有自定义的按钮 并且 有默认按钮，则下拉显示--}}
    @if(!empty($custom) && !$remove)
        <a href="#" data-toggle="dropdown">
            更多<i class="feather icon-more-vertical"></i>
        </a>
        <ul class="dropdown-menu drop-right">

            @foreach($default as $action)
                <li class="dropdown-item">{!! Dcat\Admin\Support\Helper::render($action) !!}</li>
            @endforeach

            @if(!empty($default))
                <li class="dropdown-divider"></li>
            @endif

            @foreach($custom as $action)
                <li class="dropdown-item">{!! $action !!}</li>
            @endforeach
        </ul>
        {{--如果有自定义按钮 并且 没有默认按钮，则横向直接显示--}}
    @else
        @foreach($custom as $action)
            {!! $action !!}
        @endforeach
    @endif
</div>
<style>
    a {
        padding: 0 4px;
    }

    .dropdown .drop-right:before {
        left: 9rem;
    }
</style>
