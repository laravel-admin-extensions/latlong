<?php

namespace Encore\Admin\Latlong\Map;


abstract class AbstractMap
{
    /**
     * Set to true to automatically get the current position from the browser
     * @var bool
     */
    protected $autoPosition = false;

    /**
     * @var string
     */
    protected $api;

    /**
     * @var array
     */
    protected $params;

    /**
     * Tencent constructor.
     * @param $key
     */
    public function __construct($key = '')
    {
        if ($key) {
            $this->api = sprintf($this->api, $key);
        }
    }

    /**
     * @return array
     */
    public function getAssets()
    {
        return [$this->api];
    }

    public function getParams($field = null) {
        if($field) {
            return isset($this->params[$field]) ? $this->params[$field] : null;
        }
        return $this->params;
    }

    /**
     * Set true to automatically get the current position from the browser on page load
     * @param $bool
     * @return $this
     */
    public function setAutoPosition($bool) {
        $this->autoPosition = $bool;
        return $this;
    }

    public function setParams($params) {
        $this->params = $params;
        return $this;
    }

    /**
     * @param array $id
     * @param bool $autoPosition
     * @return string
     */
    abstract public function applyScript(array $id);
}