<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id['lat']}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <div class="row">
            <div class="col-md-3">
                <input id="{{$id['lat']}}" name="{{$name['lat']}}" class="form-control" value="{{ old($column['lat'], $value['lat']) }}" {!! $attributes !!} />
            </div>
            <div class="col-md-3">
                <input id="{{$id['lng']}}" name="{{$name['lng']}}" class="form-control" value="{{ old($column['lng'], $value['lng']) }}" {!! $attributes !!} />
            </div>
        </div>

        <br>

        <div id="map_{{$id['lat'].$id['lng']}}" style="width: 100%;height: {{ $height }}px">
            <div id="panel" style="  position: absolute;
            background-color: white;
            max-height: 90%;
            overflow-y: auto;
            top: 10px;
            right: 10px;
            width: 280px;z-index: 20"></div>
        </div>

        @include('admin::form.help-block')

    </div>
</div>
