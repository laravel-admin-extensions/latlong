<?php

namespace Encore\Admin\Latlong;

use Encore\Admin\Form\Field;

class Latlong extends Field
{
    /**
     * Column name.
     *
     * @var array
     */
    protected $column = [];

    protected $callbacks;

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
     * {@inheritdoc}
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function render()
    {
        $this->script = Extension::getProvider()->applyScript($this->id,$this->callbacks);

        $variables = [
            'height'   => $this->height,
            'provider' => Extension::config('default'),
        ];

        return parent::render()->with($variables);
    }

    public function callbacks($callbacks=[]){
        $this->callbacks=$callbacks;
    }
}
