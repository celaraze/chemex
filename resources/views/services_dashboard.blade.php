<table class="table">
    <thead>
    <tr>
        <th scope="col">宿主设备名称</th>
        <th scope="col">服务程序名称</th>
        <th scope="col">状态</th>
        <th scope="col">异常说明</th>
        <th scope="col">开始时间</th>
        <th scope="col">恢复时间</th>
    </tr>
    </thead>
    <tbody>
    @foreach($services as $service)
        <tr class="table-content">
            <td style="color: #a8a9bb;">{{$service['device_name']}}</td>
            <td style="color: #a8a9bb;">{{$service['name']}}</td>
            <td class="status">
                @switch($service['status'])
                    @case(0)
                        <span class="status-bg-green">正常</span>
                        @break
                    @case(1)
                        <span class="status-bg-red">异常</span>
                        @break
                    @case(2)
                        <span class="status-bg-blue">恢复</span>
                        @break
                    @case(3)
                        <span class="status-bg-orange">暂停</span>
                        @break
                @endswitch
            </td>
            <td style="color: #a8a9bb;">
                {!! implode('',$service['issues']) !!}
            </td>
            <td style="color: #a8a9bb;">{{$service['start']}}</td>
            <td style="color: #a8a9bb;">{{$service['end']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<style>
    .status {
        font-weight: 600;
    }

    .status > span {
        padding: 2px 4px;
        border-radius: 4px;
    }

    .status-bg-red {
        background: rgba(244, 134, 132, 0.1);
        color: rgba(244, 134, 132, 1);
    }

    .status-bg-orange {
        background: rgba(255, 153, 76, 0.1);
        color: rgba(255, 153, 76, 1);
    }

    .status-bg-green {
        background: rgba(76, 181, 171, 0.1);
        color: rgba(76, 181, 171, 1);
    }

    .status-bg-blue {
        background: rgba(99, 181, 247, 0.1);
        color: rgba(99, 181, 247, 1);
    }

    .status-recovery {
        color: rgba(76, 181, 171, 1);
    }

    .table-content > td {
        display: table-cell;
        vertical-align: middle
    }
</style>
