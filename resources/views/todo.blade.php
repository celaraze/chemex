@foreach($notifications as $key=>$notification)
    @if($notification['deleted_at'] == null)
        <div>
            <a href="{{$notification['data']['url']}}"
               target="_blank">{{($key+1).'：'.$notification['data']['title'].'，'.$notification['data']['content']}}</a>
        </div>
    @endif
@endforeach
