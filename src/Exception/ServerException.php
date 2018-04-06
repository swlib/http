<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/4/6 下午1:10
 */

namespace Swlib\Http\Exception;

use Swlib\Http\Request;
use Swlib\Http\Response;

/**
 * Exception when a server error is encountered (5xx codes)
 */
class ServerException extends BadResponseException
{
    public function __construct(
        Request $request,
        ?Response $response,
        int $code = 0,
        string $message = 'Server Error occurred!',
        \Exception $previous = null
    )
    {
        parent::__construct($request, $response, $code, $message, $previous);
    }
}