<?php
/**
 * Created by PhpStorm.
 * User: twosee
 * Date: 2017/10/30
 * Time: 下午2:23
 */

namespace Swlib\Http;

use Psr\Http\Message\MessageInterface;

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
    /**@var [][]string */
    protected $headers = [];
    /**@var StreamInterface */
    protected $body;

    function __construct(array $headers = [], ?StreamInterface $body = null)
    {
        $this->withHeaders($headers);
        $this->withBody($body);
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

    public function hasHeader($name): bool
    {
        return array_key_exists(strtolower($name), $this->headers);
    }

    public function getHeaderLine($name): string
    {
        $name = strtolower($name);
        if (array_key_exists($name, $this->headers)) {
            return implode(', ', $this->headers[$name]);
        } else {
            return '';
        }
    }

    public function getHeader($name): array
    {
        $name = strtolower($name);
        if (isset($this->headers[$name])) {
            return $this->headers[$name];
        } else {
            return [];
        }
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
        } else {
            if ($ucwords) {
                $headers = [];
                foreach ($this->headers as $key => $val) {
                    $key = ucwords($key, '-');
                    $headers[$key] = $val;
                }

                return $headers;
            } else {
                if ($implode) {
                    $headers = [];
                    foreach ($this->headers as $key => $val) {
                        $headers[$key] = implode(', ', $val);
                    }

                    return $headers;
                }
            }
        }

        return $this->headers;
    }

    /**
     * @param string $name
     * @param $value
     * @return $this
     */
    public function withHeader($name, $value): self
    {
        $name = strtolower($name);
        if (isset($this->headers[$name])) {
            if ($value === null) {
                unset($this->headers[$name]);

                //重置顺序,删除
                return $this;
            }
        }
        $this->headers[$name] = (array)$value;

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

    /**
     * @param string $name
     * @param $value
     * @return $this
     */
    public function withAddedHeader($name, $value): self
    {
        $name = strtolower($name);
        $value = (array)$value;
        $this->headers[$name] = array_merge($this->headers[$name] ?? [], $value);

        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function withoutHeader($name): self
    {
        $name = strtolower($name);
        if (isset($this->headers[$name])) {
            unset($this->headers[$name]);
        }

        return $this;
    }

    public function getBody(): ?StreamInterface
    {
        return $this->body;
    }

    /**
     * @param null|StreamInterface $body
     * @return $this
     */
    public function withBody($body): self
    {
        $this->body = $body;

        return $this;
    }
}