<?php

namespace Barbery\TellMe\Providers;

class SlackProvider extends ServiceProvider
{
    public function send()
    {
        $data['payload'] = json_encode($this->translatedData);
        return $this->httpPost($this->channel['url'], $data);
    }
}
