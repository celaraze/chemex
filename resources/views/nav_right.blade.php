<ul class="nav navbar-nav">
    {{--帮助信息--}}
    <li class="dropdown dropdown-notification nav-item mr-1">
        <a class="nav-link nav-link-label" href="#" data-toggle="dropdown" aria-expanded="true">
            <i class="ficon feather icon-help-circle"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right shadow-200">
            <li class="scrollable-container media-list ps ps--active-y">
                <a class="media d-flex justify-content-between" href="#">
                    <div class="d-flex align-items-start">
                        <div class="media-left">
                        </div>
                        <div class="media-body">
                            <h6 class="primary media-heading">APP配置地址</h6>
                            <small class="notification-text">

                            </small>
                        </div>
                    </div>
                </a>
            </li>
            <li class="scrollable-container media-list ps ps--active-y">
                <a class="media d-flex justify-content-between" href="https://gitee.com/liuming5678/chemex/issues"
                   target="_blank">
                    <div class="d-flex align-items-start">
                        <div class="media-left">
                        </div>
                        <div class="media-body">
                            <h6 class="primary media-heading">上报错误</h6>
                            <small class="notification-text">
                                当在使用过程中遇到了一些BUG或是错误问题，请务必告诉我。
                            </small>
                        </div>
                    </div>
                </a>
            </li>
            <li class="scrollable-container media-list ps ps--active-y" style="max-height: none;">
                <a class="media d-flex justify-content-between" href="https://jq.qq.com/?_wv=1027&k=uoIYB37y"
                   target="_blank">
                    <div class="d-flex align-items-start">
                        <div class="media-left">
                        </div>
                        <div class="media-body">
                            <h6 class="primary media-heading">加入QQ群</h6>
                            <small class="notification-text">
                                与其它资产管理系统用户进行交流。
                            </small>
                        </div>
                    </div>
                </a>
            </li>
        </ul>
    </li>

    {{--通知--}}
    <li class="dropdown dropdown-notification nav-item mr-1" style="text-align: center">
        <a class="nav-link nav-link-label" href="#" data-toggle="dropdown" aria-expanded="true">
            <i class="ficon feather icon-bell"></i>
            @if(count($notifications)>0)
                <span class="badge badge-pill badge-primary badge-up">
                {{count($notifications)}}
                </span>
            @endif
        </a>
        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right shadow-200">
            <li class="dropdown-menu-header">
                <div class="dropdown-header m-0 p-2">
                    <h3 class="white">{{count($notifications)}}</h3>
                    <span class="grey darken-2">全部通知数量</span>
                </div>
            </li>
            @if(count($notifications)>0)
                <li class="scrollable-container media-list ps ps--active-y">
                    @foreach($notifications as $notification)
                        <a class="media d-flex justify-content-between"
                           href="{{admin_route('notification.read',[$notification['id']])}}">
                            <div class="d-flex align-items-start">
                                <div class="media-left">
                                </div>
                                <div class="media-body">
                                    <h6 class="primary media-heading">{{trans('main.'.$notification['data']['title'])}}</h6>
                                    <small class="notification-text">
                                        {{trans('main.'.$notification['data']['content'])}}
                                    </small>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </li>
                <li class="dropdown-menu-footer">
                    <a class="dropdown-item p-1 text-center" href="{{admin_route('notification.read.all')}}">全部已读</a>
                </li>
            @endif
        </ul>
    </li>
</ul>
