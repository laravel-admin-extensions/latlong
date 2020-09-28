<div {!! admin_attrs($group_attrs) !!}>

    <label for="{{$id['lat']}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <div class="row">
            <div class="col-3">
                <input id="{{$id['lng']}}" name="{{$name['lng']}}" class="form-control" value="{{ old($column['lng'], $value['lng']) }}" {!! $attributes !!} />
            </div>
            <div class="col-3">
                <input id="{{$id['lat']}}" name="{{$name['lat']}}" class="form-control" value="{{ old($column['lat'], $value['lat']) }}" {!! $attributes !!} />
            </div>

            @if($provider != 'yandex')
            <div class="col-3 offset-3">
                <div class="input-group">
                    <input type="text" class="form-control" id="search-{{$id['lat'].$id['lng']}}">
                    <span class="input-group-append">
                        <button type="button" class="btn btn-@color"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </div>
            @endif

        </div>

        <br>

        <div id="map_{{$id['lat'].$id['lng']}}" style="width: 100%;height: {{ $height }}px"></div>

        @include('admin::form.error')
        @include('admin::form.help-block')

    </div>
</div>
