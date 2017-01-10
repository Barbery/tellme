<?php

namespace Barbery\TellMe\Providers;

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

        $ret = true;
        foreach ($this->get('to', []) as $toUser) {
            $data = [
                'touser'      => $toUser,
                'template_id' => $this->channel['template_id'],
                'url'         => $this->get('url', ''),
                'topcolor'    => $this->get('topcolor', ''),
                'data'        => $this->translatedData,
            ];

            try {
                $Wechat->notice->send($data);
            } catch (\Exception $e) {
                $ret = false;
            }
        }

        return $ret;
    }
}
