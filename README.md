经纬度选择器/Latitude and longitude selector
======

这个扩展用来帮助你在form表单中选择经纬度，用来替代`Laravel-admin`中内置的`Form\Field\Map`组件, 组件支持的地图包括`Google map`、`百度地图`、`高德地图`、`腾讯地图`、`Yandex map`.

This extension is used to help you select the latitude and longitude in the form, which is used to replace the  `Laravel-admin` built in `Form\Field\Map` component. The supported maps include `Google map`, `Baidu map`, `AMap`, `Tencent Map`, `Yandex map`.

## Installation

```bash
// For laravel-admin 1.x
composer require laravel-admin-ext/latlong:1.x -vvv

// For laravel-admin 2.x
composer require laravel-admin-ext/latlong:2.x -vvv
```

## Configuration

Open `config/admin.php` and add the following configuration to the extensions section:

```php

    'extensions' => [

        'latlong' => [

            // Whether to enable this extension, defaults to true
            'enable' => true,

            // Specify the default provider
            'default' => 'google',

            // According to the selected provider above, fill in the corresponding api_key
            'providers' => [

                'google' => [
                    'api_key' => '',
                ],
                
                'yandex' => [
                    'api_key' => '',
                ],

                'baidu' => [
                    'api_key' => 'xck5u2lga9n1bZkiaXIHtMufWXQnVhdx',
                ],

                'tencent' => [
                    'api_key' => 'VVYBZ-HRJCX-NOJ4Z-ZO3PU-ZZA2J-QPBBT',
                ],

                'amap' => [
                    'api_key' => '3693fe745aea0df8852739dac08a22fb',
                ],
            ]
        ]
    ]

```

## Usage

Suppose you have two fields `latitude` and `longitude` in your table that represent latitude and longitude, then use the following in the form:

```php
$form->latlong('latitude', 'longitude', 'Position');

// Set the map height
$form->latlong('latitude', 'longitude', 'Position')->height(500);

// Set the map zoom
$form->latlong('latitude', 'longitude', 'Position')->zoom(16);

// Set default position
$form->latlong('latitude', 'longitude', 'Position')->default(['lat' => 90, 'lng' => 90]);
```

Use in show page

```php
$show->field('Position')->latlong('lat_column', 'long_column', $height = 400, $zoom = 16);
```

## Donate

如果觉得这个项目帮你节约了时间，不妨支持一下;)

![-1](https://cloud.githubusercontent.com/assets/1479100/23287423/45c68202-fa78-11e6-8125-3e365101a313.jpg)

License
------------
Licensed under [The MIT License (MIT)](LICENSE).
