<?php

namespace Encore\Admin\Latlong;

use Encore\Admin\Admin;
use Encore\Admin\Extension as BaseExtension;

class Extension extends BaseExtension
{
    public $name = 'latlong';

    public $views = __DIR__.'/../resources/views';

    /**
     * @var array
     */
    protected static $providers = [
        'baidu'   => Map\Baidu::class,
        'tencent' => Map\Tencent::class,
        'amap'    => Map\Amap::class,
        'google'  => Map\Google::class,
        'yandex'  => Map\Yandex::class,
    ];

    /**
     * @var Map\AbstractMap
     */
    protected static $provider;

    /**
     * @param string $name
     * @return Map\AbstractMap
     */
    public static function getProvider($name = '')
    {
        if (static::$provider) {
            return static::$provider;
        }

        $name = Extension::config('default', $name);
        $args = Extension::config("providers.$name", []);

        return static::$provider = new static::$providers[$name](...array_values($args));
    }

    /**
     * @return \Closure
     */
    public static function showField()
    {
        return function ($lat, $lng, $address, $zoom, $height = 300) {

            return $this->unescape()->as(function () use ($lat, $lng, $address, $zoom, $height) {

                $lat = $this->{$lat};
                $lng = $this->{$lng};
                $address = $this->{$address};
                $id = ['lat' => 'lat', 'lng' => 'lng', 'address' => 'address', 'zoom' => 'zoom'];
                Admin::script(Extension::getProvider()
                    ->setParams([
                        'zoom' => $zoom
                    ])
                    ->applyScript($id));

                if (isset($address)) {
                    return <<<HTML
<div class="row">
    <div class="col-md-3">
        <input id="{$id['lat']}" class="form-control" value="{$lat}"/>
    </div>
    <div class="col-md-3">
        <input id="{$id['lng']}" class="form-control" value="{$lng}"/>
    </div>
    <div class="col-md-4">
        <input id="{$id['address']}" class="form-control" value="{$address}"/>
    </div>
</div>

<br>

<div id="map_{$id['lat']}{$id['lng']}" style="width: 100%;height: {$height}px"></div>
HTML;
                }       else {
                    return <<<HTML
<div class="row">
    <div class="col-md-3">
        <input id="{$id['lat']}" class="form-control" value="{$lat}"/>
    </div>
    <div class="col-md-3">
        <input id="{$id['lng']}" class="form-control" value="{$lng}"/>
    </div>
</div>

<br>

<div id="map_{$id['lat']}{$id['lng']}" style="width: 100%;height: {$height}px"></div>
HTML;
                }


            });
        };
    }
}
