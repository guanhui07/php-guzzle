# Guzzle HTTP 协程处理器 (已兼容支持 FPM 环境)

[![GitHub release](https://img.shields.io/github/release/raylin666/php-guzzle.svg)](https://github.com/raylin666/php-guzzle/releases)
[![PHP version](https://img.shields.io/badge/php-%3E%207.2-orange.svg)](https://github.com/php/php-src)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](#LICENSE)

### 环境要求

* PHP >=7.2

### 安装说明

```
composer require "guanhui07/guzzle"
```

### 使用方式

```php
<?php

require 'vendor/autoload.php';

use Raylin666\Guzzle\Client;
use Raylin666\Pool\PoolOption;

/***********************************************
 * 非常驻内存环境下使用方式 (非Swoole) 
 ***********************************************/

$client = new Client();
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

$server = new swoole_http_server('127.0.0.1', 9998);

$server->set([
    'worker_num' => swoole_cpu_num(),
]);

// 如果您需要使用 $container , 请自行 composer require "raylin666/container"
$container = \Raylin666\Container\ContainerFactory::getContainer();

$server->on('workerStart', function (Swoole\Server $server, int $workerId) use ($container) {
    var_dump("进程 $workerId 已启动.");

    $client = new Client();
    $client->withPoolOption(
        (new PoolOption())->withMinConnections(1)
        ->withMaxConnections(10)
        ->withWaitTimeout(10)
    );
    $container->bind(\GuzzleHttp\Client::class, function () use ($client) {
        return $client->create();
    });
});

$server->on('request', function (Swoole\Http\Request $request, Swoole\Http\Response $response) use ($container) {
    /** @var \GuzzleHttp\Client $client */
    $client = $container->get(\GuzzleHttp\Client::class);
    for ($i = 0; $i < 100; $i++) {
         // 并发请求
         go(function () use ($client) {
            $response = $client->get('http://baidu.com');
            var_dump($response->getBody()->getContents());
            $response->getBody()->close();
         });
    }
});

$server->start();

```


```php
// on worker start
$client = new Client();
$client->withPoolOption(
    (new PoolOption())->withMinConnections(1)
        ->withMaxConnections(10)
        ->withWaitTimeout(10)
);
$container->make(\GuzzleHttp\Client::class, [function () use ($client) {
    return $client->create();
}]);
        
        
        
  // 控制器中使用 
 $client = di()->get(\GuzzleHttp\Client::class);
$result = $client->get('http://baidu.com');
var_dump($result->getBody()->getContents());
$result->getBody()->close();
        
        
```


## 更新日志

请查看 [CHANGELOG.md](CHANGELOG.md)

### 联系

如果你在使用中遇到问题，请联系: [1099013371@qq.com](mailto:1099013371@qq.com). 博客: [kaka 梦很美](http://www.ls331.com)

## License MIT

