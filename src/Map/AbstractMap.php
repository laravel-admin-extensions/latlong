<?php

namespace Encore\Admin\Latlong\Map;

abstract class AbstractMap
{
    /**
     * @var string
     */
    protected $api;

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

    /**
     * @param array $id
     * @return string
     */
    abstract public function applyScript(array $id,array $callbacks=[]);
}
