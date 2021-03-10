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

use Raylin666\Pool\Connection as ConnectionPool;

/**
 * Class Connection
 * @package Raylin666\Guzzle\Pool
 */
class Connection extends ConnectionPool
{
    /**
     * @return mixed
     */
    protected function getActiveConnection()
    {
        // TODO: Implement getActiveConnection() method.

        return $this->reconnect();
    }
}