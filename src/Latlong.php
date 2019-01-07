<?php

namespace Encore\Admin\Latlong;

use Encore\Admin\Form\Field;
use Encore\Admin\Latlong\Map;

class Latlong extends Field
{
    /**
     * Column name.
     *
     * @var array
     */
    protected $column = [];

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
     * @var string
     */
    protected $view = 'laravel-admin-latlong::latlong';

    /**
     * Map height.
     *
     * @var int
     */
    protected $height = 300;

    /**
     * Get assets required by this field.
     *
     * @return array
     */
    public static function getAssets()
    {
        return ['js' => static::getProvider()->getAssets()];
    }

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
     * Latlong constructor.
     *
     * @param string $column
     * @param array $arguments
     */
    public function __construct($column, $arguments)
    {
        $this->column['lat'] = (string)$column;
        $this->column['lng'] = (string)$arguments[0];

        array_shift($arguments);

        $this->label = $this->formatLabel($arguments);
        $this->id    = $this->formatId($this->column);
    }

    /**
     * Set map height.
     *
     * @param int $height
     * @return $this
     */
    public function height($height = 300)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function render()
    {
        $this->script = static::getProvider()->applyScript($this->id);

        return parent::render()->with(['height' => $this->height]);
    }
}
