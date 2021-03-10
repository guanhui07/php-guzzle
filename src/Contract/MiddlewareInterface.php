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

namespace Raylin666\Guzzle\Contract;

/**
 * Interface MiddlewareInterface
 * @package Raylin666\Guzzle\Contract
 */
interface MiddlewareInterface
{
    /**
     * @return callable
     */
    public function getMiddleware(): callable;
}