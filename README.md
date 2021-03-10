# Guzzle 协程处理器 (FPM环境已兼容)

[![GitHub release](https://img.shields.io/github/release/raylin666/guzzle.svg)](https://github.com/raylin666/guzzle/releases)
[![PHP version](https://img.shields.io/badge/php-%3E%207.0-orange.svg)](https://github.com/php/php-src)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](#LICENSE)

### 环境要求

* PHP >=7.0

### 安装说明

```
composer require "raylin666/guzzle"
```

### 使用方式

```php
<?php

require 'vendor/autoload.php';

/***********************************************
 * 非常驻内存环境下使用方式 (非Swoole) 
 ***********************************************/

$client = new \Raylin666\Guzzle\Client();

$client = $client->create();

var_dump($client->post('http://127.0.0.1:9902/api/v1/login', [
    'form_params' => [
        'username' => 'raylin',
        'password' => '123456',
    ]
])->getBody()->getContents());

/**
 * 输出：
 *      string(293) "{"code":200,"data":{"expire_at":1615472981,"id":1,"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJVc2VySUQiOjEsImV4cCI6MTYxNTQ3Mjk4MSwiaWF0IjoxNjE1Mzg2NTgxLCJpc3MiOiJnaW4tYXBpIiwibmJmIjoxNjE1Mzg2NTgxfQ.4d622SGpzldippeBaoKhXI29V6zVyflZST0coMwpWeg"},"message":"OK","responseTime":"66.880505ms"}"
 */

/***********************************************
 * 常驻内存环境下使用方式 (Swoole, 协程) 
 ***********************************************/

// 如果您需要使用 $container , 请自行 composer require "raylin666/container"
$container = new \Raylin666\Container\ContainerFactory(
    new \Raylin666\Container\Container()
);

// 使用协程, SWOOLE_HOOK_ALL 包含 CURL 需 swoole > 4.6 版本
Swoole\Runtime::enableCoroutine(SWOOLE_HOOK_ALL);

$server = new swoole_http_server('127.0.0.1', 10021);

$server->set([
    'worker_num' => 1,
]);

$client = new \Raylin666\Guzzle\Client();

$server->on('workerStart', function () use ($container, $client) {
    $container::getContainer()->singleton('guzzleHttp', function () use ($client) {
        return $client->create([], [
            'min_connections' => 10,
            'max_connections' => 50,
            'wait_timeout'  => 60,
        ]);
    });
});

$server->on('request', function () use ($container) {
    // 并发请求
    for ($i = 0; $i <= 100; $i++) {
        go(function () use ($container) {
           var_dump($container::getContainer()->get('guzzleHttp')->get('https://baidu.com'));
           var_dump($container::getContainer()->get('guzzleHttp')->post('http://127.0.0.1:9902/api/v1/login', [
               'form_params' => [
                   'username' => 'raylin',
                   'password' => '123456',
               ]
           ]));
        });
    }
});

$server->start();

```

## 更新日志

请查看 [CHANGELOG.md](CHANGELOG.md)

### 联系

如果你在使用中遇到问题，请联系: [1099013371@qq.com](mailto:1099013371@qq.com). 博客: [kaka 梦很美](http://www.ls331.com)

## License MIT

