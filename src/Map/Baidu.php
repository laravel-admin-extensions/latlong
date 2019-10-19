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
        var point = new BMap.Point(lng.val(), lat.val());
        map.centerAndZoom(point, {$this->getParams('zoom')});
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

        var ac = new BMap.Autocomplete(
            {"input" : "search-{$id['lat']}{$id['lng']}"
            ,"location" : map
        });

        var address;
        ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
            var _value = e.item.value;
            address = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
            setPlace();
        });

        function setPlace(){
            function myFun(){
                var pp = local.getResults().getPoi(0).point;
                map.centerAndZoom(pp, {$this->getParams('zoom')});

                marker.setPosition(pp);
                lat.val(pp.lat);
                lng.val(pp.lng);
            }
            var local = new BMap.LocalSearch(map, {
              onSearchComplete: myFun
            });
            local.search(address);
        }
	}

    init('map_{$id['lat']}{$id['lng']}');
    
})();
EOT;
    }
}