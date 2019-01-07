经纬度选择器
======

这个扩展用来帮助你在form表单中选择经纬度，用来替代`Laravel-admin`中内置的`Form\Field\Map`组件, 组件支持的地图包括`Google map`、`百度地图`、`高德地图`、`腾讯地图`、`Yadex map`.

## Installation

```bash
composer require laravel-admin-ext/latlong -vvv
```

## Configuration

打开config/admin.php，按照你的情况在extensions部分加上如下的配置：

```php

    'extensions' => [

        'latlong' => [

            // 是否开始这个组件，默认true
            'enable' => true,

            // 选择下面指定的provider
            'default' => 'yandex',

            // 根据上面的选择，填写相应地图的api_key，api_key需要到相应的平台去自行申请
            'providers' => [

                'google' => [
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

假设你的表中有两个字段`latitude`和`longitude`分别表示纬度和经度，那么在表单中使用如下：
```php
$form->latlong('latitude', 'longitude', '经纬度选择');

// 设置地图高度
$form->latlong('latitude', 'longitude', '经纬度')->height(500);

// 设置默认值
$form->latlong('latitude', 'longitude', '经纬度')->default(['lat' => 90, 'lng' => 90]);
```

## Donate

如果觉得这个项目帮你节约了时间，不妨支持一下;)

![-1](https://cloud.githubusercontent.com/assets/1479100/23287423/45c68202-fa78-11e6-8125-3e365101a313.jpg)

License
------------
Licensed under [The MIT License (MIT)](LICENSE).