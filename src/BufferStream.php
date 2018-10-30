<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/10/30 下午20:00
 */

namespace Swlib\Http;

if (version_compare(SWOOLE_VERSION, '4.2.3', '>')) {
    class_alias(SwooleBuffer::class, 'Swlib\\Http\\BufferStream');
} else {
    class_alias(PHPMemory::class, 'Swlib\\Http\\BufferStream');
}
