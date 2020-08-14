<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/1/9 下午9:18
 */

namespace Swlib\Http;

class Util
{
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

    /**
     * 解析单个header
     *
     * @param string $raw_header
     *
     * @return array
     */
    public static function parseHeader(string $raw_header): array
    {
        static $whole_fields = [
            'date' => true,
            'user-agent' => true,
        ];

        /**
         * 生成供于遍历的的header数组 ['row','row','row']
         */
        // Pretend CRLF = LF for compatibility (RFC 2616, section 19.3)
        $raw_header = str_replace("\r\n", "\n", $raw_header);
        // Unfold headers (replace [CRLF] 1*( SP | HT ) with SP) as per RFC 2616 (section 2.2)
        $raw_header = preg_replace('/\n[ \t]/', ' ', $raw_header);
        $header = [];
        $raw_header = preg_replace_callback('/(?:.*)HTTP\/[\d.]+(?: (\d+) [\w -]+)?\n/i',
            function (array $match) use (&$header) {
                if (!empty($match[1])) {
                    $header['status'] = [(int)$match[1]];
                }
                return '';
            }, $raw_header);
        $raw_header = explode("\n", $raw_header);

        /**
         * 生成可用的header数组 ['key'=>['val'],'key'=>['val']，'key'=>['val']]
         */
        foreach ($raw_header as $row) {
            list($key, $value) = explode(':', $row, 2);
            $key = strtolower($key);
            if ($key === 'set-cookie') {
                $header[$key][] = self::trimHeaderValues($value);
            } else {
                $value = self::trimHeaderValues(
                    isset($whole_fields[$key]) ? [$value] : explode(",", $value)
                );
                $header[$key] = $value;
            }
        }

        return $header;
    }

    /**
     * 解析header,传入多个header时返回[{header},{header},{header}]
     *
     * @param string $raw_header
     *
     * @return array
     */
    public static function parseHeaders(string $raw_header): array
    {
        $raw_header = explode("\r\n\r\n", $raw_header);
        if (count($raw_header) > 1) {
            $headers = [];
            foreach ($raw_header as $header) {
                $headers[] = self::parseHeader($header);
            }

            return $headers;
        } else {
            return self::parseHeader($raw_header[0]);
        }
    }

    /**
     * 获取数组最后一个键名
     *
     * @param array $array
     * @return int|string|null
     */
    public static function getLastKey(array $array)
    {
        end($array);
        return key($array);
    }
}
