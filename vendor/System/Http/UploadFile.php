<?php

namespace System\Http;

use System\Application;

class UploadFile
{
    /**
     * Application
     *
     * @var \System\Application
     */
    private $app;

    /**
     * Uploaded File Information
     *
     * @var array $file
     */
    private $file = [];

    /**
     * Uploaded File Name (With Extension)
     *
     * @var string $fileName
     */
    private $fileName;

    /**
     * Uploaded File Name (Without Extension)
     *
     * @var string $name
     */
    private $name;

    /**
     * Uploaded File Extension
     *
     * @var string $extension
     */
    private $extension;

    /**
     * Uploaded File Mime
     *
     * @var string $mime
     */
    private $mime;

    /**
     * Uploaded Temp File
     *
     * @var string $tempFile
     */
    private $tempFile;

    /**
     * Uploaded File Size In Bytes
     *
     * @var int $size
     */
    private $size;

    /**
     * Uploaded File Errors
     *
     * @var int $error
     */
    private $error;

    /**
     * The Allowed Image Extensions
     *
     * @var array $imageExtensions
     */
    private $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'webp'];

    /**
     * Constructor
     * 
     * @param string $input
     */
    public function __construct($input)
    {
        $this->getFileInfo($input);
    }

    /**
     * Prepare And Get Uploaded File Info
     * 
     * @param string $input
     * @return void
     */
    private function getFileInfo($input)
    {
        if (empty($_FILES[$input])) return;

        $file = $_FILES[$input];

        $this->errors = $file['error'];

        if ($this->error != UPLOAD_ERR_OK) {
            return;
        }

        $this->file = $file;

        $this->fileName = $this->file['name'];

        $fileNameInfo = pathinfo($this->fileName);

        $this->name = $fileNameInfo['filename'];

        $this->extension = strtolower($fileNameInfo['extension']);

        $this->mime = $this->file['type'];

        $this->size = $this->file['size'];

        $this->tempFile = $this->file['tmp_name'];

    }

    /**
     * Determine Whether The File i Uploaded Or Not
     * 
     * @return boolean
     */
    public function exists()
    {
        return ! empty($this->file);
    }

    /**
     * Get File Name Of The Uploaded File (With Extension)
     * 
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Get File Name Of The Uploaded File (Without Extension)
     * 
     * @return string
     */
    public function getNameOnly()
    {
        return $this->name;
    }

    /**
     * Get Extension Of The Uploaded File
     * 
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Get Mime Type Of The Uploaded File
     * 
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Determine Whether The upload File Is Real Image
     * 
     * @return boolean
     */
    public function isImage()
    {
        return  ( (strpos($this->mime, 'image/') === 0) && in_array($this->extension, $this->imageExtensions) );
    }

    /**
     * Upload The File To The New Destination "target"
     * 
     * @param string $target
     * @param string $newFileName
     * @return string
     */
    public function upload($target, $newFileName = null)
    {
        $fileName = $newFileName ?: sha1(mt_rand()) . '_' . date("y-m-d-h-i-s") . '.' . $this->extension;

        if (! is_dir($target)) {
            mkdir($target, 0777, true);
        }

        $target = rtrim($target, '/');

        $uploadFilePath = $target  . '/' . $fileName;

        move_uploaded_file($this->tempFile, $uploadFilePath);

        return ['fileName' => $fileName, 'filePath' => $uploadFilePath];
    }

    /**
     * Set The File Size
     *
     * @return void
     */
    private function setFileSize()
    {

    }

    /**
     * Get The File Size
     *
     * @return string
     */
    public function getFileSize()
    {
        return $this->size;
    }
}