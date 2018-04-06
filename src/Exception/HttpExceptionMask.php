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
    const E_CONNECT = 2;
    const E_REDIRECT = 4;
    const E_BAD_RESPONSE = 8;
    const E_CLIENT = 16;
    const E_SERVER = 32;
    const E_ALL = 63;
}