<?php
/**
 * Created by PhpStorm.
 * User: twosee
 * Date: 2017/11/9
 * Time: 上午12:05
 */

namespace Swlib\Http;

use InvalidArgumentException;

class Cookie
{
    public $name = '';
    public $value = '';
    public $expires = 0;
    public $path = '/';
    public $domain = '';
    public $session = false; //会话cookie,没有过期时间,在浏览器关闭时清除
    public $secure = false;
    public $httponly = false;
    public $hostonly = true;

    /**
     * Cookie constructor.
     *
     * @param array|string $options
     * @param array $default default cookie param (such as record the completely domain in http client)
     *
     */
    public function __construct($options, array $default = [])
    {
        $options = is_string($options) ? self::parseHeader($options) : $options;
        $this->init($options, $default);
    }

    /**
     * initialization
     *
     * @param array $options
     * @param array $default
     */
    private function init(array $options, array $default = []): void
    {
        $options = array_merge($default, $options);

        //Check if the cookie is valid according to RFC 6265
        if (empty($options['name']) && !is_numeric($options['name'])) {
            throw new InvalidArgumentException('Cookie must have its name!');
        }

        // Check if any of the invalid characters are present in the cookie name
        if (preg_match('/[\x00-\x20\x22\x28-\x29\x2c\x2f\x3a-\x40\x5c\x7b\x7d\x7f]/', $options['name'])) {
            throw new InvalidArgumentException(
                'Cookie name must not contain invalid characters: ASCII '
                . 'Control characters (0-31;127), space, tab and the '
                . 'following characters: ()<>@,;:\"/?={}'
            );
        }

        if (!empty($options['value']) && !isset($options['expires'])) {
            //有值但没有设定过期时间,是会话cookie
            $this->session = true;
            $this->expires = time();
        }

        foreach ($options as $key => $val) {
            //insure value is the correct type
            if (isset($this->$key) && settype($val, gettype($this->$key)) && !empty($key)) {
                $this->$key = $val;
            }
        }

        //tip: (if it's necessary?)
        if (substr($this->path, 0, 1) !== '/') {
            $this->path = '/' . $this->path; // complete the leftmost "/"
        }

        //check is it hostonly
        if (substr($this->domain, 0, 1) === '.') {
            $this->domain = ltrim($this->domain, '.');
            $this->hostonly = false;
        }
    }

    /**
     * parse header string (aaa=bbb;Expires=123; Path=/;) to cookie array
     *
     * @param string $header
     *
     * @return array
     */
    public static function parseHeader(string $header): array
    {
        $cookie = [];
        $kvs = explode('; ', $header);
        $nv = explode('=', array_shift($kvs));
        $cookie['name'] = $nv[0];
        //because of some stupid system could return Set-Cookie: foo=''; so we must replace it.
        $cookie['value'] = str_replace(['\'\'', '""'], '', $nv[1]);
        foreach ($kvs as $kv) {
            $kv = explode('=', $kv);
            $kv[0] = strtolower($kv[0]);
            if (isset($kv[1])) {
                $cookie[$kv[0]] = $kv[1];
            } else {
                $cookie[$kv[0]] = true;
            }
        }
        /**
         * If a response includes both an Expires header and a max-age directive,
         * the max-age directive overrides the Expires header!
         */
        if (isset($cookie['max-age'])) {
            $cookie['expires'] = time() + $cookie['max-age'];
        } else {
            if (isset($cookie['expires'])) {
                $cookie['expires'] = strtotime($cookie['expires']);
            }
        }

        return $cookie;
    }

    /**
     * Convert this object to an array minimized
     *
     * @param bool $simplify
     *
     * @return array
     */
    public function toArray($simplify = false): array
    {
        $r = [];
        foreach ($this as $key => $val) {
            $r[$key] = $val;
        }
        if ($simplify) {
            $r = array_filter($r);
        }

        return $r;
    }

    /**
     * Convert a Cookie object to a Set-Cookie Header string
     *
     * @return string
     */
    public function __toString(): string
    {
        if ($this->value === '') {
            //value为空时删除cookie
            $this->value = 'deleted';
            $this->expires = 0;
        }
        $str = "{$this->name}={$this->value};";

        if ($this->session === false) {
            $str .= " Expires=" . gmdate("D, d-M-Y H:i:s", $this->expires) . ' GMT;';
            $maxAge = $this->expires - time();
            $maxAge = $maxAge >= 0 ? $maxAge : 0;
            $str .= " Max-Age={$maxAge};";
        }

        if (!empty($this->path)) {
            $str .= " Path={$this->path};";
        }
        if (!empty($this->domain)) {
            $domain = $this->hostonly ? $this->domain : '.' . $this->domain;
            $str .= " Domain={$domain};";
        }
        if ($this->secure === true) {
            $str .= " Secure;";
        }
        if ($this->httponly === true) {
            $str .= " HttpOnly;";
        }

        return $str;
    }

    /**
     * is cookie in expires
     *
     * @return bool
     */
    public function isNotExpired(): bool
    {
        return $this->session || $this->expires > time();
    }

    /**
     * Check if the cookie belongs to this url
     *
     * @param string $uri
     *
     * @return bool
     */
    public function isThere(string $uri): bool
    {
        if (empty($uri)) {
            return true;
        }
        $uri = parse_url($uri);

        //检查是否在路径中
        if ($this->path !== '/' && strpos($uri['path'], rtrim($this->path, '/')) === false) {
            return false;
        }

        if ($this->hostonly) {
            return $this->domain === $uri['host'];
        } else {
            $rest = str_replace($this->domain, '', $uri['host'], $is_there);
            //uri的host中是否包含了该cookie的domain
            if ($is_there) {
                if (substr_count($rest, '.') > 1) {
                    //通配符匹配,最多向下一级(参考chrome)
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
    }

    /**
     * Check whether the cookie takes effect in the url
     *
     * @param string $uri
     *
     * @return bool
     */
    public function isValid(string $uri = ''): bool
    {
        return !empty($this->value) && $this->isNotExpired() && $this->isThere($uri);
    }
}
