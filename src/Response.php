<?php
/**
 * Created by PhpStorm.
 * User: twosee
 * Date: 2017/10/30
 * Time: 下午2:25
 */

namespace Swlib\Http;

use Psr\Http\Message\ResponseInterface;

class Response extends Message implements ResponseInterface
{
    protected $statusCode = Status::OK;
    protected $reasonPhrase = 'OK';

    function __construct(
        $status = Status::OK,
        array $headers = [],
        $body = null,
        string $protocolVersion = '1.1',
        string $reason = null
    ) {
        $this->statusCode = (int)$status;
        parent::__construct($headers, $body);
        if ($reason == '') {
            $this->reasonPhrase = Status::getReasonPhrase($this->statusCode);
        } else {
            $this->reasonPhrase = (string)$reason;
        }
        $this->protocolVersion = $protocolVersion;
    }

    /**
     * Get status code
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set status code
     *
     * @param int $code
     * @param string $reasonPhrase
     *
     * @return Response
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $this->statusCode = (int)$code;
        if ($reasonPhrase == '') {
            $this->reasonPhrase = Status::getReasonPhrase($this->statusCode);
        } else {
            $this->reasonPhrase = $reasonPhrase;
        }

        return $this;
    }

    /**
     * Get the status code corresponding to the interpreted text
     *
     * @return string
     */
    public function getReasonPhrase()
    {
        return $this->reasonPhrase;
    }

    public function __toString()
    {
        return "HTTP/{$this->getProtocolVersion()} {$this->getStatusCode()} {$this->getReasonPhrase()}\r\n" .
            "{$this->getHeadersString()}\r\n\r\n" . ($this->hasBody() ? $this->getBody() : '');
    }
}
