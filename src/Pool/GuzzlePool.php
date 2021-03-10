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

namespace Raylin666\Guzzle\Pool;

use Raylin666\Pool\Pool;
use Raylin666\Contract\ConnectionPoolInterface;
use Raylin666\Guzzle\Contract\GuzzlePoolInterface;

/**
 * Class GuzzlePool
 * @package Raylin666\Database\Pool
 */
class GuzzlePool extends Pool implements GuzzlePoolInterface
{
    /**
     * 创建连接
     * @return ConnectionPoolInterface
     */
    protected function createConnection(): ConnectionPoolInterface
    {
        // TODO: Implement createConnection() method.

        return make(
            Connection::class,
            [
                'pool'      =>   $this,
                'callback'  =>   $this->getConnectionCallback()
            ]
        );
    }
}
