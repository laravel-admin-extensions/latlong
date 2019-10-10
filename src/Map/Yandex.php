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
    public function applyScript(array $id,$callbacks=[])
    {
        $addjs='';
        if (isset($callbacks['address']) && !empty($callbacks['address'])){
            $addjs.=<<<JS
var addr_callback_{$id['lat']}{$id['lng']}=1;
JS;
        }
        else{
            $callbacks['address']='console.log';
        }
        if (isset($callbacks['metro']) && !empty($callbacks['metro'])){
            $addjs.=<<<JS
var metro_callback_{$id['lat']}{$id['lng']}=1;
JS;
        }
        else{
            $callbacks['metro']='console.log';
        }
        if (isset($callbacks['district']) && !empty($callbacks['district'])){
            $addjs.=<<<JS
var district_callback_{$id['lat']}{$id['lng']}=1;
JS;
        }
        else{
            $callbacks['district']='console.log';
        }
        if (isset($callbacks['map']) && !empty($callbacks['map'])){
            $addjs.=<<<JS
var map_callback_{$id['lat']}{$id['lng']}=1;
JS;
        }
        if (isset($callbacks['placemark']) && !empty($callbacks['placemark'])){
            $addjs.=<<<JS
var placemark_callback_{$id['lat']}{$id['lng']}=1;
JS;
        }
        return <<<JS
{$addjs}
        (function() {
            function init(name) {
                ymaps.ready(function(){
                    function filladdress(coords){
                        if(typeof addr_callback_{$id['lat']}{$id['lng']}!=='undefined'){
                            ymaps.geocode(coords).then(function (res) {
                                if(res.geoObjects.getLength()>0){
                                    var firstGeoObject = res.geoObjects.get(0);
                                    
                                    var administrativeAreas=firstGeoObject.getAdministrativeAreas();
                                    var localities=firstGeoObject.getLocalities();
                                    var thoroughfare=firstGeoObject.getThoroughfare();
                                    var premiseNumber=firstGeoObject.getPremiseNumber();
                                    var premise=firstGeoObject.getPremise();
                                    var country=firstGeoObject.getCountry();
                                    var countryCode=firstGeoObject.getCountryCode();
                                    var addressLine=firstGeoObject.getAddressLine();
                                    var address={
                                        country:country,
                                        countryCode:countryCode,
                                        administrativeAreas:administrativeAreas,
                                        localities:localities,
                                        thoroughfare:thoroughfare,
                                        premiseNumber:premiseNumber,
                                        premise:premise,
                                        addressLine:addressLine,
                                    };
                                    {$callbacks['address']}(address);
                                }
                            });
                        }
                        if(typeof metro_callback_{$id['lat']}{$id['lng']}!=='undefined'){
                            ymaps.geocode(coords, {
                                kind: 'metro',
                                results: 10,
                                json: true,                          
                            }).then(function (res) {
                                var metrolist = res.GeoObjectCollection.featureMember;
                                {$callbacks['metro']}(metrolist);
                            });
                        }
                        if(typeof district_callback_{$id['lat']}{$id['lng']}!=='undefined'){
                            ymaps.geocode(coords, {
                                kind: 'district',                           
                                json: true,                          
                            }).then(function (res) {
                                var dstlist = res.GeoObjectCollection.featureMember;
                                var districts=[];
                                dstlist.forEach(function(dst) {
                                  districts.push(dst.GeoObject.name);
                                });
                                {$callbacks['district']}(districts.reverse());
                            });
                        }
                    }
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
                        filladdress([lat.val(),lng.val()]);
                    });                
    
                    myMap.geoObjects.add(myPlacemark);
                    
                    myMap.events.group().add('click', function (e) {
                        coords = e.get('coords');
                        myPlacemark.geometry.setCoordinates(coords);
                        filladdress(coords);
                        lat.val(coords[0]);
                        lng.val(coords[1]);
                    });
                    
                    lat.on('change',function(){
                        if (lat.val().length>0 && isFinite(lat.val())){
                            myPlacemark.geometry.setCoordinates([lat.val(),lng.val()]);
                            myMap.setCenter([lat.val(),lng.val()]);
                            filladdress([lat.val(),lng.val()]);
                        }
                    });
                    lng.on('change',function(){
                        if (lng.val().length>0 && isFinite(lng.val())){
                            myPlacemark.geometry.setCoordinates([lat.val(),lng.val()]);
                            myMap.setCenter([lat.val(),lng.val()]);
                            filladdress([lat.val(),lng.val()]);
                        }
                    });
                    
                    ymaps.geolocation.get({
                        mapStateAutoApply: true
                    }).then(function (result) {
                        if (lat.val().length==0 || lng.val().length==0){
                            var pos=result.geoObjects.position;
                            lat.val(pos[0]);
                            lng.val(pos[1]);
                            myPlacemark.geometry.setCoordinates(pos);
                            myMap.setCenter(pos);
                        }
                    });
                    
                    if(typeof map_callback_{$id['lat']}{$id['lng']}!=='undefined'){
                        {$callbacks['map']}(myMap);
                    }
                    if(typeof placemark_callback_{$id['lat']}{$id['lng']}!=='undefined'){
                        {$callbacks['placemark']}(myPlacemark);
                    }
                });
            }
            
            init('{$id['lat']}{$id['lng']}');
        })();
JS;
    }
}
