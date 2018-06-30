<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/4/6 下午8:29
 */

namespace Swlib\Http\Exception;

class HttpExceptionMask
{
    const E_NONE = 0;
    const E_REQUEST = 1;
    const E_CONNECT = 1 << 1;
    const E_REDIRECT = 1 << 2;
    const E_BAD_RESPONSE = 1 << 3;
    const E_CLIENT = 1 << 4;
    const E_SERVER = 1 << 5;
    const E_ALL = (1 << 6) - 1;
}
