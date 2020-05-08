<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/3/30 下午9:00
 */

namespace Swlib\Http;

trait CookiesManagerTrait
{
    /**@var Cookies * */
    public $cookies;

    /**@var Cookies * */
    public $incremental_cookies;

    protected function __constructCookiesManager(bool $incremental = false)
    {
        $this->cookies = new Cookies;
        if ($incremental) {
            $this->incremental_cookies = new Cookies;
        }
    }

    public function getCookies()
    {
        return $this->cookies->getRaw();
    }

    /**
     * Set Cookie to Cookies->$cookie list, cookie of the same name will be overwritten
     *
     * @param $options
     *
     * @return $this
     */
    public function setCookie(array $options): self
    {
        if (isset($options['name']) && is_string($options['name'])) {
            //正常COOKIE的设定
            $this->cookies->add($options);
        } else {
            if (key($options) === 0) {
                //数组COOKIE的设定
                foreach ($options as $parent_name => $obj) {
                    $obj['name'] = $parent_name . '[' . $obj['name'] . ']';
                    $this->cookies->add($obj);
                }
            }
        }

        return $this;
    }

    /**
     * Unset cookie
     *
     * @param string $name
     * @param string $path
     * @param string $domain
     *
     * @return $this
     */
    public function unsetCookie(string $name, string $path = '', string $domain = ''): self
    {
        $this->cookies->add([
            'name' => $name,
            'expires' => -1,
            'path' => $path,
            'domain' => $domain,
        ]);

        return $this;
    }

    protected function __cloneCookiesManager()
    {
        $this->cookies = clone $this->cookies;
        if ($this->incremental_cookies) {
            $this->incremental_cookies = new Cookies;
        }
    }
}
