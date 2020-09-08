<?php

namespace Encore\Admin\Latlong\Map;

class Amap extends AbstractMap
{
    protected $api = '//webapi.amap.com/maps?v=1.4.12&key=%s';

    public function applyScript(array $id)
    {
        return <<<EOT
(function() {
    
    function init(name) {
        
        var lat = $('#{$id['lat']}');
        var lng = $('#{$id['lng']}');
    
        var map = new AMap.Map(name, {
            zoom: {$this->getParams('zoom')},
            center: [lng.val() || 0, lat.val() || 0],//中心点坐标
            viewMode:'3D',//使用3D视图
        });
        
        var marker = new AMap.Marker({
            map: map,
            draggable: true,
            position: [lng.val() || 0, lat.val() || 0],
        })

        map.on('click', function(e) {
            marker.setPosition(e.lnglat);
            
            lat.val(e.lnglat.getLat());
            lng.val(e.lnglat.getLng());
        });
        
        marker.on('dragend', function (e) {
            lat.val(e.lnglat.getLat());
            lng.val(e.lnglat.getLng());
        });
        
        if( ! lat.val() || ! lng.val()) {
            map.plugin('AMap.Geolocation', function () {
                geolocation = new AMap.Geolocation();
                map.addControl(geolocation);
                geolocation.getCurrentPosition();
                AMap.event.addListener(geolocation, 'complete', function (data) {
                    marker.setPosition(data.position);
                    
                    lat.val(data.position.getLat());
                    lng.val(data.position.getLng());
                });
            });
        }

        AMap.plugin('AMap.Autocomplete',function(){
            var autoOptions = {
                input:"search-{$id['lat']}{$id['lng']}"
            };
            var autocomplete= new AMap.Autocomplete(autoOptions);

            AMap.event.addListener(autocomplete, "select", function(data){
                map.setZoomAndCenter(18, data.poi.location);
                marker.setPosition(data.poi.location);
                lat.val(data.poi.location.lat);
                lng.val(data.poi.location.lng);
            });
        });
    }

    init('map_{$id['lat']}{$id['lng']}');
})();
EOT;
    }
}
