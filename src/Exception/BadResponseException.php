<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/4/1 下午8:28
 */

namespace Swlib\Http\Exception;

use Swlib\Http\Request;
use Swlib\Http\Response;

class BadResponseException extends RequestException
{
    public function __construct(
        Request $request,
        ?Response $response,
        int $code = 0,
        string $message = null,
        \Exception $previous = null
    ) {
        $message = $message ?: "Unknown Bad Response Error #{$code} occurred!";
        parent::__construct($request, $response, $code, $message, $previous);
    }
}
