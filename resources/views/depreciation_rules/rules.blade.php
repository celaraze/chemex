<table class="table">
    <thead>
    <tr>
        <th scope="col">周期</th>
        <th scope="col">尺度</th>
        <th scope="col">比率</th>
    </tr>
    </thead>
    <tbody>
    @foreach($value as $item)
        <tr>
            <td>{{$item['number']}}</td>
            <td>{{\App\Support\Data::timeScales()[$item['scale']]}}</td>
            <td>{{$item['ratio']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
