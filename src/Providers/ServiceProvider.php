<?php

namespace Barbery\TellMe\Provider;

use GuzzleHttp\Client;

abstract class ServiceProvider
{
    protected $channel        = null;
    protected $translatedData = [];

    // seconds
    const HTTP_TIMEOUT = 6.0;

    public function __construct($channel)
    {
        $this->channel = $channel;
    }

    // 把配置的数据转换成实际数据
    public function translate($data)
    {
        foreach ($this->channel['data'] as $key => $value) {
            $this->translatedData[$key] = preg_replace_callback('/(\{(\w+)\})/', function ($matches) use ($data) {
                return isset($data[$matches[2]]) ? $data[$matches[2]] : '';
            }, $value);
        }

        return $this;
    }

    protected function get($key, $default = null)
    {
        return isset($this->channel[$key]) ? $this->channel[$key] : $default;
    }

    protected function httpPost($url, $data)
    {
        try {
            $Client = new Client(['timeout' => self::HTTP_TIMEOUT]);
            return $Client->request('POST', $url, ['form_params' => $data]);
        } catch (\Exception $e) {
            return false;
        }

    }

    // 各自的发送方法
    abstract public function send();
}
