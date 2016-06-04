<?php

namespace Alav\ComposerAssets\JsonFile;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;
use Composer\Json\JsonFile;

/**
 * Class AbstractJsonFile
 */
abstract class AbstractJsonFile
{
    /**
     * @param string $fileName
     *
     * @return JsonFile
     * @throws JsonFileException
     */
    protected function initJsonFile($fileName)
    {
        $jsonFile = new JsonFile($fileName);
        if ($jsonFile->exists()) {
            $jsonContent = $jsonFile->read();
            if (isset($jsonContent['name']) && $jsonContent['name'] !== AssetPackagesInterface::NAME_ASSETS) {
                throw new JsonFileException();
            }
        }

        return $jsonFile;
    }
}
