<?php

namespace Barbery\TellMe\Providers;

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
        $this->translatedData = $this->_translate($this->channel['data'], $data);
        return $this;
    }

    private function _translate($format, $data)
    {
        $result = [];
        foreach ($format as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->_translate($value, $data);
            } else {
                $result[$key] = preg_replace_callback('/(\{(\w+)\})/', function ($matches) use ($data) {
                    if (!isset($data[$matches[2]])) {
                        return '';
                    } elseif (is_callable($data[$matches[2]])) {
                        return $data[$matches[2]]();
                    } else {
                        return $data[$matches[2]];
                    }
                }, $value);
            }
        }

        return $result;
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
        return true;
    }

    protected function httpPostJson($url, $data)
    {
        try {
            $Client = new Client(['timeout' => self::HTTP_TIMEOUT]);
            return $Client->request('POST', $url, [
                'json' => $data,
            ]);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    // 各自的发送方法
    abstract public function send();
}
