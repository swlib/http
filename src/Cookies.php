<?php
/**
 * Created by PhpStorm.
 * User: twosee
 * Date: 2017/11/17
 * Time: 下午4:00
 */

namespace Swlib\Http;

class Cookies
{
    const DELIMITER = '$';

    /**
     * an array include some Http\Cookie objects
     *
     * @var \Swlib\Http\Cookie[]
     */
    public $raw = [];

    /**
     * Cookies constructor.
     *
     * @param array|string $cookies
     * @param array $default
     * @param bool $nonempty
     */
    public function __construct($cookies = [], array $default = [], bool $nonempty = false)
    {
        $this->adds($cookies, $default, $nonempty);
    }

    public function reset(): Cookies
    {
        $this->raw = [];

        return $this;
    }

    /**
     * Write the complete Cookie object into the $cookie list of the only instance of Cookie
     *
     * @param array|string|Cookie $cookie
     * @param array $default
     * @param bool $nonempty
     *
     * @return Cookies
     */
    public function add($cookie, array $default = [], bool $nonempty = false): Cookies
    {
        if (!$cookie instanceof Cookie) {
            $cookie = new Cookie($cookie, $default);
        }
        //只允许非空cookie
        if ($nonempty && $cookie->value === '') {
            return $this;
        }

        // php array is a natural high-performance hash table
        // According to chrome's handling, path is only used as a cookie attribute, and the same domain+name is treated as the same cookie.
        $key = $cookie->domain . self::DELIMITER . $cookie->name;
        if (isset($this->raw[$key])) {
            //重置排序(tip:这会消耗很多性能)
            unset($this->raw[$key]);
        }
        $this->raw[$key] = $cookie;

        return $this;
    }

    /**
     * Parse any formatted cookie and convert it into a Cookie object in the Cookies list
     * Supports three formats ['name'=>'value',k=>v] | [['name'=>'xxx','value'=>'xxx']] | request_header_string:'a=b; c=d;'
     *
     * @param array|string $cookies
     * @param array $default
     * @param bool $nonempty
     *
     * @return Cookies
     */
    public function adds($cookies = [], array $default = [], bool $nonempty = false): Cookies
    {
        if (!empty($cookies)) {
            if ($cookies instanceof Cookies) {
                $this->raw = array_merge($this->raw, $cookies->raw);
            } else {
                if (is_array($cookies)) {
                    if (isset($cookies[0])) {
                        if (is_array($cookies[0])) {
                            //[{array_obj},{array_obj}] array_obj=['name'=>'xxx','value'=>'xxx']
                            foreach ($cookies as $cookie) {
                                $this->add($cookie, $default, $nonempty);
                            }
                        } else {
                            if (is_string($cookies[0])) {
                                foreach ($cookies as $cookie) {
                                    $this->add($cookie, $default, $nonempty);
                                }
                            }
                        }
                    } else {
                        if (is_string(key($cookies))) {
                            //['name'=>'value','name2'=>'value2']
                            foreach ($cookies as $name => $value) {
                                $this->add(['name' => $name, 'value' => $value], $default, $nonempty);
                            }
                        }
                    }
                } else {
                    //request header string 'a=b; c=d;'
                    $cookies = rtrim($cookies, '; '); //清除冗余字符
                    $arr = explode('; ', $cookies);
                    foreach ($arr as $string) {
                        $this->add($string, $default, $nonempty);
                    }
                }
            }
        }

        return $this;
    }

    /**
     * get raw
     *
     * @return \Swlib\Http\Cookie[]
     */
    public function getRaw(): array
    {
        return $this->raw;
    }

    /**
     * return the number of cookies
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->raw);
    }

    /**
     * Turn Cookies into k=>v key-value pairs for Request
     *
     * @param string $uri 资源标记位置
     *
     * @return array
     */
    public function toRequestArray(string $uri = ''): array
    {
        $r = [];
        if (!empty($this->raw)) {
            $priorities = [];
            /** @var $cookie Cookie */
            foreach ($this->raw as $cookie) {
                if ($cookie->isValid($uri)) {
                    //Session cookie or unexpired && match domain path
                    $priority = strlen($cookie->domain); //Record priority, the higher the match, the higher the priority
                    if (!isset($r[$cookie->name]) || $priorities[$cookie->name] < $priority) {
                        //The cookie of the name is not set or the cookie set has a lower priority than the current cookie
                        $r[$cookie->name] = $cookie->value;
                        $priorities[$cookie->name] = $priority;
                    }
                }
            }
        }

        return $r;
    }

    /**
     * Convert Cookies to (k=v; k=v) key-value pair string for Request
     *
     * @param string $uri
     *
     * @return string
     */
    public function toRequestString(string $uri = ''): string
    {
        return !empty($this->raw) ? self::kvToRequestString($this->toRequestArray($uri)) : '';
    }

    /**
     * Convert Cookies to a header array for Response
     *
     * @return array
     */
    public function toResponse(): array
    {
        $r = [];
        foreach ($this->raw as $cookie) {
            $r[] = (string)$cookie;
        }

        return $r;
    }

    /**
     * Convert an array of key-value pairs to a string of the form (a=b; c=d)
     *
     * @param array $kv
     *
     * @return string
     */
    public static function kvToRequestString(array $kv): string
    {
        $r = '';
        foreach ($kv as $k => $v) {
            $r .= $k . '=' . $v . '; ';
        }
        $r = rtrim($r, '; ');

        return $r;
    }

    /**
     * All converted to key-value string
     *
     * @return string
     */
    public function __toString(): string
    {
        $r = '';
        /** @var $cookie Cookie */
        foreach ($this->raw as $cookie) {
            $r .= $cookie->name . '=' . $cookie->value . '; ';
        }

        return $r;
    }

    public function __clone()
    {
        $new = [];
        foreach ($this->raw as $key => $cookie) {
            $new[$key] = clone $cookie;
        }
        $this->raw = $new;
    }
}
