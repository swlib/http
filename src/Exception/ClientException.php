<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/4/6 下午1:07
 */

namespace Swlib\Http\Exception;

use Exception;
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
        string $message = 'Client side error, please check your configurations and permissions!',
        Exception $previous = null
    ) {
        if ($code === Status::NOT_FOUND) {
            $message = "Your request uri {$request->getUri()} is wrong, please check it and try again!";
        }
        parent::__construct($request, $response, $code, $message, $previous);
    }
}
