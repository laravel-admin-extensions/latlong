<?php

namespace Encore\Admin\Latlong\Map;

class Yandex extends AbstractMap
{
    /**
     * @var string
     */
    protected $api = 'https://api-maps.yandex.ru/2.1/?apikey=%s&lang=ru_RU';

    /**
     * {@inheritdoc}
     */
    public function applyScript(array $id)
    {
        return <<<JS
        (function() {
            function init(name) {
                ymaps.ready(function(){
        
                    var lat = $('#{$id['lat']}');
                    var lng = $('#{$id['lng']}');
        
                    var myMap = new ymaps.Map("map_"+name, {
                        center: [lat.val(), lng.val()],
                        zoom: 17,
                        controls: ['zoomControl', 'typeSelector', 'fullscreenControl', 'rulerControl','geolocationControl']
                    }); 
    
                    var myPlacemark = new ymaps.Placemark([lat.val(), lng.val()], {
                    }, {
                        preset: 'islands#redDotIcon',
                        draggable: true
                    });
    
                    myPlacemark.events.add(['dragend'], function (e) {
                        lat.val(myPlacemark.geometry.getCoordinates()[0]);
                        lng.val(myPlacemark.geometry.getCoordinates()[1]);
                    });                
    
                    myMap.geoObjects.add(myPlacemark);
                    
                    myMap.events.group().add('click', function (e) {
                        coords = e.get('coords');
                        myPlacemark.geometry.setCoordinates(coords);
                        lat.val(coords[0]);
                        lng.val(coords[1]);
                    });
                    
                    lat.on('change',function(){
                        if (lat.val().length>0 && isFinite(lat.val())){
                            myPlacemark.geometry.setCoordinates([lat.val(),lng.val()]);
                            myMap.setCenter([lat.val(),lng.val()]);
                        }
                    });
                    lng.on('change',function(){
                        if (lng.val().length>0 && isFinite(lng.val())){
                            myPlacemark.geometry.setCoordinates([lat.val(),lng.val()]);
                            myMap.setCenter([lat.val(),lng.val()]);
                        }
                    });
                    
                    ymaps.geolocation.get({
                        mapStateAutoApply: true
                    }).then(function (result) {
                        if (lat.val().length==0 || lng.val().length==0)
                        var pos=result.geoObjects.position;
                        lat.val(pos[0]);
                        lng.val(pos[1]);
                        lat.trigger('change');
                    });
                });
            }
            
            init('{$id['lat']}{$id['lng']}');
        })();
JS;
    }
}
