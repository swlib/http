<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/4/6 下午1:06
 */

namespace Swlib\Http\Exception;

use Swlib\Http\Request;
use Swlib\Http\Response;

class TooManyRedirectsException extends RequestException
{
    public $redirect_headers;

    public function __construct(
        Request $request,
        ?Response $response,
        int $code = 0,
        array $redirects,
        \Exception $previous = null
    ) {
        $this->redirect_headers = $redirects;
        $message = "Too many redirects occurred!";
        parent::__construct($request, $response, $code, $message, $previous);
    }

    public function getRedirectHeaders(): array
    {
        return $this->redirect_headers;
    }

    public function getRedirectsTrace(): string
    {
        $trace = '';
        foreach (array_keys($this->redirect_headers) as $index => $location) {
            $trace .= "#$index $location\n";
        }

        return $trace;
    }

}