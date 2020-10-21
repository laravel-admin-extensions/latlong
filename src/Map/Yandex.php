<?php

namespace Encore\Admin\Latlong\Map;

class Yandex extends AbstractMap
{
    /**
     * @var string
     */
    protected $api = '//api-maps.yandex.ru/2.1/?apikey=%s&lang=ru_RU';

    /**
     * {@inheritdoc}
     */
    public function applyScript(array $id)
    {
        return <<<EOT
        (function() {
            function init(name) {
                ymaps.ready(function(){
        
                    var lat = $('#{$id['lat']}');
                    var lng = $('#{$id['lng']}');
        
                    var myMap = new ymaps.Map("map_"+name, {
                        center: [lat.val(), lng.val()],
                        zoom: {$this->getParams('zoom')}
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
                });
    
            }
            
            init('{$id['lat']}{$id['lng']}');
        })();
EOT;
    }
}
