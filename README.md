# TellMe

[English Version](README-EN.md)


## What it is

TellMe 是一个报警推送的工具，主要功能是消息推送，可以让大家及时收到生产的错误反馈。
目前支持微信、瀑布（零信）、Slack、Email这几种方式的推送。


## Install

```
composer require barbery/tellme:dev-master
```


## Example

```
<?php

use Barbery\TellMe\Tellme;

// 首先配置好需要推送的channels
// 这些配置建议放到框架的配置文件里面
$config = [
    'channels' => [
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
    ],
];


// 自动执行方式，通过设置set_exception_handler，set_error_handler方式来自动调用
// 由于部分框架有内部的注册异常处理，所以该方式可能会和框架框架内置的异常处理冲突了
TellMe::registerThrowableHandler($config);

// 手动执行方式，你可以try{}catch(Throwable){}框架的入口代码，捕捉到错误后手动执行发送
// 或者放置到框架错误处理的地方，像laravel的话，放置到App\Exceptions\Handler.php的里面。
(new TellMe($config))->send($e);

```


## 配置说明

### data变量设置

以下变量在data配置下才有用，需要用大括号进行包裹，例如{code}

| 参数名  | 说明                                                                                                                    |
|---------|-------------------------------------------------------------------------------------------------------------------------|
| code    | 错误码，error时为系统错误码，exception时为异常抛出的错误码                                                              |
| message | 错误简短说明                                                                                                            |
| file    | 发生错误的文件                                                                                                          |
| line    | 发生错误的行数                                                                                                          |
| trace   | 错误追踪的调用栈信息                                                                                                    |
| level   | 错误级别，error时为系统的各个级别(detail: http://php.net/manual/en/errorfunc.constants.php)，exception时固定为exception |
| time    | 错误发生时间，格式为Y-m-d H:i:s                                                                                         |


### Wechat

| 参数名       | 示例                                                                                                                                | 说明                       |
|--------------|-------------------------------------------------------------------------------------------------------------------------------------|----------------------------|
| template_id  | SAJNHOya9H9xI7w_q33I2h_M9Q-h2Tun4WPRLJ4Iey0                                                                                         | 微信公众号模板id           |
| provider     |                                                                wechat                                                               | 微信的推送设置为wechat即可 |
| to           | ["openid1","openid2"]                                                                                                               | 接受人的openid集合         |
| access_token | YOUR_WECHAT_ACCESS_TOKEN                                                                                                            | 公众号的access_token       |
| easywechat   | new EasyWeChat\Foundation\Application($option)                                                                                      | easywechat对象             |
| wechat       | ['app_id'=> 'your-app-id','secret'=> 'your-app-secret','token'=> 'your-token','aes_key' => '']                                      | 公众号信息配置             |
| data         | ['first'=> '{message}','keyword1' => '{level}','keyword2' => '{file}:{line}','keyword3' => '{time}','remark'=> '详细信息：{trace}'] | 推送模板的数据配置         |


### Slack

| 参数名   | 示例                                                                                                                                                                         | 说明                        |
|----------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|-----------------------------|
| url      | https://hooks.slack.com/services/YOUR_TOKEN                                                                                                                                  | slack的incoming webhook url |
| provider | slack                                                                                                                                                                        | 配置为slack时填写slack即可  |
| data     | ['username' => 'tellme-bot','icon_url' => 'http://ww3.sinaimg.cn/large/7376ce75gw1fbljeafka2j206k09amxh.jpg','text'=> "报错啦：{message} \n file:{file}:{line} \n {trace}"] | slack推送的data配置         |


### Email

| 参数名      | 示例                                                                                      | 说明                                                                            |
|-------------|-------------------------------------------------------------------------------------------|---------------------------------------------------------------------------------|
| provider    | email                                                                                     | 配置为email时添加email即可                                                      |
| to          | ["email@example.com","email2@example.com"]                                                | 收件人集合                                                                      |
| data        | ['title' => '报错啦：{message}','content'=> "{message} \n file:{file}:{line} \n {trace}"] | 邮件的data配置                                                                  |
| char_set    | utf-8                                                                                     | 一般填utf-8即可                                                                 |
| is_smtp     | true                                                                                      | 如果是使用smtp发送，就设置为true                                                |
| host        | smtp1.example.com;smtp2.example.com                                                       | 邮件发送服务商host                                                              |
| smtp_auth   | true                                                                                      | 是否启用smtp授权验证                                                            |
| username    | noreply@example.com                                                                       | 邮件账号                                                                        |
| password    | ******                                                                                    | 邮件密码                                                                        |
| smtp_secure | tls                                                                                       | 传输协议，支持tls和ssl                                                          |
| port        | 1234                                                                                      | 邮件服务商提供的端口                                                            |
| is_html     | true                                                                                      | 是否把content的文本解析为html，收件人的服务商支持的话就自动解析成html的样式展示 |