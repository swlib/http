<?php
/**
 * Created by PhpStorm.
 * User: twosee
 * Date: 2017/10/31
 * Time: 上午9:36
 */

namespace Swlib\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface
{

    /**@var Uri */
    protected $uri;
    protected $method;
    protected $requestTarget;

    protected $cookieParams = [];
    protected $queryParams = [];
    protected $parsedBody;
    protected $uploadFiles = [];

    function __construct($uri = '', string $method = 'GET', array $headers = [], $body = null)
    {
        if (!($uri instanceof UriInterface)) {
            $uri = new Uri($uri);
        }
        $this->withUri($uri);
        $this->withMethod($method);
        if (!($body instanceof StreamInterface)) {
            $body = new BufferStream((string)$body);
        }
        parent::__construct($headers, $body);
    }

    public function getRequestTarget(): string
    {
        if ($this->requestTarget !== null) {
            return $this->requestTarget;
        }

        $target = $this->uri->getPath();
        if ($target == '') {
            $target = '/';
        }
        if ($this->uri->getQuery() != '') {
            $target .= '?' . $this->uri->getQuery();
        }

        return $target;
    }

    /**
     * @param string $requestTarget
     * @return $this
     */
    public function withRequestTarget($requestTarget): self
    {
        if (preg_match('/\s/', $requestTarget)) {
            throw new InvalidArgumentException(
                'Invalid request target provided; cannot contain whitespace'
            );
        }
        $this->requestTarget = $requestTarget;

        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param $method
     * @return $this
     */
    public function withMethod($method): self
    {
        $this->method = strtoupper($method);

        return $this;
    }

    public function getUri(): ?UriInterface
    {
        return $this->uri;
    }

    /**
     * @param Uri $uri
     * @param bool $autoHost
     * @return $this
     */
    public function withUri(?UriInterface $uri, $preserveHost = false): self
    {
        if ($uri !== $this->uri) {
            $this->uri = $uri;
        }
        if (!$preserveHost) {
            $this->updateHostFromUri();
        }

        return $this;
    }

    private function updateHostFromUri()
    {
        $host = $this->uri->getHost();
        if ($host == '') {
            return;
        }
        if (($port = $this->uri->getPort()) !== null) {
            $host .= ':' . $port;
        }
        if (isset($this->headerNames['host'])) {
            $raw_name = $this->headerNames['host'];
        } else {
            $raw_name = 'Host';
            $this->headerNames['host'] = 'Host';
        }
        // Ensure Host is the first header.
        // See: http://tools.ietf.org/html/rfc7230#section-5.4
        $this->headers = [$raw_name => [$host]] + $this->headers;
    }

    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    public function getCookieParam(string $name): string
    {
        return $this->cookieParams[$name] ?? '';
    }

    /**
     * @param string $name
     * @param null|string $value
     * @return $this
     */
    public function withCookieParam(string $name, ?string $value): self
    {
        if ($value === null) {
            unset($this->cookieParams[$name]);
        } else {
            $this->cookieParams[$name] = $value;
        }

        return $this;
    }

    /**
     * @param array $cookies
     * @return $this
     */
    public function withCookieParams(array $cookies): self
    {
        $this->cookieParams = $cookies;

        return $this;
    }

    public function getQueryParam(string $name): string
    {
        return $this->queryParams[$name] ?? '';
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * @param string $name
     * @param null|string $value
     * @return $this
     */
    public function withQueryParam(string $name, ?string $value): self
    {
        if ($value === null) {
            if (isset($this->queryParams[$name])) {
                unset($this->queryParams[$name]);
            }
        } else {
            $this->queryParams[$name] = $value;
        }

        return $this;
    }

    /**
     * @param array $query
     * @return $this
     */
    public function withQueryParams(array $query): self
    {
        $this->queryParams = $query;

        return $this;
    }

    public function getParsedBody(?string $name = null)
    {
        if ($name === null) {
            return $this->parsedBody;
        } else {
            return $this->parsedBody[$name] ?? null;
        }
    }

    /**
     * @param $data
     * @return $this
     */
    public function withParsedBody($data): self
    {
        $this->parsedBody = $data;

        return $this;
    }

    public function getUploadFile(string $name): UploadFile
    {
        return $this->uploadFiles[$name] ?? null;
    }

    public function getUploadFiles(): array
    {
        return $this->uploadFiles;
    }

    /**
     * @param UploadFile $uploadFile
     * @return $this
     */
    public function withUploadFile(UploadFile $uploadFile): self
    {
        $this->uploadFiles[] = $uploadFile;

        return $this;
    }

    /**
     * @param UploadFile[] $uploadFile must be array of UploadFile Instance
     *
     * @return Request
     */
    public function withUploadFiles(array $uploadFiles): self
    {
        $this->uploadFiles = $uploadFiles;

        return $this;
    }

}