<?php

namespace Barbery\TellMe\Providers;

class DingtalkProvider extends ServiceProvider
{
    public function send()
    {
        return $this->httpPostJson($this->channel['url'], $this->translatedData);
    }
}
