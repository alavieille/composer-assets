<?php

namespace Alav\ComposerAssets\JsonFile;

use Exception;

/**
 * Class JsonFileException
 */
class JsonFileException extends Exception
{
    protected $message = 'JsonFileException: File {fileName} already exist. Can\'t install assets. Remove this file before.';

    /**
     * construct json file exception
     *
     * @param String $fileName
     */
    public function __construct($fileName) {
        parent::__construct(str_replace('{fileName}', $fileName, $this->message));
    }
}
