<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/4/6 下午1:07
 */

namespace Swlib\Http\Exception;

use Swlib\Http\Request;
use Swlib\Http\Response;

/**
 * Exception when a client error is encountered (4xx codes)
 */
class ClientException extends BadResponseException
{
    public function __construct(
        Request $request,
        ?Response $response,
        int $code = 0,
        string $message = 'Client Error occurred!',
        \Exception $previous = null
    ) {
        parent::__construct($request, $response, $code, $message, $previous);
    }
}