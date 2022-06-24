<div class="timeline">
    <div class="time-label">
        <span class="bg-green">最新记录</span>
    </div>
    @foreach($data as $key=>$item)
        <div>
            @if($item['status'] == '+')
                <i class="fa fa-plus" style="background: rgba(98,168,234,1);color: white"></i>
                <div class="timeline-item">
                    <span class="time"><i class="feather icon-clock"></i> {{$item['datetime']}}</span>
                    <h3 class="timeline-header">
                        关联了 {{$item['type']}}
                    </h3>
                    <div class="timeline-body">
                        {{$item['name']}}
                    </div>
                </div>
            @else
                <i class="fa fa-minus" style="background: rgba(234,84,85,1);color: white"></i>
                <div class="timeline-item">
                    <span class="time"><i class="feather icon-clock"></i> {{$item['datetime']}}</span>
                    <h3 class="timeline-header">
                        解除了 {{$item['type']}}
                    </h3>
                    <div class="timeline-body">
                        {{$item['name']}}
                    </div>
                </div>
            @endif
        </div>
    @endforeach
    <div>
        <i class="fas fa-clock bg-gray"></i>
    </div>
</div>

<style>
    .timeline-item, .timeline-header {
        background: none !important;
        color: #a8a9bb !important;
    }
</style>
