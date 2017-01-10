<?php

return [
    'error_level_map' => [
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
                'first'    => '{title}',
                'keyword1' => '{level}',
                'keyword2' => '{message}',
                'keyword3' => '{time}',
                'remark'   => '详细信息：{trace}',
            ],
        ],
        [
            'url'      => 'https://hooks.pubu.im/services/yzl4w3r1lbl8sbn',
            'provider' => 'slack',
            'data'     => [
                'username' => 'tellme-bot',
                'icon_url' => 'http://ww3.sinaimg.cn/large/7376ce75gw1fbljeafka2j206k09amxh.jpg',
                'text'     => "{title} \n {message} \n {trace}",
            ],
        ],
        [
            'url'      => 'https://hooks.slack.com/services/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',
            'provider' => 'slack',
            'data'     => [
                'username'   => 'tellme-bot',
                'icon_emoji' => ':ghost:',
                'text'       => "{title} \n {message} \n {trace}",
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
                'title'   => '{title}',
                'content' => "{title} \n {message} \n {trace}",
            ],
        ],
    ],
    'providers'       => [
        'email'  => Barbery\TellMe\Provider\EmailProvider::class,
        'wechat' => Barbery\TellMe\Provider\WechatProvider::class,
        'slack'  => Barbery\TellMe\Provider\SlackProvider::class,
    ],
];
