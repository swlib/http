<?php

namespace Swlib\Http;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use TypeError;

/**
 * PSR-7 URI implementation, **From guzzle and make some changes.**
 *
 * @author Michael Dowling
 * @author Tobias Schultze
 * @author Matthew Weier O'Phinney
 */
class Uri implements UriInterface
{
    /**
     * Absolute http and https URIs require a host per RFC 7230 Section 2.7
     * but in generic URIs the host can be empty. So for http(s) URIs
     * we apply this default host when no host is given yet to form a
     * valid URI.
     */
    const HTTP_DEFAULT_HOST = 'localhost';

    private static $defaultPorts = [
        'http' => 80,
        'https' => 443,
        'ftp' => 21,
        'gopher' => 70,
        'nntp' => 119,
        'news' => 119,
        'telnet' => 23,
        'tn3270' => 23,
        'imap' => 143,
        'pop' => 110,
        'ldap' => 389,
    ];

    private static $charUnreserved = 'a-zA-Z0-9_\-\.~';
    private static $charSubDelims = '!\$&\'\(\)\*\+,;=';
    private static $replaceQuery = ['=' => '%3D', '&' => '%26'];

    /** @var string Uri scheme. */
    private $scheme = '';

    /** @var string Uri user info. */
    private $userInfo = '';

    /** @var string Uri host. */
    private $host = '';

    /** @var int|null Uri port. */
    private $port;

    /** @var string Uri path. */
    private $path = '';

    /** @var string Uri query string. */
    private $query = '';

    /** @var string Uri fragment. */
    private $fragment = '';

    /**
     * @param string $uri URI to parse
     */
    public function __construct(string $uri = '')
    {
        // weak type check to also accept null until we can add scalar type hints
        if ($uri != '') {
            $parts = parse_url($uri);
            if ($parts === false) {
                throw new InvalidArgumentException("Unable to parse URI: $uri");
            }
            $this->applyParts($parts);
        }
    }

    public function __toString()
    {
        return self::composeComponents(
            $this->scheme,
            $this->getAuthority(),
            $this->path,
            $this->query,
            $this->fragment
        );
    }

    /**
     * Composes a URI reference string from its various components.
     *
     * Usually this method does not need to be called manually but instead is used indirectly via
     * `Psr\Http\Message\UriInterface::__toString`.
     *
     * PSR-7 UriInterface treats an empty component the same as a missing component as
     * getQuery(), getFragment() etc. always return a string. This explains the slight
     * difference to RFC 3986 Section 5.3.
     *
     * Another adjustment is that the authority separator is added even when the authority is missing/empty
     * for the "file" scheme. This is because PHP stream functions like `file_get_contents` only work with
     * `file:///myfile` but not with `file:/myfile` although they are equivalent according to RFC 3986. But
     * `file:///` is the more common syntax for the file scheme anyway (Chrome for example redirects to
     * that format).
     *
     * @param string $scheme
     * @param string $authority
     * @param string $path
     * @param string $query
     * @param string $fragment
     *
     * @return string
     *
     * @link https://tools.ietf.org/html/rfc3986#section-5.3
     */
    public static function composeComponents($scheme, $authority, $path, $query, $fragment): string
    {
        $uri = '';

        // weak type checks to also accept null until we can add scalar type hints
        if ($scheme != '') {
            $uri .= $scheme . ':';
        }

        if ($authority != '' || $scheme === 'file') {
            $uri .= '//' . $authority;
        }

        $uri .= $path;

        if ($query != '') {
            $uri .= '?' . $query;
        }

        if ($fragment != '') {
            $uri .= '#' . $fragment;
        }

        return $uri;
    }

    /**
     * Whether the URI has the default port of the current scheme.
     *
     * `Psr\Http\Message\UriInterface::getPort` may return null or the standard port. This method can be used
     * independently of the implementation.
     *
     * @param UriInterface $uri
     *
     * @return bool
     */
    public static function isDefaultPort(UriInterface $uri): bool
    {
        return $uri->getPort() === null
            || (isset(self::$defaultPorts[$uri->getScheme()]) && $uri->getPort() === self::$defaultPorts[$uri->getScheme()]);
    }

    /**
     * Whether the URI is absolute, i.e. it has a scheme.
     *
     * An instance of UriInterface can either be an absolute URI or a relative reference. This method returns true
     * if it is the former. An absolute URI has a scheme. A relative reference is used to express a URI relative
     * to another URI, the base URI. Relative references can be divided into several forms:
     * - network-path references, e.g. '//example.com/path'
     * - absolute-path references, e.g. '/path'
     * - relative-path references, e.g. 'subpath'
     *
     * @param UriInterface $uri
     *
     * @return bool
     * @see Uri::isNetworkPathReference
     * @see Uri::isAbsolutePathReference
     * @see Uri::isRelativePathReference
     * @link https://tools.ietf.org/html/rfc3986#section-4
     */
    public static function isAbsolute(UriInterface $uri): bool
    {
        return $uri->getScheme() !== '';
    }

