<?php

namespace Encore\Admin\Latlong\Map;

class Baidu extends AbstractMap
{
    protected $api = '//api.map.baidu.com/api?v=2.0&ak=%s';

    public function applyScript(array $id)
    {
        return <<<EOT
(function() {

    function init(name) {
        var lat = $('#{$id['lat']}');
        var lng = $('#{$id['lng']}');
    
        var map = new BMap.Map(name);
        var point = new BMap.Point(lat.val(), lng.val());
        map.centerAndZoom(point, 12);
        map.enableScrollWheelZoom(true);
        
        var marker = new BMap.Marker(point);
        map.addOverlay(marker);
        marker.enableDragging();
        
        if( ! lat.val() || ! lng.val()) {
            var geolocation = new BMap.Geolocation();
            geolocation.getCurrentPosition(function(e){
                if(this.getStatus() == BMAP_STATUS_SUCCESS){
                    map.panTo(e.point);
                    marker.setPosition(e.point);
                    
                    lat.val(e.point.lat);
                    lng.val(e.point.lng);
                    
                }
                else {
                    console.log('failed'+this.getStatus());
                }
            },{enableHighAccuracy: true})
        }
        
        map.addEventListener("click", function(e){
            marker.setPosition(e.point);
            lat.val(e.point.lat);
            lng.val(e.point.lng);
        });
        
	    marker.addEventListener("dragend", function(e){
	        lat.val(e.point.lat);
            lng.val(e.point.lng);
        });
	}

    init('map_{$id['lat']}{$id['lng']}');
    
})();
EOT;
    }
}