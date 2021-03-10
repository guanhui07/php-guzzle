<?php
// +----------------------------------------------------------------------
// | Created by linshan. 版权所有 @
// +----------------------------------------------------------------------
// | Copyright (c) 2020 All rights reserved.
// +----------------------------------------------------------------------
// | Technology changes the world . Accumulation makes people grow .
// +----------------------------------------------------------------------
// | Author: kaka梦很美 <1099013371@qq.com>
// +----------------------------------------------------------------------

namespace Raylin666\Guzzle;

use GuzzleHttp\HandlerStack;
use Raylin666\Utils\Coroutine\Coroutine;
use GuzzleHttp\Client as GuzzleHttpClient;
use Raylin666\Guzzle\Contract\HandlerInterface;
use Raylin666\Guzzle\Contract\MiddlewareInterface;
use Raylin666\Guzzle\Middleware\RetryMiddleware;

/**
 * Class Client
 * @package Raylin666\Guzzle
 */
class Client
{
    /**
     * @var HandlerInterface
     */
    protected $handler;

    /**
     * 连接池配置项
     * @var array
     */
    protected $poolOption = [];

    /**
     * 中间件
     * @var array
     */
    protected $middlewares = [
        // 重试中间件 [默认开启]
        'retry' => [RetryMiddleware::class, [1, 10]],
    ];

    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->handler = new CoroutineHandler();
    }

    /**
     * 设置 GuzzleHttp\Client 处理器
     * @param HandlerInterface $handler
     * @return Client
     */
    public function setHandler(HandlerInterface $handler): self
    {
        $this->handler = $handler;
        return $this;
    }

    /**
     * 设置连接池配置项 [不设置将不开启连接池]
     * @param array $poolOption
     * @return Client
     */
    public function setPoolOption(array $poolOption): self
    {
        $this->poolOption = $poolOption;
        return $this;
    }

    /**
     * @return array
     */
    public function getPoolOption(): array
    {
        return $this->poolOption;
    }

    /**
     * 设置中间件 [覆盖默认中间件]
     * @param array $middlewares
     * @return Client
     */
    public function setMiddlewares(array $middlewares): self
    {
        $this->middlewares = $middlewares;
        return $this;
    }

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * 创建 GuzzleHttp\Client 客户端
     * @param array $clientOptions
     * @return GuzzleHttpClient
     */
    public function create(array $clientOptions = []): GuzzleHttpClient
    {
        $stack = null;

        if (Coroutine::inCoroutine()) {
            $stack = $this->getHandlerStack();
        }

        $config = array_replace(['handler' => $stack], $clientOptions);

        return new GuzzleHttpClient($config);
    }

    /**
     * @return HandlerStack
     */
    protected function getHandlerStack(): HandlerStack
    {
        $this->handler->setPoolOption($this->getPoolOption());

        $stack = HandlerStack::create($this->handler);

        foreach ($this->getMiddlewares() as $key => $middleware) {
            if (is_array($middleware)) {
                [$class, $arguments] = $middleware;
                $middleware = new $class(...$arguments);
            }

            if ($middleware instanceof MiddlewareInterface) {
                $stack->push($middleware->getMiddleware(), $key);
            }
        }

        return $stack;
    }
}