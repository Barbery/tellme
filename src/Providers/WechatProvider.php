<?php

namespace Barbery\TellMe\Providers;

class WechatProvider extends ServiceProvider
{
    private $wechat = null;
    private $sender = '';

    public function __construct($channel)
    {
        parent::__construct($channel);
        $this->check();
    }

    private function check()
    {
        if (class_exists('\EasyWeChat\Foundation\Application')) {
            if ($this->get('easywechat') instanceof \EasyWeChat\Foundation\Application) {
                $this->wechat = $this->channel['easywechat'];
            } else {
                $this->wechat = new \EasyWeChat\Foundation\Application($this->get('wechat', []));
            }
            $this->sender = 'notice';
        } elseif (class_exists('\EasyWeChat\Factory')) {
            $this->easywechatVersion = 4;
            if ($this->get('easywechat') instanceof \EasyWeChat\OfficialAccount\Application) {
                $this->wechat = $this->channel['easywechat'];
            } else {
                $this->wechat = \EasyWeChat\Factory::officialAccount($this->get('wechat', []));
            }
            $this->sender = 'template_message';
        }

        if (!empty($this->channel['access_token'])) {
            $this->wechat->access_token->setToken($this->channel['access_token']);
        }
    }

    public function send()
    {
        foreach ($this->get('to', []) as $toUser) {
            $data = [
                'touser'      => $toUser,
                'template_id' => $this->channel['template_id'],
                'url'         => $this->get('url', ''),
                'topcolor'    => $this->get('topcolor', ''),
                'data'        => $this->translatedData,
            ];

            $ret = $this->_sendByEasyWechat($data);
        }

        return $ret;
    }

    private function _sendByEasyWechat($data)
    {
        $ret = true;
        try {
            $this->wechat->{$this->sender}->send($data);
        } catch (\Exception $e) {
            $ret = false;
        }
        return $ret;
    }
}