    /**
     * Whether the URI is a network-path reference.
     *
     * A relative reference that begins with two slash characters is termed an network-path reference.
     *
     * @param UriInterface $uri
     *
     * @return bool
     * @link https://tools.ietf.org/html/rfc3986#section-4.2
     */
    public static function isNetworkPathReference(UriInterface $uri): bool
    {
        return $uri->getScheme() === '' && $uri->getAuthority() !== '';
    }

    /**
     * Whether the URI is a absolute-path reference.
     *
     * A relative reference that begins with a single slash character is termed an absolute-path reference.
     *
     * @param UriInterface $uri
     *
     * @return bool
     * @link https://tools.ietf.org/html/rfc3986#section-4.2
     */
    public static function isAbsolutePathReference(UriInterface $uri): bool
    {
        return $uri->getScheme() === ''
            && $uri->getAuthority() === ''
            && isset($uri->getPath()[0])
            && $uri->getPath()[0] === '/';
    }

    /**
     * Whether the URI is a relative-path reference.
     *
     * A relative reference that does not begin with a slash character is termed a relative-path reference.
     *
     * @param UriInterface $uri
     *
     * @return bool
     * @link https://tools.ietf.org/html/rfc3986#section-4.2
     */
    public static function isRelativePathReference(UriInterface $uri): bool
    {
        return $uri->getScheme() === ''
            && $uri->getAuthority() === ''
            && (!isset($uri->getPath()[0]) || $uri->getPath()[0] !== '/');
    }

    /**
     * Whether the URI is a same-document reference.
     *
     * A same-document reference refers to a URI that is, aside from its fragment
     * component, identical to the base URI. When no base URI is given, only an empty
     * URI reference (apart from its fragment) is considered a same-document reference.
     *
     * @param UriInterface $uri The URI to check
     * @param UriInterface|null $base An optional base URI to compare against
     *
     * @return bool
     * @link https://tools.ietf.org/html/rfc3986#section-4.4
     */
    public static function isSameDocumentReference(UriInterface $uri, UriInterface $base = null): bool
    {
        if ($base !== null) {
            $uri = UriResolver::resolve($base, $uri);

            return ($uri->getScheme() === $base->getScheme())
                && ($uri->getAuthority() === $base->getAuthority())
                && ($uri->getPath() === $base->getPath())
                && ($uri->getQuery() === $base->getQuery());
        }

        return $uri->getScheme() === '' && $uri->getAuthority() === '' && $uri->getPath() === '' && $uri->getQuery() === '';
    }

    /**
     * Removes dot segments from a path and returns the new path.
     *
     * @param string $path
     *
     * @return string
     *
     * @deprecated since version 1.4. Use UriResolver::removeDotSegments instead.
     * @see UriResolver::removeDotSegments
     */
    public static function removeDotSegments($path): string
    {
        return UriResolver::removeDotSegments($path);
    }

    /**
     * Converts the relative URI into a new URI that is resolved against the base URI.
     *
     * @param string|UriInterface $base Base URI
     * @param string|UriInterface $rel Relative URI
     *
     * @return UriInterface
     *
     * @see UriResolver::resolve
     */
    public static function resolve($base, $rel): ?UriInterface
    {
        if ($base && $rel) {
            if (!($base instanceof UriInterface)) {
                $base = new self($base);
            }
            if (!($rel instanceof UriInterface)) {
                $rel = new self($rel);
            }
            return UriResolver::resolve($base, $rel);
        } elseif ($rel) {
            return $rel instanceof UriInterface ? $rel : new self($rel);
        } elseif ($base) {
            return $base instanceof UriInterface ? $base : new self($base);
        } else {
            return null;
        }
    }

    /**
     * Creates a new URI with a specific query string value removed.
     *
     * Any existing query string values that exactly match the provided key are
     * removed.
     *
     * @param UriInterface $uri URI to use as a base.
     * @param string $key Query string key to remove.
     *
     * @return UriInterface
     */
    public static function withoutQueryValue(UriInterface $uri, $key): UriInterface
    {
        $current = $uri->getQuery();
        if ($current === '') {
            return $uri;
        }

        $decodedKey = rawurldecode($key);
        $result = array_filter(explode('&', $current), function ($part) use ($decodedKey) {
            return rawurldecode(explode('=', $part)[0]) !== $decodedKey;
        });

        return $uri->withQuery(implode('&', $result));
    }

