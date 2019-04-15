<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/10/30 下午20:00
 */

namespace Swlib\Http;

use Exception;
use InvalidArgumentException;
use RuntimeException;

Class PHPMemory implements StreamInterface
{

    private $stream;
    private $seekable;
    private $readable;
    private $writable;
    private $readList = [
        'r' => true,
        'w+' => true,
        'r+' => true,
        'x+' => true,
        'c+' => true,
        'rb' => true,
        'w+b' => true,
        'r+b' => true,
        'x+b' => true,
        'c+b' => true,
        'rt' => true,
        'w+t' => true,
        'r+t' => true,
        'x+t' => true,
        'c+t' => true,
        'a+' => true
    ];
    private $writeList = [
        'w' => true,
        'w+' => true,
        'rw' => true,
        'r+' => true,
        'x+' => true,
        'c+' => true,
        'wb' => true,
        'w+b' => true,
        'r+b' => true,
        'x+b' => true,
        'c+b' => true,
        'w+t' => true,
        'r+t' => true,
        'x+t' => true,
        'c+t' => true,
        'a' => true,
        'a+' => true
    ];

    public function __construct($resource = '', $mode = 'r+')
    {
        switch (gettype($resource)) {
            case 'resource':
                {
                    $this->stream = $resource;
                    break;
                }
            case 'object':
                {
                    if (method_exists($resource, '__toString')) {
                        $resource = $resource->__toString();
                        $this->stream = fopen('php://memory', $mode);
                        if ($resource !== '') {
                            fwrite($this->stream, $resource);
                        }
                        break;
                    } else {
                        throw new InvalidArgumentException('Invalid resource type: ' . gettype($resource));
                    }
                }
            default:
                {
                    $this->stream = fopen('php://memory', $mode);
                    try {
                        $resource = (string)$resource;
                        if ($resource !== '') {
                            fwrite($this->stream, $resource);
                        }
                    } catch (Exception $exception) {
                        throw new InvalidArgumentException('Invalid resource type: ' . gettype($resource));
                    }
                }
        }
        $info = stream_get_meta_data($this->stream);
        $this->seekable = $info['seekable'];
        $this->readable = isset($this->readList[$info['mode']]);
        $this->writable = isset($this->writeList[$info['mode']]);
    }

    /**
     * 返回Stream对象中完整的流数据
     *
     * @return string
     */
    public function __toString()
    {
        try {
            $this->rewind();
            return (string)stream_get_contents($this->stream);
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * 关闭当前流对象，流对象中的数据也随之清空
     */
    public function close()
    {
        $res = $this->detach();
        if (is_resource($res)) {
            fclose($res);
        }
    }

    public function detach()
    {
        if (!isset($this->stream)) {
            return null;
        }
        $this->readable = $this->writable = $this->seekable = false;
        $result = $this->stream;
        unset($this->stream);

        return $result;
    }

    /**
     * 获取当前Stream对象中数据的大小（长度）
     *
     * @return int|null
     */
    public function getSize()
    {
        $stats = fstat($this->stream);
        if (isset($stats['size'])) {
            return $stats['size'];
        } else {
            return null;
        }
    }

    /**
     * 获取当前数据流指针所处位置
     *
     * @return bool|int
     */
    public function tell()
    {
        $result = ftell($this->stream);
        if ($result === false) {
            throw new RuntimeException('Unable to determine stream position');
        }

        return $result;
    }

    /**
     * 判断当数据流指针是否处于资源结束位置
     *
     * @return bool
     */
    public function eof()
    {
        return !$this->stream || feof($this->stream);
    }

    public function isSeekable()
    {
        return $this->seekable;
    }

    /**
     * 移动数据流指针到指定位置
     *
     * @param     $offset
     * @param int $whence
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (!$this->seekable) {
            throw new RuntimeException('Stream is not seekable');
        } elseif (fseek($this->stream, $offset, $whence) === -1) {
            throw new RuntimeException(
                'Unable to seek to stream position ' . $offset .
                ' with whence ' . var_export($whence, true)
            );
        }
    }

    /**
     * 将数据流指针移动至开始位置
     */
    public function rewind()
    {
        $this->seek(0);
    }

    public function isWritable()
    {
        return $this->writable;
    }

    /**
     * 向当前数据流写入数据
     * 注意：写入时应该注意数据流指针所处位置
     *
     * @param string $string
     *
     * @return bool|int
     */
    public function write($string)
    {
        if (!$this->writable) {
            throw new RuntimeException('Cannot write to a non-writable stream');
        }
        $result = fwrite($this->stream, $string);
        if ($result === false) {
            throw new RuntimeException('Unable to write to stream');
        }

        return $result;
    }

    public function isReadable()
    {
        return $this->readable;
    }

    public function read($length)
    {
        if (!$this->readable) {
            throw new RuntimeException('Cannot read from non-readable stream');
        }
        if ($length < 0) {
            throw new RuntimeException('Length parameter cannot be negative');
        }
        if (0 === $length) {
            return '';
        }
        $string = fread($this->stream, $length);
        if (false === $string) {
            throw new RuntimeException('Unable to read from stream');
        }

        return $string;
    }

    public function getContents()
    {
        $contents = stream_get_contents($this->stream);
        if ($contents === false) {
            throw new RuntimeException('Unable to read stream contents');
        }

        return $contents;
    }

    public function getMetadata($key = null)
    {
        if (!isset($this->stream)) {
            return $key ? null : [];
        } elseif (!$key) {
            return stream_get_meta_data($this->stream);
        } else {
            $meta = stream_get_meta_data($this->stream);

            return isset($meta[$key]) ? $meta[$key] : null;
        }
    }

    public function __destruct()
    {
        $this->close();
    }

    public function getStreamResource()
    {
        return $this->stream;
    }

    public function truncate($size = 0)
    {
        return ftruncate($this->stream, $size);
    }

    public function clear()
    {
        return $this->truncate(0);
    }

    public function overWrite(string $data = null)
    {
        if ($data === '' || $data === null) {
            $this->clear();
        } else {
            $this->rewind();
            $this->write($data);
            $this->truncate(strlen($data));
        }
    }

}
