<?php

namespace Encore\Admin\Latlong;

use Encore\Admin\Form\Field;

class Latlong extends Field
{
    /**
     * Set to true to automatically get the current position from the browser
     * @var bool
     */
    protected $autoPosition = false;
    /**
     * Column name.
     *
     * @var array
     */
    protected $column = [];

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
     * Map Zoom
     *
     * @var int
     */
    protected $zoom = 16;

    /**
     * Get assets required by this field.
     *
     * @return array
     */
    public static function getAssets()
    {
        return ['js' => Extension::getProvider()->getAssets()];
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
    public function height(int $height)
    {
        $this->height = $height;

        return $this;
    }


    /**
     * Set map zoom.
     *
     * @param int $zoom
     * @return $this
     */
    public function zoom(int $zoom)
    {
        $this->zoom = $zoom;

        return $this;
    }

    /**
     * Set true to automatically get the current position from the browser on page load
     * @param $bool
     * @return Latlong
     */
    public function setAutoPosition($bool) {
        $this->autoPosition = $bool;
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function render()
    {
        $this->script = Extension::getProvider()
            ->setParams([
                'zoom' => $this->zoom
            ])
            ->setAutoPosition($this->autoPosition)
            ->applyScript($this->id);

        $variables = [
            'height'   => $this->height,
            'provider' => Extension::config('default'),
        ];

        $this->addVariables($variables);
        
        return parent::fieldRender();
    }
}