    /**
     * Creates a new URI with a specific query string value.
     *
     * Any existing query string values that exactly match the provided key are
     * removed and replaced with the given key value pair.
     *
     * A value of null will set the query string key without a value, e.g. "key"
     * instead of "key=value".
     *
     * @param UriInterface $uri URI to use as a base.
     * @param string $key Key to set.
     * @param string|null $value Value to set
     *
     * @return UriInterface
     */
    public static function withQueryValue(UriInterface $uri, $key, $value): UriInterface
    {
        $current = $uri->getQuery();

        if ($current === '') {
            $result = [];
        } else {
            $decodedKey = rawurldecode($key);
            $result = array_filter(explode('&', $current), function ($part) use ($decodedKey) {
                return rawurldecode(explode('=', $part)[0]) !== $decodedKey;
            });
        }

        // Query string separators ("=", "&") within the key or value need to be encoded
        // (while preventing double-encoding) before setting the query string. All other
        // chars that need percent-encoding will be encoded by withQuery().
        $key = strtr($key, self::$replaceQuery);

        if ($value !== null) {
            $result[] = $key . '=' . strtr($value, self::$replaceQuery);
        } else {
            $result[] = $key;
        }

        return $uri->withQuery(implode('&', $result));
    }

    /**
     * Creates a URI from a hash of `parse_url` components.
     *
     * @param array $parts
     *
     * @return UriInterface
     * @link http://php.net/manual/en/function.parse-url.php
     *
     * @throws TypeError If the components do not form a valid URI.
     */
    public static function fromParts(array $parts): UriInterface
    {
        $uri = new self;
        $uri->applyParts($parts);
        $uri->validateState();

        return $uri;
    }

    public function getScheme()
    {
        return $this->scheme;
    }

    public function getAuthority()
    {
        $authority = $this->host;
        if ($this->userInfo !== '') {
            $authority = $this->userInfo . '@' . $authority;
        }

        if ($this->port !== null) {
            $authority .= ':' . $this->port;
        }

        return $authority;
    }

