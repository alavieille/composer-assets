<?php

namespace Alav\ComposerAssets\JsonFile;

use Composer\Json\JsonFile;

/**
 * Class AssetsLockFile
 */
class AssetsLockFile extends AbstractJsonFile
{
    const ASSETS_LOCK_FILE_NAME = 'assets.lock';

    protected $vendorDir;

    /**
     * @param array $jsonContent
     */
    public function createAssetsLockFile(array $jsonContent)
    {
        $jsonFile = $this->initJsonFile(self::ASSETS_LOCK_FILE_NAME);
        $jsonFile->write($jsonContent);
    }

    /**
     * @return array
     *
     * @throws JsonFileException
     */
    public function readJsonFile()
    {
        $jsonFile = $this->initJsonFile(self::ASSETS_LOCK_FILE_NAME);

        return $jsonFile->read();
    }

    /**
     * @return bool
     */
    public function existFile()
    {
        $jsonFile = new JsonFile(self::ASSETS_LOCK_FILE_NAME);

        return $jsonFile->exists();
    }
}
