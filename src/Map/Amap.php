<?php

namespace Encore\Admin\Latlong\Map;

class Amap extends AbstractMap
{
    protected $api = '//webapi.amap.com/maps?v=1.4.12&key=%s';

    public function applyScript(array $id)
    {
        return <<<EOT
(function() {
    
    var geocoder;
    var placeSearch;
    function init(name) {
        
        var lat = $('#{$id['lat']}');
        var lng = $('#{$id['lng']}');
    
        var map = new AMap.Map(name, {
            zoom:11,
            center: [lng.val()|| 0, lat.val()|| 0],//中心点坐标
            viewMode:'3D'//使用3D视图
        });
        
        var marker = new AMap.Marker({
            map: map,
            draggable: true,
            position: [lng.val() || 0, lat.val()|| 0],
        })
        
       AMap.service(["AMap.PlaceSearch"], function() {
        //构造地点查询类
        placeSearch = new AMap.PlaceSearch({ 
            pageSize: 5, // 单页显示结果条数
            pageIndex: 1, // 页码
            city: "全国", // 兴趣点城市
            citylimit: true,  //是否强制限制在设置的城市内搜索
            map: map, // 展现结果的地图实例
            panel: "panel", // 结果列表将在此容器中进行展示。
            autoFitView: true // 是否自动调整地图视野使绘制的 Marker点都处于视口的可见范围
        });
    
    });
          
         
        AMap.service('AMap.Geocoder',function(){//回调函数
             //实例化Geocoder
              geocoder = new AMap.Geocoder({
                city: "全国"//城市，默认：“全国”
              });
            //TODO: 使用geocoder 对象完成相关功能
         });
           
        map.on('click', function(e) {
            marker.setPosition(e.lnglat);
            
            lat.val(e.lnglat.getLat());
            lng.val(e.lnglat.getLng());
             writeAddress([e.lnglat.getLng(),e.lnglat.getLat()]);
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
    }
    
      // 填写地址
    function writeAddress(lnglatXY){
     geocoder.getAddress(lnglatXY, function(status, result) {
         if (status === 'complete' && result.info === 'OK') {
             geocoder_CallBack(result);
         }else{
          layer.msg('未找到相关地址');
         }
     }); 
    }
   
    // 地址回调
    function geocoder_CallBack(data) {
         var address = data.regeocode.formattedAddress; //返回地址描述
         $("#address").val(address);
    }
    $('#address').blur(function(){
        placeSearch.search($(this).val());
    });
    init('map_{$id['lat']}{$id['lng']}');
})();
EOT;
    }
}