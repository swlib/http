<?php
/**
 * Created by PhpStorm.
 * User: twosee
 * Date: 2017/10/31
 * Time: 上午9:36
 */

namespace Swlib\Http;

use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface
{
    /** @var string */
    protected $method = 'GET';
    /** @var null|string */
    protected $requestTarget;

    protected $cookieParams = [];
    protected $queryParams = [];
    protected $parsedBody;
    protected $uploadedFiles = [];

    function __construct(string $method = 'GET', $uri = '', array $headers = [], $body = null, string $protocolVersion = '1.1')
    {
        parent::__construct($headers, $body, $protocolVersion);
        if (!($uri instanceof UriInterface)) {
            $uri = new Uri($uri); // request must has uri
        }
        $this->withUri($uri, $this->hasHeader('Host'));
        $this->withMethod($method);
    }

    public function getRequestTarget(): string
    {
        if ($this->requestTarget !== null) {
            return $this->requestTarget;
        }

        parse_str($this->uri->getQuery(), $query);
        $query = $this->getQueryParams() + $query; //attribute value first
        $query = http_build_query($query);

        $target = $this->uri->getPath() ?: '/';
        $target = empty($query) ? $target : $target . '?' . $query;

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

    /**
     * @param UriInterface|null $uri
     * @param bool $preserveHost
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

    public function getUploadedFile(string $name): ?UploadedFileInterface
    {
        return $this->uploadedFiles[$name] ?? null;
    }

    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    /**
     * @param UploadedFileInterface $uploadedFile
     * @return $this
     */
    public function withUploadedFile(string $name, ?UploadedFileInterface $uploadedFile): self
    {
        if ($uploadedFile === null) {
            $this->withoutUploadedFile($name);
        } else {
            $this->uploadedFiles[$name] = $uploadedFile;
        }

        return $this;
    }

    /** @return $this */
    public function withoutUploadedFile(string $name): self
    {
        if (isset($this->uploadedFiles[$name])) {
            unset($this->uploadedFiles[$name]);
        }

        return $this;
    }

    /**
     * @param UploadedFileInterface[] $uploadedFile must be array of UploadedFile Instance
     *
     * @return Request
     */
    public function withUploadedFiles(array $uploadedFiles): self
    {
        $this->uploadedFiles = $uploadedFiles;

        return $this;
    }

    public function __toString()
    {
        $req = trim("{$this->getMethod()} {$this->getRequestTarget()}") . " HTTP/{$this->getProtocolVersion()}\r\n";
        if (!$this->hasHeader('host')) {
            $req .= "Host: {$this->getUri()->getHost()}\r\n";
        }
        $req .= "{$this->getHeadersString()}\r\n\r\n" . ($this->hasBody() ? $this->getBody() : '');

        return $req;
    }
}
