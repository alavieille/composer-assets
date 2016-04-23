<?php

namespace Alav\ComposerAssets\JsonFile;

use Exception;

/**
 * Class JsonFileException
 */
class JsonFileException extends Exception
{
    protected $message = 'File already exist. Can\'t install assets';

    /**
     * @param null           $message
     * @param int            $code
     * @param Exception|null $previous
     */
    public function __construct($message = null, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
