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
 * Interface HandlerInterface
 * @package Raylin666\Guzzle\Contract
 */
interface HandlerInterface
{
    /**
     * 设置连接池配置
     * @param array $option
     * @return mixed
     */
    public function setPoolOption(array $option);
}