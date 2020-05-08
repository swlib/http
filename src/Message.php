<?php
/**
 * Created by PhpStorm.
 * User: twosee
 * Date: 2017/10/30
 * Time: 下午2:23
 */

namespace Swlib\Http;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class Message
 *
 * @package   Http
 * @reference <http://www.php-fig.org/psr/psr-7/>
 */
class Message implements MessageInterface
{
    /**@var string */
    protected $protocolVersion = '1.1';
    /**@var Uri */
    protected $uri;
    /**@var [][]string */
    protected $headerNames = [];
    protected $headers = [];
    /**@var StreamInterface */
    protected $body;

    function __construct(array $headers = [], $body = null, string $protocolVersion = '1.1')
    {
        $this->withProtocolVersion($protocolVersion);
        $this->withAddedHeaders($headers);
        if ($body !== '' && $body !== null) {
            $this->body = stream_for($body);
        }
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * @param string $version
     * @return $this
     */
    public function withProtocolVersion($version): self
    {
        if ($this->protocolVersion === $version) {
            return $this;
        }
        $this->protocolVersion = $version;

        return $this;
    }

    public function getUri(): ?UriInterface
    {
        return $this->uri;
    }

    /**
     * @param UriInterface|null $uri
     * @param bool $preserveHost
     * @return $this
     */
    public function withUri(?UriInterface $uri, $preserveHost = false)
    {
        $this->uri = $uri;

        return $this;
    }

    public function hasHeader($name): bool
    {
        return isset($this->headerNames[strtolower($name)]);
    }

    public function getHeader($name): array
    {
        if ($raw_name = ($this->headerNames[strtolower($name)] ?? false)) {
            return $this->headers[$raw_name];
        } else {
            return [];
        }
    }

    public function getHeaderLine($name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    /**
     * get all headers
     *
     * @param bool $implode
     * @param bool $ucwords
     *
     * @return array
     */
    public function getHeaders(bool $implode = false, bool $ucwords = false): array
    {
        if ($ucwords && $implode) {
            $headers = [];
            foreach ($this->headers as $key => $val) {
                $key = ucwords($key, '-');
                $headers[$key] = implode(', ', $val);
            }

            return $headers;
        } elseif ($ucwords) {
            $headers = [];
            foreach ($this->headers as $key => $val) {
                $key = ucwords($key, '-');
                $headers[$key] = $val;
            }

            return $headers;
        } elseif ($implode) {
            $headers = [];
            foreach ($this->headers as $key => $val) {
                $headers[$key] = implode(', ', $val);
            }

            return $headers;
        }

        return $this->headers;
    }

    public function getHeadersString(bool $ucwords = true): string
    {
        $headers = '';
        foreach ($this->headers as $name => $values) {
            if (strtolower($name) === 'set-cookie') {
                $set_cookie_array = $this->headers[$this->headerNames['set-cookie']];
                foreach ($set_cookie_array as $val) {
                    $headers .= "Set-Cookie: $val\r\n";
                }
            } else {
                $line = implode(', ', $values);
                if ($ucwords) {
                    $name = ucwords($name, '-');
                }
                $headers .= "$name: $line\r\n";
            }
        }
        $headers = substr($headers, 0, -2);

        return $headers;
    }

    /**
     * @param string $raw_name
     * @param $value
     * @return $this
     */
    public function withHeader($raw_name, $value): self
    {
        $normalized = strtolower($raw_name);
        if (isset($this->headerNames[$normalized])) {
            $this->withoutHeader($raw_name);
            if ($value === null || $value === false) {
                return $this;
            }
        }
        $this->headerNames[$normalized] = $raw_name;
        $this->headers[$raw_name] = self::trimHeaderValues((array)$value);

        return $this;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function withHeaders(array $headers): self
    {
        foreach ($headers as $name => $value) {
            $this->withHeader($name, $value);
        }

        return $this;
    }

    public function withAddedHeaders(array $headers): self
    {
        foreach ($headers as $name => $value) {
            $this->withAddedHeader($name, $value);
        }

        return $this;
    }


    /**
     * @param string $raw_name
     * @param $value
     * @return $this
     */
    public function withAddedHeader($raw_name, $value): self
    {
        $normalized = strtolower($raw_name);
        if (isset($this->headerNames[$normalized])) {
            $raw_name = $this->headerNames[$normalized];
        } else {
            $this->headerNames[$normalized] = $raw_name;
        }
        $this->headers[$raw_name] =
            array_merge(
                $this->headers[$raw_name] ?? [],
                self::trimHeaderValues((array)$value)
            );

        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function withoutHeader($name): self
    {
        $normalized = strtolower($name);
        $raw_name = $this->headerNames[$normalized] ?? false;
        if ($raw_name) {
            unset($this->headers[$raw_name], $this->headerNames[$normalized]);
        }

        return $this;
    }

    /**
     * @return int if empty 0 else bodySize
     */
    public function hasBody(): int
    {
        return $this->body ? $this->body->getSize() : 0;
    }

    /**
     * @return StreamInterface
     */
    public function getBody(): StreamInterface
    {
        if (!isset($this->body)) {
            $this->body = stream_for('');
        }

        return $this->body;
    }

    /**
     * @param null|StreamInterface $body
     * @return $this
     */
    public function withBody(?StreamInterface $body): self
    {
        if ($body === $this->body) {
            return $this;
        }
        if ($body === null) {
            $this->body = stream_for('');
        } else {
            $this->body = $body;
        }

        return $this;
    }

    /**
     * Trims whitespace from the header values.
     *
     * Spaces and tabs ought to be excluded by parsers when extracting the field value from a header field.
     *
     * header-field = field-name ":" OWS field-value OWS
     * OWS          = *( SP / HTAB )
     *
     * @param string[] $values Header values
     *
     * @return string[] Trimmed header values
     *
     * @see https://tools.ietf.org/html/rfc7230#section-3.2.4
     */
    private static function trimHeaderValues(array $values)
    {
        return array_map(function ($value) {
            return trim($value, " \t");
        }, $values);
    }
}
