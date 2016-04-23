<?php

namespace Alav\ComposerAssets\JsonFile;

use Composer\Json\JsonFile;

/**
 * Class NpmJsonFile
 */
class NpmJsonFile extends AbstractJsonFile
{
    const NPM_FILE_NAME = 'package.json';

    /**
     * @param array $jsonContent
     */
    public function createPackageJson(array $jsonContent)
    {
        $jsonFile = $this->createJsonFile(self::NPM_FILE_NAME);
        $jsonFile->write($jsonContent);
    }
}
