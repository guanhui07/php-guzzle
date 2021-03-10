<?php
// +----------------------------------------------------------------------
// | Created by linshan. 版权所有 @
// +----------------------------------------------------------------------
// | Copyright (c) 2019 All rights reserved.
// +----------------------------------------------------------------------
// | Technology changes the world . Accumulation makes people grow .
// +----------------------------------------------------------------------
// | Author: kaka梦很美 <1099013371@qq.com>
// +----------------------------------------------------------------------

namespace Raylin666\Guzzle\Middleware;

use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raylin666\Guzzle\Contract\MiddlewareInterface;

/**
 * Class RetryMiddleware
 * @package Raylin666\Guzzle\Middleware
 */
class RetryMiddleware implements MiddlewareInterface
{
    /**
     * 重试次数
     * @var int
     */
    protected $retries = 1;

    /**
     * 延迟重试
     * @var int
     */
    protected $delay = 0;

    /**
     * RetryMiddleware constructor.
     * @param int $retries
     * @param int $delay
     */
    public function __construct(int $retries = 1, int $delay = 0)
    {
        $this->retries = $retries;
        $this->delay = $delay;
    }

    /**
     * @return callable
     */
    public function getMiddleware(): callable
    {
        // TODO: Implement getMiddleware() method.

        return Middleware::retry(function ($retries, RequestInterface $request, ResponseInterface $response = null) {
            return (! $this->isOk($response) && $retries < $this->retries) ? true : false;
        }, function () {
            return $this->delay;
        });
    }

    /**
     * Check the response status is correct.
     * @param ResponseInterface|null $response
     * @return bool
     */
    protected function isOk(?ResponseInterface $response): bool
    {
        return $response && $response->getStatusCode() >= 200 && $response->getStatusCode() < 300;
    }
}