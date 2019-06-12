<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/10/30 下午20:00
 */

namespace Swlib\Http;

use Exception;
use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

Class PHPMemory implements StreamInterface
{
    /** @var resource|null */
    protected $stream;
    /** @var bool */
    protected $seekable;
    /** @var bool */
    protected $readable;
    /** @var bool */
    protected $writable;

    protected $readList = [
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
    protected $writeList = [
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
     * Reads all data from the stream into a string, from the beginning to end.
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
     * Closes the stream and any underlying resources.
     */
    public function close(): void
    {
        $res = $this->detach();
        if (is_resource($res)) {
            fclose($res);
        }
    }

    /**
     * Separates any underlying resources from the stream.
     *
     * @return resource|null
     */
    public function detach()
    {
        if (!isset($this->stream)) {
            return null;
        }
        $this->readable = $this->writable = $this->seekable = false;
        $result = $this->stream;
        $this->stream = null;

        return $result;
    }

    /**
     * Get the size of the stream if known.
     *
     * @return int|null
     */
    public function getSize()
    {
        if (!$this->stream) {
            return null;
        }
        $stats = fstat($this->stream);
        if (isset($stats['size'])) {
            return $stats['size'];
        } else {
            return null;
        }
    }

    /**
     * Returns the current position of the file read/write pointer
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
     * Returns true if the stream is at the end of the stream.
     *
     * @return bool
     */
    public function eof(): bool
    {
        return !$this->stream || feof($this->stream);
    }

    /**
     * Returns whether or not the stream is seekable.
     *
     * @return bool
     */
    public function isSeekable(): bool
    {
        return $this->seekable;
    }

    /**
     * Seek to a position in the stream.
     *
     * @param     $offset
     * @param int $whence
     */
    public function seek($offset, $whence = SEEK_SET): void
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
     * Seek to the beginning of the stream.
     */
    public function rewind(): void
    {
        $this->seek(0);
    }

    /**
     * Returns whether or not the stream is writable.
     *
     * @return bool
     */
    public function isWritable(): bool
    {
        return $this->writable;
    }

    /**
     * Write data to the stream.
     * Note: you should pay attention to the location of the data stream pointer when writing.
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

    /**
     * Returns whether or not the stream is readable.
     *
     * @return bool
     */
    public function isReadable(): bool
    {
        return $this->readable;
    }

    /**
     * Read data from the stream.
     *
     * @param int $length
     * @return string
     */
    public function read($length): string
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

    /**
     * Returns the remaining contents in a string.
     *
     * @return bool|string
     */
    public function getContents()
    {
        $contents = stream_get_contents($this->stream);
        if ($contents === false) {
            throw new RuntimeException('Unable to read stream contents');
        }

        return $contents;
    }

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     *
     * @param null $key
     * @return array|mixed|null
     */
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

    /**
     * @return resource|null
     */
    public function getStreamResource()
    {
        return $this->stream;
    }

    /**
     * @param int $size
     * @return bool
     */
    public function truncate(int $size = 0): bool
    {
        return ftruncate($this->stream, $size);
    }

    public function __destruct()
    {
        $this->close();
    }

}
