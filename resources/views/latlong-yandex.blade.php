<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id['lat']}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <div class="row">
            <div class="col-md-3">
                <input id="{{$id['lng']}}" name="{{$name['lng']}}" class="form-control" value="{{ old($column['lng'], $value['lng']) }}" {!! $attributes !!} />
            </div>
            <div class="col-md-3">
                <input id="{{$id['lat']}}" name="{{$name['lat']}}" class="form-control" value="{{ old($column['lat'], $value['lat']) }}" {!! $attributes !!} />
            </div>
            <?php if (isset($id['address'])): ?>
                <div class="col-md-6">
                    <input id="{{$id['address']}}" name="{{$name['address']}}" class="form-control" value="{{ old($column['address'], (isset($value['address']))?$value['address']:'')}}" {!! $attributes !!} />
                </div>
            <?php endif; ?>
            <?php if (isset($id['zoom'])): ?>
                <div class="col-md-3">
                    <input id="{{$id['zoom']}}" name="{{$name['zoom']}}" class="form-control" value="{{ old($column['zoom'], (isset($value['zoom']))?$value['zoom']:'')}}" {!! $attributes !!} />
                </div>
            <?php endif; ?>

            @if($provider != 'yandex')
            <div class="col-md-3 col-md-offset-3">
                <div class="input-group">
                    <input type="text" class="form-control" id="search-{{$id['lat'].$id['lng']}}">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </div>
            @endif

        </div>

        <br>

        <div id="map_{{$id['lat'].$id['lng']}}" style="width: 100%;height: {{ $height }}px"></div>

        @include('admin::form.help-block')

    </div>
</div>
<div id="test" style="display: none">
    <div>
        <div id='zoom-in' class='btn'><i class='icon-plus'>+</i></div><br>
        <div id='zoom-out' class='btn'><i class='icon-minus'>-</i></div>
    </div>
</div>


<script>
    $(document).ready(function () {
        function init(name) {
            ymaps.ready(function(){

                var lat = $('#{{$id['lat']}}');
                var lng = $('#{{$id['lng']}}');
                var address = $('#{{ isset($id['address'])?$id['address']:null }}');
                var zoom = $('#{{ isset($id['zoom'])?$id['zoom']:null }}');
                var test = $('#test').html();

                if (zoom.length !== 0) {

                    if (zoom.val() == 0) {
                        zoom.val(16);
                    }

                    var myMap = new ymaps.Map("map_"+name, {
                        center: [lat.val(), lng.val()],
                        zoom: zoom.val(),
                        controls: [],
                    });

                    ZoomLayout = ymaps.templateLayoutFactory.createClass(test, {
                        build: function () {
                            ZoomLayout.superclass.build.call(this);
                            this.zoomInCallback = ymaps.util.bind(this.zoomIn, this);
                            this.zoomOutCallback = ymaps.util.bind(this.zoomOut, this);

                            $('#zoom-in').bind('click', this.zoomInCallback);
                            $('#zoom-out').bind('click', this.zoomOutCallback);
                        },

                        clear: function () {
                            $('#zoom-in').unbind('click', this.zoomInCallback);
                            $('#zoom-out').unbind('click', this.zoomOutCallback);

                            ZoomLayout.superclass.clear.call(this);
                        },

                        zoomIn: function () {
                            var map = this.getData().control.getMap();
                            map.setZoom(map.getZoom() + 1, {checkZoomRange: true});
                            zoom.val(map.getZoom() + 1);
                        },

                        zoomOut: function () {
                            var map = this.getData().control.getMap();
                            map.setZoom(map.getZoom() - 1, {checkZoomRange: true});
                            zoom.val(map.getZoom() - 1);
                        }
                    });

                    zoomControl = new ymaps.control.ZoomControl({options: {layout: ZoomLayout}});
                    myMap.controls.add(zoomControl);

                } else {
                    var myMap = new ymaps.Map("map_"+name, {
                        center: [lat.val(), lng.val()],
                        zoom: 16,
                    });
                }

                var myPlacemark = new ymaps.Placemark([lat.val(), lng.val()], {
                }, {
                    preset: 'islands#redDotIcon',
                    draggable: true
                });

                myMap.events.add('click', function (e) {
                    var coords = e.get('coords');
                    myPlacemark.geometry.setCoordinates(coords);
                    lat.val(myPlacemark.geometry.getCoordinates()[0]);
                    lng.val(myPlacemark.geometry.getCoordinates()[1]);

                    if (address.length !== 0) {
                        getAddress(myPlacemark.geometry.getCoordinates());
                    }
                });

                myMap.geoObjects.add(myPlacemark);

                function getAddress(coords) {
                    ymaps.geocode(coords).then(function (res) {
                        firstGeoObject = res.geoObjects.get(0);
                        address.val(firstGeoObject.getAddressLine());
                    });
                }


            });
        }

        init('{{$id['lat']}}{{$id['lng']}}');
    });
</script>

<style>
    ymaps .btn {
        border-color: #c5c5c5;
        border-color:
                rgba(0,0,0,0.15) rgba(0,0,0,0.15)
                rgba(0,0,0,0.25);
    }
    ymaps .btn {
        display: inline-block;
        *display: inline;
        padding: 4px 12px;
        margin-bottom: 0;
        *margin-left: .3em;
        font-size: 14px;
        line-height: 20px;
        color:
                #333;
        text-align: center;
        text-shadow: 0 1px 1px
        rgba(255,255,255,0.75);
        vertical-align: middle;
        cursor: pointer;
        background-color:
                #f5f5f5;
        *background-color: #e6e6e6;
        background-image: -moz-linear-gradient(top,#fff,#e6e6e6);
        background-image: -webkit-gradient(linear,0 0,0 100%,from(#fff),to(#e6e6e6));
        background-image: -webkit-linear-gradient(top,#fff,#e6e6e6);
        background-image: -o-linear-gradient(top,#fff,#e6e6e6);
        background-image: linear-gradient(to bottom,
        #fff,
        #e6e6e6);
        background-repeat: repeat-x;
        border: 1px solid
        #bbb;
        border-top-color: rgb(187, 187, 187);
        border-right-color: rgb(187, 187, 187);
        border-bottom-color: rgb(187, 187, 187);
        border-left-color: rgb(187, 187, 187);
        *border: 0;
        border-color: #e6e6e6 #e6e6e6 #bfbfbf;
        border-color: rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);
        border-bottom-color: #a2a2a2;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff',endColorstr='#ffe6e6e6',GradientType=0);
        filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
        *zoom: 1;
        -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
        -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
        box-shadow: inset 0 1px 0
        rgba(255,255,255,0.2),0 1px 2px
        rgba(0,0,0,0.05);
    }

    ymaps .icon-plus {
        background-position: -408px -96px;
    }
    ymaps [class^="icon-"], [class*=" icon-"] {
        display: inline-block;
        width: 14px;
        height: 14px;
        margin-top: 1px;
        *margin-right: .3em;
        line-height: 14px;
        vertical-align: text-top;
        background-image: '+';
        background-position: 14px 14px;
        background-repeat: no-repeat;
    }
</style>