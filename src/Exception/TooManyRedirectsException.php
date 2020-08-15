<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/4/6 下午1:06
 */

namespace Swlib\Http\Exception;

use Exception;
use Swlib\Http\Request;
use Swlib\Http\Response;
use Swlib\Http\Util;

class TooManyRedirectsException extends RequestException
{
    public $redirect_headers;

    public function __construct(
        Request $request,
        ?Response $response,
        int $code = 0,
        array $redirects,
        Exception $previous = null
    ) {
        $this->redirect_headers = $redirects;
        $times = count($redirects);
        $location = Util::getLastKey($this->redirect_headers);
        $message = "Too many redirects! more than {$times} times to {$location} !";
        parent::__construct($request, $response, $code, $message, $previous);
    }

    public function getRedirectHeaders(): array
    {
        return $this->redirect_headers;
    }

    public function getRedirectsTrace(): string
    {
        $trace = '';
        $index = 0;

        foreach ($this->redirect_headers as $location => $redirect_headers) {
            $trace .= "#{$index} {$location}\n";
            $index++;
        }

        return $trace;
    }

}
