<?php

return [
    'error_level_map' => [
        0                   => 'Error',
        E_ERROR             => 'Error',
        E_WARNING           => 'Warning',
        E_PARSE             => 'Parse error',
        E_NOTICE            => 'Notice',
        E_CORE_ERROR        => 'Code error',
        E_CORE_WARNING      => 'Code Warning',
        E_COMPILE_ERROR     => 'Compile error',
        E_COMPILE_WARNING   => 'Compile warning',
        E_USER_ERROR        => 'User error',
        E_USER_WARNING      => 'User warning',
        E_USER_NOTICE       => 'User notice',
        E_STRICT            => 'Strict notice',
        E_RECOVERABLE_ERROR => 'Recoverable error',
        E_DEPRECATED        => 'Deprecated notice',
        E_USER_DEPRECATED   => 'User deprecated notice',
        E_ALL               => 'Other errors',
    ],

    'channels'        => [
        [
            'template_id'  => 'YOUR TEMPLATE ID',
            'provider'     => 'wechat',
            'to'           => [],
            'easywechat'   => null,
            'access_token' => '',
            'wechat'       => [
                'app_id'  => 'your-app-id',
                'secret'  => 'your-app-secret',
                'token'   => 'your-token',
                'aes_key' => '',
            ],
            'data'         => [
                'first'    => '{message}',
                'keyword1' => '{level}',
                'keyword2' => '{file}:{line}',
                'keyword3' => '{time}',
                'remark'   => '详细信息：{trace}',
            ],
        ],
        [
            'url'      => 'YOUR SLACK URL',
            'provider' => 'slack',
            'data'     => [
                'username' => 'tellme-bot',
                'icon_url' => 'http://ww3.sinaimg.cn/large/7376ce75gw1fbljeafka2j206k09amxh.jpg',
                'text'     => "{message} \n {trace}",
            ],
        ],
        [
            'url'      => 'YOUR SLACK URL',
            'provider' => 'slack',
            'data'     => [
                'username'   => 'tellme-bot',
                'icon_emoji' => ':ghost:',
                'text'       => "{message} \n {trace}",
            ],
        ],
        [
            'to'          => [],
            'provider'    => 'email',
            'char_set'    => 'urf-8',
            'is_smtp'     => true,
            'host'        => 'smtp1.example.com;smtp2.example.com',
            'smtp_auth'   => true,
            'username'    => 'user@example.com',
            'password'    => '',
            'smtp_secure' => 'tls',
            'port'        => 587,
            'is_html'     => false,
            'data'        => [
                'title'   => '{message}',
                'content' => "{message} \n {trace}",
            ],
        ],
    ],
    'providers'       => [
        'email'  => Barbery\TellMe\Providers\EmailProvider::class,
        'wechat' => Barbery\TellMe\Providers\WechatProvider::class,
        'slack'  => Barbery\TellMe\Providers\SlackProvider::class,
    ],
];
