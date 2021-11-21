<?php

namespace Encore\Admin\Latlong\Map;

class Google extends AbstractMap
{
    /**
     * @var string
     */
    protected $api = '//maps.googleapis.com/maps/api/js?v=3.exp&key=%s&libraries=places';

    /**
     * {@inheritdoc}
     */
    public function applyScript(array $id)
    {
        $autoPosition = ($this->autoPosition)?'1':'0';
        return <<<EOT
        (function() {
            function init(name) {
                var lat = $('#{$id['lat']}');
                var lng = $('#{$id['lng']}');
    
                var LatLng = new google.maps.LatLng(lat.val(), lng.val());
    
                var options = {
                    zoom: {$this->getParams('zoom')},
                    center: LatLng,
                    panControl: false,
                    zoomControl: true,
                    scaleControl: true,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }
    
                var container = document.getElementById("map_"+name);
                var map = new google.maps.Map(container, options);
                
                if (navigator.geolocation && {$autoPosition}) {
                  navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = {
                      lat: position.coords.latitude,
                      lng: position.coords.longitude
                    };
                    map.setCenter(pos);
                    marker.setPosition(pos);
                    
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

                var autocomplete = new google.maps.places.Autocomplete(
                    document.getElementById("search-{$id['lat']}{$id['lng']}")
                );
                autocomplete.bindTo('bounds', map);

                google.maps.event.addListener(autocomplete, 'place_changed', function() {
                    var place = autocomplete.getPlace();
                    var location = place.geometry.location;
                    
                    if (place.geometry.viewport) {
                      map.fitBounds(place.geometry.viewport);
                    } else {
                      map.setCenter(location);
                      map.setZoom(18);
                    }

                    marker.setPosition(location);

                    lat.val(location.lat());
                    lng.val(location.lng());
                });
            }

            init('{$id['lat']}{$id['lng']}');
        })();
EOT;
    }
}
