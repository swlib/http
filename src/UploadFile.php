<?php

namespace Swlib\Http;

class UploadFile
{

    private $stream;
    private $size;
    private $error;
    private $clientFileName;
    private $clientMediaType;

    function __construct($tempName, $size, $errorStatus, $clientFilename = null, $clientMediaType = null)
    {
        $this->stream = new BufferStream(fopen($tempName, "r+"));
        $this->error = $errorStatus;
        $this->size = $size;
        $this->clientFileName = $clientFilename;
        $this->clientMediaType = $clientMediaType;
    }

    /**
     * Return data stream of uploaded file
     *
     * @return BufferStream
     */
    public function getStream()
    {
        return $this->stream;
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
        return file_put_contents($targetPath, $this->stream) ? true : false;
    }

    /**
     * Get file size
     *
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Get error message when uploading files
     *
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Get the client file name of the file
     *
     * @return null
     */
    public function getClientFilename()
    {
        return $this->clientFileName;
    }

    /**
     * Get file type
     *
     * @return string
     */
    public function getClientMediaType()
    {
        return $this->clientMediaType;
    }

}