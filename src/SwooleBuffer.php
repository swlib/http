<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/1/23 下午2:23
 */

namespace Swlib\Http;

class SwooleBuffer implements StreamInterface
{
    public $buffer;
    public $pos = 0;
    private $writable = true;

    public function __construct(string $data = '', ?int $length = null)
    {
        $length = $length ?? max(strlen($data), 32);
        $this->buffer = new \Swoole\Buffer($length);
        $this->write($data);
    }

    public function getSize(): int
    {
        return $this->buffer->length;
    }

    /**
     * Add data to buffer
     *
     * @param string $data
     *
     * @return $this
     */
    public function write($data = ''): self
    {
        If ($this->writable && $data !== '') {
            $this->buffer->append($data);
        }

        return $this;
    }

    /**
     * Overwrite the entire buffer
     *
     * @param string $data
     *
     * @return $this
     */
    public function overWrite(string $data = null): self
    {
        If ($this->writable && $data !== '') {
            $this->buffer->clear();
            $this->buffer->write(0, $data);
        }

        return $this;
    }

    /**
     * Clear the buffer
     *
     * @return $this
     */
    public function clear(): self
    {
        $this->buffer->clear();

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->buffer->read(0, $this->buffer->length);
    }

    /**
     * Close the buffer
     */
    public function close(): void
    {
        $this->writable = false;
    }

    /**
     * Clear totally
     */
    public function __destruct()
    {
        $this->buffer->clear();
        $this->buffer = null;
    }

    /**
     * Separates any underlying resources from the stream.
     *
     * After the stream has been detached, the stream is in an unusable state.
     *
     * @return resource|null Underlying PHP stream, if any
     */
    public function detach(): void
    {
        $this->close();
    }

    /**
     * Returns the current position of the file read/write pointer
     *
     * @return int Position of the file pointer
     * @throws \RuntimeException on error.
     */
    public function tell(): int
    {
        return $this->pos;
    }

    /**
     * Returns true if the stream is at the end of the stream.
     *
     * @return bool
     */
    public function eof()
    {
        return $this->pos >= $this->buffer->length;
    }

    /**
     * Returns whether or not the stream is seekable.
     *
     * @return bool
     */
    public function isSeekable(): bool
    {
        return true;
    }

    /**
     * Seek to a position in the stream.
     *
     * @link http://www.php.net/manual/en/function.fseek.php
     * @param int $offset Stream offset
     * @param int $whence Specifies how the cursor position will be calculated
     *     based on the seek offset. Valid values are identical to the built-in
     *     PHP $whence values for `fseek()`.  SEEK_SET: Set position equal to
     *     offset bytes SEEK_CUR: Set position to current location plus offset
     *     SEEK_END: Set position to end-of-stream plus offset.
     * @throws \RuntimeException on failure.
     */
    public function seek($offset, $whence = SEEK_SET): void
    {
        $size = $this->getSize();
        switch ($whence) {
            case SEEK_CUR:
                $pos = $this->pos + $offset;
                break;
            case SEEK_END:
                $pos = $size - 1 - $offset;
                break;
            case SEEK_SET:
            default:
                $pos = $offset;
        }
        if ($pos < 0 || $pos >= $size) {
            throw new \RuntimeException("Wrong Offset number $offset !");
        }
    }

    /**
     * Seek to the beginning of the stream.
     *
     * If the stream is not seekable, this method will raise an exception;
     * otherwise, it will perform a seek(0).
     *
     * @see seek()
     * @link http://www.php.net/manual/en/function.fseek.php
     * @throws \RuntimeException on failure.
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
        return true;
    }

    /**
     * Returns whether or not the stream is readable.
     *
     * @return bool
     */
    public function isReadable(): bool
    {
        return true;
    }

    /**
     * Read data from the stream.
     *
     * @param int $length Read up to $length bytes from the object and return
     *     them. Fewer than $length bytes may be returned if underlying stream
     *     call returns fewer bytes.
     * @return string Returns the data read from the stream, or an empty string
     *     if no bytes are available.
     * @throws \RuntimeException if an error occurs.
     */
    public function read($length): string
    {
        $pos = $this->pos;
        $this->pos = min($pos + $length, $this->getSize());
        $length = $this->pos - $pos;
        return $this->buffer->read($pos, $length) ?: '';
    }

    /**
     * Returns the remaining contents in a string
     *
     * @return string
     * @throws \RuntimeException if unable to read or an error occurs while
     *     reading.
     */
    public function getContents()
    {
        throw new \BadMethodCallException('Not implement!');
    }

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     *
     * The keys returned are identical to the keys returned from PHP's
     * stream_get_meta_data() function.
     *
     * @link http://php.net/manual/en/function.stream-get-meta-data.php
     * @param string $key Specific metadata to retrieve.
     * @return array|mixed|null Returns an associative array if no key is
     *     provided. Returns a specific key value if a key is provided and the
     *     value is found, or null if the key is not found.
     */
    public function getMetadata($key = null)
    {
        return null;
    }

}
