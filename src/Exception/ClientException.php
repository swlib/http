<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/4/6 下午1:07
 */

namespace Swlib\Http\Exception;

use Swlib\Http\Request;
use Swlib\Http\Response;
use Swlib\Http\Status;

/**
 * Exception when a client error is encountered (4xx codes)
 */
class ClientException extends BadResponseException
{
    public function __construct(
        Request $request,
        ?Response $response,
        int $code = 0,
        string $message = null,
        \Exception $previous = null
    ) {
        $message = $message ?: "Client Error #{$code}: " . Status::getReasonPhrase($code) . '!';
        parent::__construct($request, $response, $code, $message, $previous);
    }
}