    public function getUserInfo()
    {
        return $this->userInfo;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getRealPort()
    {
        return $this->port ?? (self::$defaultPorts[$this->scheme] ?? null);
    }

    public function getDir()
    {
        $is_dir = substr($this->path, -1, 1) === '/';
        $split = explode('/', $this->path);
        $final_name = end($split);
        //tip: it implements dirty
        if (strpos($final_name, '.') !== false) {//有后缀,可能是文件(uri不标准情况下)
            $is_file = true;
        } else {
            $is_file = false;
        }

        if ($is_dir || !$is_file) {
            return $this->path;
        } else {
            array_pop($split);

            return implode('/', $split);
        }
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function getPathWithQuery()
    {
        return $this->getPath() . (($query = $this->getQuery()) ? '?' . $query : '');
    }

    public function getFragment()
    {
        return $this->fragment;
    }

    public function withScheme($scheme)
    {
        $scheme = $this->filterScheme($scheme);

        if ($this->scheme === $scheme) {
            return $this;
        }

        $this->scheme = $scheme;
        $this->removeDefaultPort();
        $this->validateState();

        return $this;
    }

    public function withUserInfo($user, $password = null)
    {
        $info = $user;
        if ($password != '') {
            $info .= ':' . $password;
        }

        if ($this->userInfo === $info) {
            return $this;
        }

        $this->userInfo = $info;
        $this->validateState();

        return $this;
    }

    public function withHost($host)
    {
        $host = $this->filterHost($host);

        if ($this->host === $host) {
            return $this;
        }

        $this->host = $host;
        $this->validateState();

        return $this;
    }

    public function withPort($port)
    {
        $port = $this->filterPort($port);

        if ($this->port === $port) {
            return $this;
        }

        $this->port = $port;
        $this->removeDefaultPort();
        $this->validateState();

        return $this;
    }

    public function withPath($path)
    {
        $path = $this->filterPath($path);

        if ($this->path === $path) {
            return $this;
        }

        $this->path = $path;
        $this->validateState();

        return $this;
    }

    /**
     * @param string|array $query
     * @return $this|static
     */
    public function withQuery($query)
    {
        if (is_array($query)) {
            $query = http_build_query($query);
        }
        $query = $this->filterQueryAndFragment($query);

        if ($this->query === $query) {
            return $this;
        }

        $this->query = $query;

        return $this;
    }

    /**
     * @param string|array $query
     * @return $this|static
     */
    public function withAddedQuery($query): self
    {
        parse_str($this->getQuery(), $old);
        if (is_string($query)) {
            parse_str($query, $query);
        }
        $this->withQuery($query + $old);

        return $this;
    }

    public function withFragment($fragment)
    {
        $fragment = $this->filterQueryAndFragment($fragment);

        if ($this->fragment === $fragment) {
            return $this;
        }

        $this->fragment = $fragment;

        return $this;
    }

    /**
     * Apply parse_url parts to a URI.
     *
     * @param array $parts Array of parse_url parts to apply.
     */
    private function applyParts(array $parts)
    {
        $this->scheme = isset($parts['scheme'])
            ? $this->filterScheme($parts['scheme'])
            : '';
        $this->userInfo = isset($parts['user']) ? $parts['user'] : '';
        $this->host = isset($parts['host'])
            ? $this->filterHost($parts['host'])
            : '';
        $this->port = isset($parts['port'])
            ? $this->filterPort($parts['port'])
            : null;
        $this->path = isset($parts['path'])
            ? $this->filterPath($parts['path'])
            : '';
        $this->query = isset($parts['query'])
            ? $this->filterQueryAndFragment($parts['query'])
            : '';
        $this->fragment = isset($parts['fragment'])
            ? $this->filterQueryAndFragment($parts['fragment'])
            : '';
        if (isset($parts['pass'])) {
            $this->userInfo .= ':' . $parts['pass'];
        }

        $this->removeDefaultPort();
    }

    /**
     * @param string $scheme
     *
     * @return string
     *
     * @throws TypeError If the scheme is invalid.
     */
    private function filterScheme(string $scheme): string
    {
        return strtolower($scheme);
    }

    /**
     * @param string $host
     *
     * @return string
     *
     * @throws TypeError If the host is invalid.
     */
    private function filterHost(string $host): string
    {
        return strtolower($host);
    }

    /**
     * @param int|null $port
     *
     * @return int|null
     *
     * @throws TypeError If the port is invalid.
     */
    private function filterPort(?int $port): ?int
    {
        if ($port === null) {
            return null;
        }

        $port = (int)$port;
        if (1 > $port || 0xffff < $port) {
            throw new InvalidArgumentException(
                sprintf('Invalid port: %d. Must be between 1 and 65535', $port)
            );
        }

        return $port;
    }

    private function removeDefaultPort()
    {
        if ($this->port !== null && self::isDefaultPort($this)) {
            $this->port = null;
        }
    }

    /**
     * Filters the path of a URI
     *
     * @param string $path
     *
     * @return string
     *
     * @throws TypeError If the path is invalid.
     */
    private function filterPath(string $path): string
    {
        return preg_replace_callback(
            '/(?:[^' . self::$charUnreserved . self::$charSubDelims . '%:@\/]++|%(?![A-Fa-f0-9]{2}))/',
            [$this, 'rawUrlencodeMatchZero'],
            $path
        );
    }

    /**
     * Filters the query string or fragment of a URI.
     *
     * @param string $str
     *
     * @return string
     *
     * @throws TypeError If the query or fragment is invalid.
     */
    private function filterQueryAndFragment(string $str): string
    {
        return preg_replace_callback(
            '/(?:[^' . self::$charUnreserved . self::$charSubDelims . '%:@\/\?]++|%(?![A-Fa-f0-9]{2}))/',
            [$this, 'rawUrlencodeMatchZero'],
            $str
        );
    }

    private function rawUrlencodeMatchZero(array $match)
    {
        return rawurlencode($match[0]);
    }

    private function validateState()
    {
        if ($this->host === '' && ($this->scheme === 'http' || $this->scheme === 'https')) {
            $this->host = self::HTTP_DEFAULT_HOST;
        }

        if ($this->getAuthority() === '') {
            if (0 === strpos($this->path, '//')) {
                throw new InvalidArgumentException('The path of a URI without an authority must not start with two slashes "//"');
            }
            if ($this->scheme === '' && false !== strpos(explode('/', $this->path, 2)[0], ':')) {
                throw new InvalidArgumentException('A relative URI must not have a path beginning with a segment containing a colon');
            }
        } elseif (isset($this->path[0]) && $this->path[0] !== '/') {
            @trigger_error(
                'The path of a URI with an authority must start with a slash "/" or be empty. Automagically fixing the URI ' .
                'by adding a leading slash to the path is deprecated since version 1.4 and will throw an exception instead.',
                E_USER_DEPRECATED
            );
            $this->path = '/' . $this->path;
            //throw new \InvalidArgumentException('The path of a URI with an authority must start with a slash "/" or be empty');
        }
    }
}
