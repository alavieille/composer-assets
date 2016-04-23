<?php

namespace Alav\ComposerAssets\JsonFile;

use Composer\Json\JsonFile;

/**
 * Class BowerJsonFile
 */
class BowerJsonFile extends AbstractJsonFile
{
    const BOWER_FILE_NAME = 'bower.json';
    const RC_FILE_NAME = '.bowerrc';

    protected $vendorDir;

    /**
     * @param $vendorDir
     */
    public function __construct($vendorDir)
    {
        $this->vendorDir = $vendorDir;
    }

    /**
     * @param array $jsonContent
     */
    public function createBowerJson(array $jsonContent)
    {
        $jsonFile = $this->createJsonFile(self::BOWER_FILE_NAME);
        $jsonFile->write($jsonContent);
        $this->createBoweRc();
    }

    /**
     * Create .bowerrc file
     */
    protected function createBoweRc()
    {
        $jsonFile = new JsonFile(self::RC_FILE_NAME);
        if (!$jsonFile->exists()) {
            $jsonContent = [];
            $jsonContent['directory'] = $this->vendorDir.'/bower_components';
            $jsonFile->write($jsonContent);
        }
    }
}
