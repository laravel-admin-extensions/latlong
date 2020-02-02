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
        if (isset($id['address'])) {
            return <<<EOT
                        (function() {
                            function init(name) {
                                ymaps.ready(function(){

                                    var lat = $('#{$id['lat']}');
                                    var lng = $('#{$id['lng']}');
                                    var address = $('#{$id['address']}');

                                    var myMap = new ymaps.Map("map_"+name, {
                                        center: [lat.val(), lng.val()],
                                        zoom: 16
                                    });

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
                                        getAddress(myPlacemark.geometry.getCoordinates());
                                    });

                                    myMap.geoObjects.add(myPlacemark);

                                    function getAddress(coords) {
                                       ymaps.geocode(coords).then(function (res) {
                                           firstGeoObject = res.geoObjects.get(0);

                                           console.log(address);
                                           console.log(firstGeoObject.getAddressLine());
                                           address.val(firstGeoObject.getAddressLine());
                                        });
                                    }
                                });
                            }

                            init('{$id['lat']}{$id['lng']}');
                        })();
                EOT;
        } else {
            return <<<EOT
                        (function() {
                            function init(name) {
                                ymaps.ready(function(){

                                    var lat = $('#{$id['lat']}');
                                    var lng = $('#{$id['lng']}');
                                    var zoom = $('#{$id['zoom']}');

                                    if (zoom.length !== 0) {

                                       var myMap = new ymaps.Map("map_"+name, {
                                            center: [lat.val(), lng.val()],
                                            zoom: zoom.val(),
                                       });

                                       ZoomLayout = ymaps.templateLayoutFactory.createClass('<div></div>');

                                       zoomControl = new ymaps.control.ZoomControl({options: {layout: ZoomLayout}});
                                       myMap.controls.add(zoomControl);

                                    } else {

                                       var myMap = new ymaps.Map("map_"+name, {
                                            center: [lat.val(), lng.val()],
                                            zoom: 16
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
                                    });

                                    myMap.geoObjects.add(myPlacemark);

                                    function getAddress(coords) {
                                       ymaps.geocode(coords).then(function (res) {
                                           firstGeoObject = res.geoObjects.get(0);
                                        });
                                    }
                                });
                            }

                            init('{$id['lat']}{$id['lng']}');
                        })();
                EOT;
        }
    }
}
