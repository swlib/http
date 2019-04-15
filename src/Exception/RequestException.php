<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/4/1 上午1:22
 */

namespace Swlib\Http\Exception;

use Exception;
use Swlib\Http\Request;
use Swlib\Http\Response;

class RequestException extends TransferException
{

    /** @var Request */
    private $request;
    /** @var Response */
    private $response;

    public function __construct(
        Request $request,
        ?Response $response,
        int $code = 0,
        string $message = 'Unknown failed',
        Exception $previous = null
    ) {
        $code = $code ? $code : ($response ? $response->getStatusCode() : 0);
        $phrase = $response ? $response->getReasonPhrase() : 'Unknown';
        parent::__construct("HTTP {$code} {$phrase}: {$message}", $code, $previous);
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Get a short summary of the response
     *
     * Will return `null` if the response is not printable.
     *
     * @param Response $response
     *
     * @return string
     */
    public static function getResponseBodySummary(Response $response): string
    {
        $body = $response->getBody();
        $size = $body->getSize();
        if ($size === 0) {
            return '';
        }
        $summary = $body->read(120);
        $body->rewind();
        if ($size > 120) {
            $summary .= ' (truncated...)';
        }
        // Matches any printable character, including unicode characters:
        // letters, marks, numbers, punctuation, spacing, and separators.
        if (preg_match('/[^\pL\pM\pN\pP\pS\pZ\n\r\t]/', $summary)) {
            return '';
        }

        return $summary;
    }

    /**
     * Get the request that caused the exception
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Get the associated response
     *
     * @return Response|null
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * Check if a response was received
     *
     * @return bool
     */
    public function hasResponse(): bool
    {
        return $this->response !== null;
    }

}
