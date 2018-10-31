<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/3/30 下午10:27
 */

namespace Swlib\Http;

interface StreamInterface extends \Psr\Http\Message\StreamInterface
{

    public function clear();

    public function overWrite(string $data = null);

}
