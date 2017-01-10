<?php

namespace Barbery\TellMe\Provider;

use EasyWeChat\Foundation\Application;

class WechatProvider extends ServiceProvider
{
    public function send()
    {
        if ($this->get('easywechat') instanceof Application) {
            $Wechat = $this->channel['easywechat'];
        } else {
            $Wechat = new Application($this->get('wechat', []));
            if (!empty($this->channel['access_token'])) {
                $Wechat->access_token->setToken($this->channel['access_token']);
            }
        }

        foreach ($this->get('to', []) as $toUser) {
            $data = [
                'touser'      => $toUser,
                'template_id' => $this->channel['template_id'],
                'url'         => $this->get('url', ''),
                'topcolor'    => $this->get('topcolor', ''),
                'data'        => $this->translatedData,
            ];
            $Wechat->notice->send($data);
        }

        return true;
    }
}
