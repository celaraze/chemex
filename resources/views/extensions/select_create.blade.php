<div class="{{$viewClass['form-group']}}">

    <div class="{{ $viewClass['label'] }} control-label">
        <span>{!! $label !!}</span>
    </div>

    <div class="{{$viewClass['field']}}">

        <div class="input-group">
            @include('admin::form.error')

            <input type="hidden" name="{{$name}}"/>

            <select class="form-control {{$class}}" style="width: 100%;" name="{{$name}}" {!! $attributes !!} >
                <option value=""></option>
                @if($groups)
                    @foreach($groups as $group)
                        <optgroup label="{{ $group['label'] }}">
                            @foreach($group['options'] as $select => $option)
                                <option value="{{$select}}" {{ $select == $value ?'selected':'' }}>{{$option}}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                @else
                    @foreach($options as $select => $option)
                        <option
                            value="{{$select}}" {{ Dcat\Admin\Support\Helper::equal($select, $value) ?'selected':'' }}>{{$option}}</option>
                    @endforeach
                @endif
            </select>

            <div class="input-group-append">
                {!! $createDialog !!}
            </div>
        </div>

        @include('admin::form.help-block')

    </div>
</div>

@include('admin::form.select-script')
