<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/4/9 下午8:44
 */

namespace Swlib\Http;

use BadMethodCallException;
use Psr\Http\Message\UploadedFileInterface;

class SwUploadFile implements UploadedFileInterface
{
    private $stream;
    private $offset;
    private $size;
    private $clientFileName;
    private $clientMediaType;

    public function __construct(
        string $file_path,
        ?string $file_nickname = null,
        ?string $clientMediaType = null,
        ?int $offset = 0,
        ?int $size = null
    ) {
        $this->stream = $file_path;
        $this->clientFileName = $file_nickname ?? null ?: basename($this->stream);
        $this->clientMediaType = $clientMediaType ?? null ?:
                (($extension = pathinfo($file_path, PATHINFO_EXTENSION)) ?
                    ContentType::get($extension) :
                    null
                );
        $this->offset = $offset ?? 0;
        $this->size = $size;
    }

    public static function create($file): self
    {
        if (is_string($file)) {
            $file = new self($file);
        } elseif (is_array($file)) {
            $file = new self(
                $file['path'] ?? '',
                $file['nickname'] ?? $file['name'] ?? null,
                $file['type'] ?? $file['mime'] ?? null,
                $file['offset'] ?? null,
                $file['size'] ?? null
            );
        }

        return $file;
    }

    public function getFilePath(): string
    {
        return $this->stream;
    }

    /**
     * Return data stream of uploaded file
     *
     * @return string
     */
    public function getStream(): string
    {
        return $this->stream;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * Get file size
     *
     * @return int|null
     */
    public function getSize(): ?int
    {
        return $this->size;
    }

    /**
     * Get the client file name of the file
     *
     * @return null
     */
    public function getClientFilename(): string
    {
        return $this->clientFileName;
    }

    /**
     * Get file type
     *
     * @return string
     */
    public function getClientMediaType(): string
    {
        return $this->clientMediaType;
    }

    /**
     * Save uploaded file as entity file
     *
     * @param $targetPath
     *
     * @return bool
     */
    public function moveTo($targetPath)
    {
        throw new BadMethodCallException('Are you dreaming?');
    }

    /**
     * Get error message when uploading files
     *
     * @return mixed
     */
    public function getError()
    {
        throw new BadMethodCallException('Nop');
    }
}
