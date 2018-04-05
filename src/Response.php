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
        if ($code === $this->statusCode) {
            return $this;
        } else {
            $this->statusCode = $code;
            if (empty($reasonPhrase)) {
                $this->reasonPhrase = Status::getReasonPhrase($this->statusCode);
            } else {
                $this->reasonPhrase = $reasonPhrase;
            }

            return $this;
        }
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
        $headers_array = $this->getHeaders(true);
        $headers = '';
        foreach ($headers_array as $name => $line) {
            if ($name === 'Set-Cookie') {
                $set_cookie_array = $this->getHeader('set-cookie');
                foreach ($set_cookie_array as $val) {
                    $headers .= "Set-Cookie: $val\n";
                }
            } else {
                $headers .= "$name: $line\n";
            }
        }
        $headers = rtrim($headers, "\n");

        return
            'HTTP/' . $this->getProtocolVersion() . ' ' . $this->getStatusCode() . ' ' . $this->getReasonPhrase() . "\n" .
            $headers . "\r\n\r\n" . $this->getBody();
    }
}