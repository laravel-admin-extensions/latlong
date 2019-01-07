<?php

namespace Encore\Admin\Latlong\Map;

class Google extends AbstractMap
{
    /**
     * @var string
     */
    protected $api = '//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=%s';

    /**
     * {@inheritdoc}
     */
    public function applyScript(array $id)
    {
        return <<<EOT
        (function() {
            function init(name) {
                var lat = $('#{$id['lat']}');
                var lng = $('#{$id['lng']}');
    
                var LatLng = new google.maps.LatLng(lat.val(), lng.val());
    
                var options = {
                    zoom: 13,
                    center: LatLng,
                    panControl: false,
                    zoomControl: true,
                    scaleControl: true,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }
    
                var container = document.getElementById("map_"+name);
                var map = new google.maps.Map(container, options);
                
                if (navigator.geolocation) {
                  navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = {
                      lat: position.coords.latitude,
                      lng: position.coords.longitude
                    };
                    map.setCenter(pos);
                    
                    lat.val(position.coords.latitude);
                    lng.val(position.coords.longitude);
                    
                  }, function() {
                    
                  });
                }
            
                var marker = new google.maps.Marker({
                    position: LatLng,
                    map: map,
                    title: 'Drag Me!',
                    draggable: true
                });
                
                google.maps.event.addListener(marker, "position_changed", function(event) {
                  var position = marker.getPosition();
                  
                   lat.val(position.lat());
                   lng.val(position.lng());
                });
                
                google.maps.event.addListener(map, 'click', function(event) {
                    marker.setPosition(event.latLng);
                });
            }
    
            init('{$id['lat']}{$id['lng']}');
        })();
EOT;
    }
}